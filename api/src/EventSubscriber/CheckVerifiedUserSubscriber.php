<?php

namespace App\EventSubscriber;

use App\Entity\Auth\User;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
// ... lines 10 - 14
    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => 'onCheckPassport',
        ];
    }

    public function onCheckPassport(CheckPassportEvent $event)
    {
        $passport = $event->getPassport();
        if (!$passport instanceof Passport) {
            throw new \Exception('Unexpected passport type');
        }
        $user = $passport->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Unexpected user type');
        }
        if (!$user->isStatus()) {
            throw new CustomUserMessageAuthenticationException(
                'Please verify your account before logging in. Check your email for the verification link.'
            );
        }
    }
}