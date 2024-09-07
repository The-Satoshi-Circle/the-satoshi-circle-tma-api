<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Task extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'url' => $this->url,
            'done' => $request->user()->tasks()->where('task_id', $this->id)->exists(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
