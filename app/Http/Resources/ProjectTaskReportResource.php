<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectTaskReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //"id" => 1
        //    "name" => "test 13"
        //    "description" => "test descriptio asdfn"
        //    "user_id" => 1
        //    "status" => 1
        //    "created_at" => "2024-08-15 14:46:33"
        //    "updated_at" => "2024-08-15 16:50:01"
        //    "Pending_Tasks" => 22
        //    "InProgress_Tasks" => 4
        //    "Completed_Tasks" => 12
        //    "Canceled_Tasks" => 0
        //    "tasks_count" => 38
        //    "sub_tasks_count" => 3
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updated_at' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
            'total_tasks' => $this->tasks_count,
            'completed_tasks' => $this->Completed_Tasks,
            'pending_tasks' => $this->Pending_Tasks,
            'in_progress_tasks' => $this->InProgress_Tasks,
            'canceled_tasks' => $this->Canceled_Tasks,
            'completed_percentage' => $this->tasks_count > 0 ? ($this->Completed_Tasks / $this->tasks_count) * 100 : 0
        ];
    }
}
