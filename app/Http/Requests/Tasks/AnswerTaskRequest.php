<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\Request;
use App\Models\Task;
use App\Services\MediaService;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AnswerTaskRequest extends Request
{
    public function authorize(): bool
    {
        return $this->user()->can('answer', $this->entity);
    }

    public function rules(): array
    {
        return [
            'answer' => 'nullable|array',
            'answer.*' => 'string'
        ];
    }

    public function validateResolved(): void
    {
        $this->checkEntityExists('Task', $this->route('id'));

        parent::validateResolved();

        $this->checkAnswer($this->input('answer'));
    }

    private function checkAnswer(array $answer): void
    {
        if ($this->entity->response_type === Task::TYPE_MEDIA) {
            foreach ($answer as $mediaId) {
                if (!is_numeric($mediaId)) {
                    throw new UnprocessableEntityHttpException('Answer should contain a valid media_ids');
                }
            }

            $media = app(MediaService::class)->getByList($answer);

            if (count($media) !== count(array_unique($answer))) {
                throw new BadRequestHttpException('One of the provided media does not exist');
            }
        }

        if (isset($this->entity->expected_response) && !array_equals($answer, $this->entity->expected_response)) {
            throw new BadRequestHttpException('Task failed');
        }
    }
}
