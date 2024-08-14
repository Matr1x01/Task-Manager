<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function getTasks(string $project_id)
    {
        return $this->taskService->getTasks($project_id);
    }

    public function getTaskById(string $project_id, string $task_id)
    {
        return $this->taskService->getTaskById($project_id, $task_id);
    }

    public function createTask(TaskCreateRequest $request, string $project_id)
    {
        return $this->taskService->createTask($request, $project_id);
    }


}
