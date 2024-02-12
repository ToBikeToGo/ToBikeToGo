<?php

namespace App\Controller;

use App\Entity\Vacation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class VacationController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/vacations/user/{id}', name: 'vacation_user', methods: ['GET'])]
    public function __invoke(int $id): Response
    {
        $vacations = $this->entityManager->getRepository(Vacation::class)->findBy(['user' => $id]);

        return $this->json($vacations);
    }
}
