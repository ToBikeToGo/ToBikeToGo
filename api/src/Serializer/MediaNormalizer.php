<?php

namespace App\Serializer;

use App\Entity\Media;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class MediaNormalizer implements NormalizerInterface
{
    private const ALREADY_CALLED = 'MEDIA_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private StorageInterface $storage,
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

        $object->setContentUrl($this->storage->resolveUri($object, 'file'));

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Media;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Media::class => true,
        ];
    }
}
