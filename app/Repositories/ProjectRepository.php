<?php

namespace App\Repositories;

use App\Enums\TaskStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{
    public function getUserProjects(string $user_id): Collection
    {
        return Project::query()
            ->where('user_id', $user_id)
            ->select('id', 'name', 'description')
            ->get();
    }

    public function getUserProjectById(string $user_id, string $project_id): Project|null
    {
        return Project::query()
            ->where('id', $project_id)
            ->where('user_id', $user_id)
            ->select('id', 'name', 'description')
            ->first();
    }

    public function createProject(string $name, string $description, string $user_id)
    {
        return Project::query()->create([
            'name' => $name,
            'description' => $description,
            'user_id' => $user_id
        ]);
    }

    public function editProject(Project $project, string $name, string $description)
    {
        return $project->update([
            'name' => $name,
            'description' => $description
        ]);
    }

    public function deleteProject(Project $project,): ?bool
    {
        return $project->delete();
    }

    public function getProjectReport(string $user_id, string $project_id): Project|null
    {
        $project = Project::query()
            ->where('status', 1)
            ->where('user_id', $user_id)
            ->where('id', $project_id);

        foreach (TaskStatus::cases() as $status) {
            $project->withCount(['tasks as ' . $status->name . '_Tasks' => function ($query) use ($status) {
                $query->where('tasks.status', 1);
                $query->where('tasks.task_status', $status->value);
            }]);
        }

        return $project
            ->withCount(['tasks' => function ($query) {
                $query->where('tasks.status', 1);
            }])
            ->withCount(['subTasks' => function ($query) {
                $query->where('sub_tasks.status', 1);
            }])
            ->first();
    }

}
