<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateMediaRequest extends Request
{
    protected const KB_IN_MB = 1024 * 1024;

    public function rules(): array
    {
        return [
            'file' => 'required|file',
            'is_public' => 'boolean',
            'company_id' => 'int|exists:companies,id'
        ];
    }

    public function validateResolved(): void
    {
        if (!$this->user()->company_id && !$this->has('company_id')) {
            throw new UnprocessableEntityHttpException('Cannot create media without a company_id');
        }

        parent::validateResolved();

        $this->validateMedia();
    }

    protected function validateMedia(): void
    {
        $imageExtensions = config('defaults.permitted_image_extensions');
        $videoExtensions = config('defaults.permitted_video_extensions');

        list($type, $extension) = explode('/', $this->file->getMimeType());

        $currentSize = $this->file->getSize();

        if (!in_array($extension, array_merge($imageExtensions, $videoExtensions))) {
            throw new UnprocessableEntityHttpException("Media with type {$extension} can not been uploaded.");
        }

        $maxSize = $this->getMaxSize($extension, $imageExtensions);

        if ($currentSize > $maxSize) {
            throw new UnprocessableEntityHttpException("Maximum media size - {$maxSize}.");
        }
    }

    protected function getMaxSize($extension, $imageExtensions): int
    {
        $maxImageSize = self::KB_IN_MB * 10;
        $maxVideoSize = self::KB_IN_MB * 100;

        return (in_array($extension, $imageExtensions) ? $maxImageSize : $maxVideoSize);
    }
}
