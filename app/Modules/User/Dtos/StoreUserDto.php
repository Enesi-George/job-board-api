<?php

namespace App\Modules\User\Dtos;

use App\Dtos\BaseDto;
use Illuminate\Http\UploadedFile;

readonly class StoreUserDto extends BaseDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?UploadedFile $picture = null,
    ) {}
}
