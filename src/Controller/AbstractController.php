<?php
declare(strict_types=1);

namespace App\Controller;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;

abstract class AbstractController extends BaseController
{
    public function __construct(protected InertiaInterface $inertia)
    {
    }
}