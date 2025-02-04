<?php

namespace App\Modules\Job\Dtos;

use App\Dtos\BaseDto;
use Illuminate\Http\UploadedFile;

readonly class StoreJobDto extends BaseDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $company,
        public readonly string $location,
        public readonly string $category,
        public readonly string $salary,
        public readonly array $qualifications,
        public readonly array $descriptions,
        public readonly string $type,
        public readonly ?string $benefit = null,
        public readonly ?UploadedFile $company_logo = null,
        public readonly ?string $work_condition = null,
    ) {}
}
