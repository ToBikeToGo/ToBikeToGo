<?php

namespace App\Controller;

use App\Entity\Media;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class MediaUploadAction extends AbstractController
{
    public function __invoke(Request $request): Media
    {
        $uploadedFile = $request->files->get('file');
        $extension = $uploadedFile->getMimeType();
        $mimeTypes = new MimeTypes();
        $mimeType = $mimeTypes->guessMimeType($uploadedFile);

        if ($mimeType !== $extension) {
            throw new BadRequestHttpException('Invalid file type');
        }

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $media = new Media();
        $media->setFile($uploadedFile);
        $media->setExtension($extension);

        return $media;
    }
}
