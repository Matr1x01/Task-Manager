<?php

namespace App\Services;

use App\Helpers\JsonResponder;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskEditRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepository;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    public function __construct(private readonly TaskRepository $taskRepository)
    {
    }

    public function getTasks(string $project_id)
    {
        return JsonResponder::respond(data:['tasks'=>TaskResource::collection($this->taskRepository->getTasks($project_id))]);
    }

    public function getTaskById(string $project_id, string $task_id)
    {
        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if(!$task){
            return JsonResponder::respond(message:'Task not found',code:Response::HTTP_NOT_FOUND);
        }
    }

    public function createTask(string $title, string $description, TaskStatus $taskStatus, string $project_id)
    {
        $task = $this->taskRepository->createTask($title, $description, $taskStatus, $project_id);
        return JsonResponder::respond(message: 'Task created successfully', data: ['task'=>new TaskResource($task)]);
    }

    public function updateTask(string $title, string $description, TaskStatus $taskStatus, string $project_id, string $task_id)
    {
        $task = $this->taskRepository->updateTask($title, $description, $taskStatus, $project_id, $task_id);

        return JsonResponder::respond(message: 'Task updated successfully', data: ['task'=>new TaskResource($task)]);
    }

    public function deleteTask(string $project_id, string $task_id)
    {
        $this->taskRepository->deleteTask($project_id, $task_id);
        return JsonResponder::respond(message: 'Task deleted successfully');
    }

}
