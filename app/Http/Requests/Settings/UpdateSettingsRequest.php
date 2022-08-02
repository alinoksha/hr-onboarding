<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\Request;
use App\Models\Company;
use App\Services\CompanyService;
use App\Services\SettingService;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateSettingsRequest extends Request
{
    private ?Company $company;

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->company);
    }

    public function rules(): array
    {
        return [
            '*.id' => 'int',
            '*.data' => 'string|max:255',
            '*.sorting_order' => 'int|min:1'
        ];
    }

    private function checkSettingExists(): void
    {
        $settingsIds = Arr::pluck($this->all(), 'id');
        $settings = app(SettingService::class)->getByList($settingsIds);

        if (count($settings) !== count(array_unique($settingsIds))) {
            throw new BadRequestHttpException('One of the provided settings does not exist');
        }
    }

    public function validateResolved(): void
    {
        $this->checkSettingExists();

        $this->company = app(CompanyService::class)->find($this->user()->company_id);

        parent::validateResolved();
    }
}
