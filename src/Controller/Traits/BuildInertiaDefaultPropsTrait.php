<?php
declare(strict_types=1);

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

trait BuildInertiaDefaultPropsTrait
{
    protected function buildDefaultProps(Request $request): array
    {
        $flashSuccessMessage = null;
        $flashErrorMessage = null;

        if ($request->hasSession()) {
            /** @var Session $session */
            $session = $request->getSession();

            if ($session->getFlashBag()->has('success')) {
                $flashSuccessMessages = $session->getFlashBag()->get('success');
                $flashSuccessMessage = reset($flashSuccessMessages);
            }

            if ($session->getFlashBag()->has('success')) {
                $flashErrorMessages = $session->getFlashBag()->get('error');
                $flashErrorMessage = reset($flashErrorMessages);
            }
        }

        return [
            'errors' => [],
            'flash' => [
                'success' => $flashSuccessMessage,
                'error' => $flashErrorMessage
            ]
        ];
    }

}