<?php

namespace App\Modules\Job\Dtos;

use App\Dtos\BaseDto;
use Illuminate\Http\UploadedFile;

readonly class ApplicationDto extends BaseDto
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $phone_number,
        public readonly string $location,
        public readonly ?UploadedFile $document = null,
    ) {}
}
