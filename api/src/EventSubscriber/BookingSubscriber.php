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
 readonly class BookingSubscriber implements EventSubscriberInterface
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
        if ($request->isMethod('POST') && $request->getPathInfo() === '/api/bookings') {
            $slug = NotificationTypeEnum::RESERVATION_CREATED;
            $requestData = $request->attributes->get('data');
            $user = $requestData->getUser();
            $bike = $requestData->getBike();

            $affiliates = $bike->getShop()->getFranchise()->getUsers()->getValues();
            $startDate = $requestData->getstartDate();
            $endDate = $requestData->getendDate();
            if (isset($startDate) && isset($endDate)) {
                $otherInfo = ['startDate' => $startDate->format('d/m/Y'), 'endDate' => $endDate->format('d/m/Y')];
            }
            $this->notificationService->sendNotification(
                emailing:  $this->emailing,
                sender:  $user,
                slug: $slug,
                affiliates: $affiliates,
                otherInfo: $otherInfo
            );
        }
        // Assurez-vous que c'est une requête POST et que le chemin correspond à l'endpoint de Vacation
        if ($request->isMethod('PATCH') && preg_match('#^/api/bookings/.*#', $request->getPathInfo())) {
            $slug = NotificationTypeEnum::RESERVATION_MODIFIED;
            $requestData = $request->attributes->get('data');
            $user = $requestData->getUser();
            $bike = $requestData->getBike();
            $affiliates = $bike->getShop()->getFranchise()->getUsers()->getValues();
            $startDate = $requestData->getstartDate();
            $endDate = $requestData->getendDate();
            if (isset($startDate) && isset($endDate)) {
                $otherInfo = ['startDate' => $startDate->format('d/m/Y'), 'endDate' => $endDate->format('d/m/Y')];
            }
            $this->notificationService->sendNotification(
                emailing:  $this->emailing,
                sender:  $user,
                slug: $slug,
                affiliates: $affiliates,
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
