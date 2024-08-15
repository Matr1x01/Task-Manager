<?php

namespace App\Services;

use App\Helpers\JsonResponder;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectTaskReportResource;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectService
{
    public function __construct(private readonly ProjectRepository $projectRepository)
    {
    }

    public function getProjects(): JsonResponse
    {
        $projects = $this->projectRepository->getUserProjects(auth()->user()->id);
        return JsonResponder::respond(data: [
            'projects'=>ProjectResource::collection($projects)
        ]);
    }

    public function getProjectById(string $project_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id,$project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        return JsonResponder::respond(data: [
            'project'=>new ProjectResource($project)
        ]);
    }

    public function createProject(string $name, string $description): JsonResponse
    {
        $project = $this->projectRepository->createProject($name,$description,auth()->user()->id);

        if (!$project) {
            return JsonResponder::respond(message: 'Failed to create project', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(data: [
            'project'=>new ProjectResource($project)
        ]);
    }

    public function editProject(string $name, string $description, string $project_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id,$project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $response = $this->projectRepository->editProject($project,$name,$description);

        if (!$response) {
            return JsonResponder::respond(message: 'Failed to edit project', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(data: [
            'project'=>new ProjectResource($project)
        ]);
    }

    public function deleteProject(string $project_id): JsonResponse
    {
        $project = $this->projectRepository->getUserProjectById(auth()->user()->id,$project_id);

        if (!$project) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        $response = $this->projectRepository->deleteProject($project);

        if (!$response) {
            return JsonResponder::respond(message: 'Failed to delete project', code: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return JsonResponder::respond(message: 'Project deleted successfully');
    }

    public function getProjectReport(string $project_id): JsonResponse
    {
        $report = $this->projectRepository->getProjectReport(auth()->user()->id, $project_id);

        if (!$report) {
            return JsonResponder::respond(message: 'Project not found', code: Response::HTTP_NOT_FOUND);
        }

        return JsonResponder::respond(data: [
            'report'=>new ProjectTaskReportResource($report)
        ]);
    }
}
