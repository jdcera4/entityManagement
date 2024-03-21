<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramsParticipants extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'entity_type' => $this->entity_type,
            'program_id' => $this->program_id,
            'entity_id' => $this->entity_id,
            'program' => [
                'id' => $this->program->id ?? null,
                'title' => $this->program->title ?? null,
                'description' => $this->program->description ?? null
            ]
        ];
    }
}
