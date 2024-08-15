<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

class TaskResource extends JsonResource
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
            'description' => $this->description,
            'status' => $this->status,
            'task_status' => TaskStatus::tryFrom($this->task_status)->name,
            'created_at' => date('D, d M Y H:i:s',strtotime($this->created_at)),
            'updated_at' => date('D, d M Y H:i:s',strtotime($this->updated_at)),
        ];
    }
}
