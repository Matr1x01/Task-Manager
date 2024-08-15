<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Helpers\JsonResponder;
use App\Http\Resources\SubTaskResource;
use App\Http\Resources\TaskResource;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly ProjectRepository $projectRepository
    )
    {
    }


    public function getTasks(string $project_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        return JsonResponder::respond(data: ['tasks' => TaskResource::collection($this->taskRepository->getTasks($project_id))]);
    }

    public function getTaskById(string $project_id, string $task_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        return JsonResponder::respond(data: ['task' => new TaskResource($task)]);
    }

    public function createTask(string $title, string $description, TaskStatus $taskStatus, string $project_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->createTask($title, $description, $taskStatus, $project);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not created', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(message: 'Task created successfully', data: ['task' => new TaskResource($task)]);
    }

    public function updateTask(string $title, string $description, TaskStatus $taskStatus, string $project_id, string $task_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        $response = $this->taskRepository->updateTask($task, $title, $description, $taskStatus);

        if (!$response) {
            return JsonResponder::respond(message: 'Task not updated', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(message: 'Task updated successfully', data: ['task' => new TaskResource($task)]);
    }

    public function deleteTask(string $project_id, string $task_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        $response = $this->taskRepository->deleteTask($task);

        if (!$response) {
            return JsonResponder::respond(message: 'Task not deleted', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(message: 'Task deleted successfully');
    }

    public function processTaskFile($file, string $project_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $filePath = $file->getRealPath();
        $fileHandle = fopen($filePath, 'r');
        $header = fgetcsv($fileHandle);

        $tasks = [];
        $insertCount = 0;
        while ($row = fgetcsv($fileHandle)) {
            $rowData = array_combine($header, $row);
            if (empty($rowData['title'])) {
                continue;
            }

            $insertCount++;

            $tasks[] = [
                'title' => $rowData['title'],
                'description' => $rowData['description'] ?? null,
                'task_status' => TaskStatus::tryFrom($rowData['task_status'])->value ?? TaskStatus::Pending->value,
            ];
        }

        fclose($fileHandle);

        $error = $this->taskRepository->createTasksFromCsv($tasks, $project_id);

        if ($error) {
            return JsonResponder::respond(message: $error, code: Response::HTTP_BAD_REQUEST);
        }

        return JsonResponder::respond(message: 'Tasks created successfully. Total tasks created: ' . $insertCount);
    }

    public function getSubtasks(string $project_id, string $task_id): JsonResponse
    {

        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        return JsonResponder::respond(data: ['subtasks' => SubTaskResource::collection($this->taskRepository->getSubtasks($project_id, $task_id))]);
    }

    public function getSubtaskById(string $project_id, string $task_id, string $subtask_id): JsonResponse
    {

        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        $subtask = $this->taskRepository->getSubtaskById($project_id, $task_id, $subtask_id);

        if (!$subtask) {
            return JsonResponder::respond(message: 'Subtask not found', code: Response::HTTP_NOT_FOUND);
        }

        return JsonResponder::respond(data: ['subtask' => new SubTaskResource($subtask)]);
    }

    public function createSubtask(string $title, string $project_id, string $task_id): JsonResponse
    {

        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        $subtask = $this->taskRepository->createSubtask($title, $project_id, $task_id);

        if (!$subtask) {
            return JsonResponder::respond(message: 'Subtask not created', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(message: 'Subtask created successfully', data: ['subtask' => new SubTaskResource($subtask)]);
    }

    public function updateSubtask(string $title, string $project_id, string $task_id, string $subtask_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        $subtask = $this->taskRepository->getSubtaskById($project_id, $task_id, $subtask_id);

        if (!$subtask) {
            return JsonResponder::respond(message: 'Subtask not found', code: Response::HTTP_NOT_FOUND);
        }

        $response = $this->taskRepository->updateSubtask($subtask, $title);

        if (!$response) {
            return JsonResponder::respond(message: 'Subtask not updated', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(message: 'Subtask updated successfully', data: ['subtask' => new SubTaskResource($subtask)]);
    }

    public function deleteSubtask(string $project_id, string $task_id, string $subtask_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id, $project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $task = $this->taskRepository->getTaskById($project_id, $task_id);

        if (!$task) {
            return JsonResponder::respond(message: 'Task not found', code: Response::HTTP_NOT_FOUND);
        }

        $subtask = $this->taskRepository->getSubtaskById($project_id, $task_id, $subtask_id);

        if (!$subtask) {
            return JsonResponder::respond(message: 'Subtask not found', code: Response::HTTP_NOT_FOUND);
        }

        $response = $this->taskRepository->deleteSubtask($subtask);

        if (!$response) {
            return JsonResponder::respond(message: 'Subtask not deleted', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(message: 'Subtask deleted successfully');
    }

}
