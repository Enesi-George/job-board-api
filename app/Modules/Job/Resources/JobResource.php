<?php

namespace App\Modules\Job\Resources;

use App\Modules\User\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'company' => $this->company,
            'company_logo' => $this->company_logo,
            'location' => $this->location,
            'category' => $this->category,
            'salary' => $this->salary,
            'qualifications' => $this->qualifications,
            'descriptions' => $this->descriptions,
            'benefit' => $this->benefit,
            'type' => $this->type,
            'work_condition' => $this->work_condition,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
