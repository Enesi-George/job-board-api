<?php

namespace App\Modules\Job\Dtos;

use App\Dtos\BaseDto;
use Illuminate\Http\UploadedFile;

readonly class UpdateJobDto extends BaseDto
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $company = null,
        public readonly ?string $location = null,
        public readonly ?string $category = null,
        public readonly ?string $salary = null,
        public readonly ?string $qualifications = null,
        public readonly ?string $descriptions = null,
        public readonly ?string $type = null,
        public readonly ?string $benefit = null,
        public readonly ?UploadedFile $company_logo = null,
        public readonly ?string $work_condition = null,
    ) {}
}
