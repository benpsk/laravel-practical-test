<?php

namespace App\Http\Resources;

use App\Models\SurveyForm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var SurveyForm $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_no' => $this->phone_no,
            'gender' => $this->gender,
            'dob' => $this->dob ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
