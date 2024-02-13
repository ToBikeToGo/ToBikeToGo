<?php

namespace App\EventSubscriber;

use App\Entity\Auth\User;
use App\Enum\NotificationTypeEnum;
use App\Repository\UserRepository;
use App\Service\Emailing;
use App\Service\NotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface as TokenStorage;
 readonly class VacationSubscriber implements EventSubscriberInterface
{


    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly UserRepository      $userRepository,
        private readonly Emailing            $emailing
    )
    {
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $otherInfo = [];

        $request = $event->getRequest();
        if ($request->isMethod('POST') && $request->getPathInfo() === '/api/vacations') {
            $slug = NotificationTypeEnum::LEAVE_REQUEST;
            $requestData = $request->attributes->get('data');
            $user = $requestData->getUser();
            $leaveStart = $requestData->getstartDate();
            $leaveEnd = $requestData->getendDate();
            if (isset($leaveStart) && isset($leaveEnd)) {
                $otherInfo = ['leaveStart' => $leaveStart->format('d/m/Y'), 'leaveEnd' => $leaveEnd->format('d/m/Y')];
            }
            $admins = $this->userRepository->findProvidersInFranchisesOfUser($user->getId());
            $this->notificationService->sendNotification(
                emailing:  $this->emailing,
                sender:  $user,
                slug: $slug,
                affiliates: $admins,
                otherInfo: $otherInfo
            );
        }
        // Assurez-vous que c'est une requête POST et que le chemin correspond à l'endpoint de Vacation
        if ($request->isMethod('PATCH') && preg_match('#^/api/vacations/.*#', $request->getPathInfo())) {
            if (str_contains($request->getPathInfo(), '/validated')) {
                return;
            }
            $slug = NotificationTypeEnum::LEAVE_MODIFIED;
            $requestData = $request->attributes->get('data');
            $user = $requestData->getUser();
            $leaveStart = $requestData->getstartDate();
            $leaveEnd = $requestData->getendDate();
            if (isset($leaveStart) && isset($leaveEnd)) {
                $otherInfo = ['leaveStart' => $leaveStart->format('d/m/Y'), 'leaveEnd' => $leaveEnd->format('d/m/Y')];
            }
            $admins = $this->userRepository->findProvidersInFranchisesOfUser($user->getId());
            $this->notificationService->sendNotification(
                emailing:  $this->emailing,
                sender:  $user,
                slug: $slug,
                affiliates: $admins,
                otherInfo: $otherInfo

            );
        }
    }

    public static function getSubscribedEvents()
    {
        // Définissez l'événement que vous souhaitez écouter (kernel.terminate pour le moment)
        return [
            KernelEvents::TERMINATE => 'onKernelTerminate',
        ];
    }

}
