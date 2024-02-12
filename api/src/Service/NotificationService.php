<?php

namespace App\Service;

use App\Entity\Auth\User;
use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Enum\NotificationTypeEnum;
use App\Repository\NotificationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PharIo\Manifest\Email;
use phpDocumentor\Reflection\Types\This;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;
use function Symfony\Component\Clock\now;

class NotificationService
{

    public function __construct(
        private readonly NotificationTypeRepository $notificationTypeRepository,
        private readonly EntityManagerInterface     $entityManager
    )
    {
    }

    public function sendNotification(
        Emailing $emailing,
        User $sender,
        string $slug,
        array $affiliates,
        ?User $action = null,
        ?array $otherInfo = null,
    ): void
    {
        switch ($slug) {
            case NotificationTypeEnum::EMPLOYEE_ADDED:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a ajouté ' . $action->getFirstname() . $action->getLastname() . ' à votre franchise';
                $textForHimself = '<p><b> Vous venez d’ajouté ' . $action->getFirstname() . $action->getLastname() . ' à votre franchise';
            break;
            case NotificationTypeEnum::LEAVE_REQUEST:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a fait une demande de congé du ' . $otherInfo['leaveStart'] . ' au ' . $otherInfo['leaveEnd'] . '</p>';
                $textForHimself = '<p>Votre demande de congé a été enregistré du ' . $otherInfo['leaveStart'] . ' au ' . $otherInfo['leaveEnd'] . '</p>';
            break;
            case NotificationTypeEnum::LEAVE_CANCELLED:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a annulé votre demande de congé</p>';
                $textForHimself = '<p>Vous avez refusé la demande de congé de' . $action->getFirstname() . $action->getLastname() . '</p>';
            break;
            case NotificationTypeEnum::LEAVE_ACCEPTED:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a accepté votre demande de congé</p>';
                $textForHimself = '<p>Vous avez accepté la demande de congé de' . $action->getFirstname() . $action->getLastname() . '</p>';
            break;
            case NotificationTypeEnum::LEAVE_MODIFIED:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a modifié ça demande de congé du ' . $otherInfo['leaveStart'] . ' au ' . $otherInfo['leaveEnd'] . '</p>';
                $textForHimself = '<p>Vous avez modifié votre demande de congé du ' . $otherInfo['leaveStart'] . ' au ' . $otherInfo['leaveEnd'] . '</p>';
            break;
            case NotificationTypeEnum::ACCOUNT_CREATION_REQUEST:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a fait une demande de création d’entreprise</p>';
                $textForHimself = '<p>Votre demande de création de franchise à bien été prise en compte</p>';
            break;
            case NotificationTypeEnum::RESERVATION_CREATED:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a fait une demande de reservation du ' . $otherInfo['startDate'] . ' au ' . $otherInfo['endDate'] . '</p>';
                $textForHimself = '<p>Votre demande de reservation du ' . $otherInfo['startDate'] . ' au ' . $otherInfo['endDate'] . ' à bien été prise en compte</p>';
            break;
            case NotificationTypeEnum::RESERVATION_CANCELLED:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a annulé ça reservation</p>';
                $textForHimself = '<p>Votre demande d’annulation de reservation à bien été prise en compte</p>';
            break;
            case NotificationTypeEnum::RESERVATION_MODIFIED:
                $text = '<p><b>' . $sender->getFirstname() . ' ' . $sender->getLastname() . '</b> a modifié ça reservation du ' . $otherInfo['startDate'] . ' au ' . $otherInfo['endDate'] . '</p>';
                $textForHimself = '<p>La modification de votre reservation du ' . $otherInfo['startDate'] . ' au ' . $otherInfo['endDate'] . ' à bien été prise en compte</p>';
            break;
        }
        $notificationType = $this->notificationTypeRepository->findOneBy(['slug' => $slug]);
        if (!empty($textForHimself) and $notificationType){
            $this->saveNotification(
                sender: $sender,
                notificationType: $notificationType,
                text: $textForHimself
            );
        }
        if (!empty($text) and $notificationType) {
            foreach ($affiliates as $affiliate) {
                if ($affiliate->getId() === $sender->getId()){
                    continue;
                }
                $this->saveNotification(
                    sender: $affiliate,
                    notificationType: $notificationType,
                    text: $text
                );
            }
        }
        if (!empty($text)){
            $emailing->sendNotificationEmailing(
                emailUsers: $affiliates,
                idTemplate: 2,
                text: strip_tags($text)
            );
        }
    }
    public function saveNotification(
        User $sender,
        NotificationType $notificationType,
        string $text
    ): void
    {
        $notification = new Notification();
        $notification->setNotificationType($notificationType);
        $notification->setSender($sender);
        $notification->setIsAlreadySeen(false);
        $notification->setText($text);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
