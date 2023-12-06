<?php

namespace App\Controller;

use App\Entity\Auth\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private Security $security
    )
    {
    }


    public function __invoke()
    {
        return $this->security->getUser();
    }
}
