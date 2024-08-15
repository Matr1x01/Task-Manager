<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\DB;

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

    public function createTask(string $title, string $description, TaskStatus $taskStatus, Project $project): Task
    {
        return Task::query()->create([
            'title' => $title,
            'description' => $description,
            'project_id' => $project->id,
            'status' => 1,
            'task_status' => $taskStatus->value,
        ]);
    }

    public function updateTask(Task $task, string $title, string $description, TaskStatus $taskStatus): bool
    {
        return $task->update([
            'title' => $title,
            'description' => $description,
            'task_status' => $taskStatus->value,
        ]);
    }

    public function deleteTask(Task $task): bool
    {
        return $task->delete();
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
        return SubTask::query()->where('task_id', $task_id)->get();
    }

    public function getSubTaskById(string $project_id, string $task_id, string $sub_task_id): SubTask|null
    {
        return SubTask::query()->where('task_id', $task_id)->where('id', $sub_task_id)->first();
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

    public function updateSubTask(SubTask $subTask, string $title): bool
    {
        return $subTask->update([
            'title' => $title,
        ]);
    }

    public function deleteSubTask(SubTask $subTask): bool
    {
        return $subTask->delete();
    }

    public function createTasksFromCsv(array $tasks, string $project_id)
    {
        $tasks = array_map(function ($task) use ($project_id) {
            return [
                'title' => $task['title'],
                'description' => $task['description'],
                'task_status' => $task['task_status'],
                'project_id' => $project_id,
                'status' => 1,
                'id' => 0
            ];
        }, $tasks);

        try {
            DB::transaction(function () use ($tasks) {
                Task::query()->upsert(
                    $tasks,
                    ['id'],
                    ['title', 'description', 'task_status', 'project_id', 'status']
                );
            });
            return false;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
