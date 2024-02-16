<?php

namespace App\Controller;

use App\Entity\Auth\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ActivateAction extends AbstractController
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly EntityManagerInterface $entityManager

    )
    {
    }


    public function __invoke(Request $request, UserPasswordHasherInterface $passwordHasher) :JsonResponse
    {
        $userData = json_decode($request->getContent(), true);
        $token = $userData['token'];
        $userId = $userData['user'];
        $user = $this->userRepository->findOneBy(["id" => $userId, "token" => $token]);
        if ($user) {
            if ($userData['password']) {
                $plaintextPassword = $userData['password'];
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
            }
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
