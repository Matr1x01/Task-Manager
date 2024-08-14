<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{
    public function getUserProjects(string $user_id): Collection
    {
        return Project::query()
            ->where('user_id',$user_id)
            ->get();
    }

    public function getUserProjectById(string $user_id, string $project_id): Project|null
    {
        return Project::query()
            ->where('id',$project_id)
            ->where('user_id',$user_id)
            ->first();
    }

    public function createProject(string $name, string $description, string $user_id)
    {
        return Project::query()->create([
            'name'=>$name,
            'description'=>$description,
            'user_id'=>$user_id
        ]);
    }

    public function editProject(string $name, string $description, string $user_id, string $project_id)
    {
        return Project::query()
            ->where('id',$project_id)
            ->where('user_id',$user_id)
            ->update([
                'name'=>$name,
                'description'=>$description
            ]);
    }

    public function deleteProject(string $user_id, string $project_id)
    {
        return Project::query()
            ->where('id',$project_id)
            ->where('user_id',$user_id)
            ->delete();
    }

}
