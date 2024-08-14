<?php

namespace App\Repositories;

use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use TaskStatus;

class TaskRepository
{
    public function getTasks(string $project_id): Collection
    {
        return Task::query()->where('project_id', $project_id)->get();
    }

    public function getTaskById(string $project_id, string $task_id): Task|null
    {
        return Task::query()->where('project_id', $project_id)->where('id', $task_id)->first();
    }

    public function createTask(string $title, string $description, TaskStatus $taskStatus, string $project_id): Task
    {
        return Task::query()->create([
            'title' => $title,
            'description' => $description,
            'project_id' => $project_id,
            'status' => 1,
            'task_status' => $taskStatus->value,
        ]);
    }

    public function updateTask(string $title, string $description, TaskStatus $taskStatus, string $project_id, string $task_id): Task
    {
        $task = Task::query()->where('project_id', $project_id)->where('id', $task_id)->first();
        $task->title = $title;
        $task->description = $description;
        $task->task_status = $taskStatus->value;
        $task->save();
        return $task;
    }

    public function deleteTask(string $project_id, string $task_id): void
    {
        Task::query()->where('project_id', $project_id)->where('id', $task_id)->delete();
    }

    public function updateTaskStatus(TaskStatus $taskStatus, string $project_id, string $task_id): Task
    {
        $task = Task::query()->where('project_id', $project_id)->where('id', $task_id)->first();
        $task->task_status = $taskStatus->value;
        $task->save();
        return $task;
    }

    public function getSubTasks(string $project_id, string $task_id): Collection
    {
        return SubTask::query()->where('project_id', $project_id)->where('task_id', $task_id)->get();
    }

    public function getSubTaskById(string $project_id, string $task_id, string $sub_task_id): SubTask|null
    {
        return SubTask::query()->where('project_id', $project_id)->where('task_id', $task_id)->where('id', $sub_task_id)->first();
    }

    public function createSubTask(string $title, string $project_id, string $task_id): SubTask
    {
        return SubTask::query()->create([
            'title' => $title,
            'project_id' => $project_id,
            'task_id' => $task_id,
            'status' => 1,
        ]);
    }

    public function updateSubTask(string $title, string $project_id, string $task_id, string $sub_task_id): SubTask
    {
        $subTask = SubTask::query()->where('project_id', $project_id)->where('task_id', $task_id)->where('id', $sub_task_id)->first();
        $subTask->title = $title;
        $subTask->save();
        return $subTask;
    }

    public function deleteSubTask(string $project_id, string $task_id, string $sub_task_id): void
    {
        SubTask::query()->where('project_id', $project_id)->where('task_id', $task_id)->where('id', $sub_task_id)->delete();
    }
}
