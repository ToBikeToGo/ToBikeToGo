<?php

namespace App\Serializer;

use App\Entity\Shop;
use App\Entity\Auth\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Constants\Globals as Roles;

final class UserNormalizer implements NormalizerInterface
{
    private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private StorageInterface $storage,
        private NormalizerInterface $normalizer,
        private TokenStorageInterface $tokenStorage
    )
    {
    }

    public function normalize(
        $object,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        $user = $this->tokenStorage->getToken()->getUser();
        $roles = $this->tokenStorage->getToken()->getRoleNames();

        if (
            $user === $object ||
            in_array(Roles::ROLE_PROVIDER, $roles) ||
            in_array(Roles::ROLE_ADMIN, $roles) ||
            $context['request_uri'] != '/api/users/' . $object->getId()
        ) {
            return $this->normalizer->normalize($object, $format, $context);
        }

        throw new CustomUserMessageAuthenticationException('You are not allowed to access this page');
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }
}
