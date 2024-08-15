<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\SubTaskCreateRequest;
use App\Http\Requests\SubTaskEditRequest;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskEditRequest;
use App\Http\Requests\TaskUploadRequest;
use App\Services\TaskService;

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
        $task = $request->string('task_status', TaskStatus::Pending->value);
        return $this->taskService->createTask(
            $request->string('title'),
            $request->string('description'),
            TaskStatus::tryFrom($task),
            $project_id
        );
    }

    public function editTask(TaskEditRequest $request, string $project_id, string $task_id)
    {
        return $this->taskService->updateTask(
            $request->string('title'),
            $request->string('description'),
            TaskStatus::tryFrom($request->string('task_status')),
            $project_id,
            $task_id
        );
    }

    public function deleteTask(string $project_id, string $task_id)
    {
        return $this->taskService->deleteTask($project_id, $task_id);
    }


    public function uploadTasks(TaskUploadRequest $request, string $project_id)
    {
        return $this->taskService->processTaskFile($request->file('file'), $project_id);
    }

    public function getSubtasks(string $project_id, string $task_id)
    {
        return $this->taskService->getSubtasks($project_id, $task_id);
    }

    public function getSubtaskById(string $project_id, string $task_id, string $subtask_id)
    {
        return $this->taskService->getSubtaskById($project_id, $task_id, $subtask_id);
    }

    public function createSubtask(SubTaskCreateRequest $request, string $project_id, string $task_id)
    {
        return $this->taskService->createSubtask(
            $request->string('title'),
            $project_id,
            $task_id
        );
    }

    public function editSubtask(SubTaskEditRequest $request, string $project_id, string $task_id, string $subtask_id)
    {
        return $this->taskService->updateSubtask(
            $request->string('title'),
            $project_id,
            $task_id,
            $subtask_id
        );
    }

    public function deleteSubtask(string $project_id, string $task_id, string $subtask_id)
    {
        return $this->taskService->deleteSubtask($project_id, $task_id, $subtask_id);
    }
}
