<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ActivateAction extends AbstractController
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $entityManager

    )
    {
    }


    public function __invoke(Request $request) :JsonResponse
    {
        $token = $request->attributes->get('token');
        $userId = $request->attributes->get('user');
        $user = $this->userRepository->findOneBy(["id" => $userId, "token" => $token]);
        if ($user) {
            $user->setStatus(true);
            $this->entityManager->flush();
            $json = [
                'status' => 'success',
                'code' => '200',
                'message' => 'User account is validate'
            ];
        } else {
            $json = [
                'status' => 'error',
                'code' => '404',
                'message' => 'User not found or account validation failed'
            ];
        }
        return new JsonResponse($json);
    }
}
