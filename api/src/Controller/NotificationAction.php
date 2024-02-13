<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationAction extends AbstractController
{
    public function __construct(
        private readonly Security $security
    )
    {
    }


    public function __invoke(): JsonResponse
    {
        dd('ok');
        return $this->security->getUser();
    }
}
