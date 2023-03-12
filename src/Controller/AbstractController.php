<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\BuildInertiaDefaultPropsTrait;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractController extends BaseController
{
    use BuildInertiaDefaultPropsTrait;

    public function __construct(
        protected InertiaInterface $inertia,
        protected ValidatorInterface $validator,
        protected RequestStack $requestStack,
    )
    {
    }

    protected function renderWithInertia(
        string $component,
        array $props = [],
        array $viewData = [],
        array $context = []
    ): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            throw new \RuntimeException('There is no current request.');
        }

        $defaultProps = $this->buildDefaultProps($request);

        return $this->inertia->render($component, array_merge($defaultProps, $props), $viewData, $context);
    }
}