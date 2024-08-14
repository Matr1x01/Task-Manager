<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponder;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectEditRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService)
    {
    }

    public function getProjects()
    {
        return $this->projectService->getProjects();
    }

    public function getProjectById(string $project_id)
    {
        return $this->projectService->getProjectById($project_id);
    }

    public function createProject(ProjectCreateRequest $request)
    {
        return $this->projectService->createProject($request->string('name'), $request->string('description',''));
    }

    public function editProject(ProjectEditRequest $request, string $project_id)
    {
        return $this->projectService->editProject($request->string('name'), $request->string('description',''), $project_id);
    }

    public function deleteProject(string $project_id)
    {
        return $this->projectService->deleteProject($project_id);
    }
}
