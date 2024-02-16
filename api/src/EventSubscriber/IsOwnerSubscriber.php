<?php

namespace App\EventSubscriber;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IsOwnerSubscriber implements EventSubscriberInterface
{

    public function __construct(private TokenStorageInterface $tokenStorage)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {

        /*$loggedInUser = $this->tokenStorage->getToken()->getUser();
        $roles = $loggedInUser->getRoles();

        if (in_array('ROLE_ADMIN', $roles) || in_array('ROLE_PROVIDER', $roles)) {
            return;
        }

        $requestUri = $event->getRequest()->getRequestUri();

        if (str_contains($requestUri, '/api/users/')) {
            $requestUser = $event->getRequest()->get('data');
            if ($loggedInUser !== $requestUser) {
                throw new CustomUserMessageAuthenticationException('You are not the owner of this resource');
            }
        }*/
    }

    public static function getSubscribedEvents(): array
    {
        return [
            //KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
