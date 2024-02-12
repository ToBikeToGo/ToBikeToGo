<?php

namespace App\Serializer;

use App\Entity\Shop;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ShopNormalizer implements NormalizerInterface
{
    private const ALREADY_CALLED = 'SHOP_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private NormalizerInterface $normalizer
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

        $object->setAddress($object->getStreet() . ', ' . $object->getZipCode() . ' ' . $object->getCity());

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Shop;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Shop::class => true,
        ];
    }
}
