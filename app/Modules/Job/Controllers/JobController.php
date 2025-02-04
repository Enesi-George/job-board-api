<?php

namespace App\Modules\Job\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Modules\Job\Dtos\ApplicationDto;
use App\Modules\Job\Dtos\StoreJobDto;
use App\Modules\Job\Dtos\UpdateJobDto;
use App\Modules\Job\Requests\ApplicationRequest;
use App\Modules\Job\Requests\StoreJobRequest;
use App\Modules\Job\Requests\UpdateJobRequest;
use App\Modules\Job\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{

    public function __construct(private readonly JobService $jobService) {}

    public function store(StoreJobRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        return $this->jobService->create(StoreJobDto::fromArray($validatedData));
    }

    public function index(): JsonResponse
    {
        return $this->jobService->getAll();
    }

    public function show(Job $job_id): JsonResponse
    {
        return $this->jobService->getById($job_id);
    }

    public function getMyJobs(): JsonResponse
    {
        return $this->jobService->getMyJobs();
    }

    public function searchJobs(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['nullable', 'string', 'min:2', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1']
        ]);

        $query = Job::query();

        return $this->jobService->search($request, $query);
    }

    public function update(UpdateJobRequest $request, Job $job_id): JsonResponse
    {
        $validatedData = $request->validated();

        return $this->jobService->update(UpdateJobDto::fromArray($validatedData), $job_id);
    }

    public function destroy(Job $job_id): JsonResponse
    {
        return $this->jobService->delete($job_id);
    }

    public function application(ApplicationRequest $request, Job $job_id): JsonResponse
    {
        $validatedData = $request->validated();

        return $this->jobService->application(ApplicationDto::fromArray($validatedData), $job_id);
    }

    public function getAllApplications(Job $job_id): JsonResponse
    {
        return $this->jobService->getApplications($job_id);
    }
}
