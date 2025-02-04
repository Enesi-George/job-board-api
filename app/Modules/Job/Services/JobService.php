<?php

namespace App\Modules\Job\Services;

use App\Models\Application;
use App\Models\Job;
use App\Modules\Job\Dtos\ApplicationDto;
use App\Modules\Job\Dtos\StoreJobDto;
use App\Modules\Job\Dtos\UpdateJobDto;
use App\Modules\Job\Resources\ApplicationResource;
use App\Modules\Job\Resources\JobResource;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JobService
{
    use ApiResponses;

    public function create(StoreJobDto $dto): JsonResponse
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            DB::beginTransaction();

            $jobData = (array) $dto;

            if (isset($dto->company_logo)) {
                $jobData['company_logo'] = $this->storeCompanyLogo($dto->company_logo);
            }
            $jobData['user_id'] = $user->id;

            $job = Job::create($jobData);

            DB::commit();

            return $this->successApiResponse(
                "Job Successfully Created!",
                new JobResource($job),
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return $this->errorApiResponse(
                "An error occurred while creating job",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getAll(int $perPage = 15): JsonResponse
    {
        $cacheKey = "jobs_paginated";
        $jobs = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage) {
            return Job::latest()->paginate($perPage);
        });

        $result = JobResource::collection($jobs);
        return $this->successApiResponse(
            "Jobs retrieved successfully!",
            $result,
            Response::HTTP_CREATED
        );
    }

    public function getById(Job $job): JsonResponse
    {
        $job = Job::findOrFail($job->id);

        $result = new JobResource($job);

        return $this->successApiResponse(
            "Job retrieved successfully!",
            $result,
            Response::HTTP_CREATED
        );
    }

    public function getMyJobs(int $perPage = 15): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $cacheKey = "user_{$user->id}_jobs";

        $jobs = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user, $perPage) {
            return Job::where('user_id', $user->id)
                ->latest()
                ->paginate($perPage);
        });

        return $this->successApiResponse(
            "My jobs retrieved successfully!",
            JobResource::collection($jobs),
            Response::HTTP_OK
        );
    }

    public function search(Request $request, $query)
    {
        if ($searchTerm = $request->get('q')) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . addslashes($searchTerm) . '%')
                    ->orWhere('descriptions', 'like', '%' . addslashes($searchTerm) . '%')
                    ->orWhere('company', 'like', '%' . addslashes($searchTerm) . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $results = $query->latest()->paginate($perPage);

        return $this->successApiResponse(
            "Jobs retrieved successfully",
            JobResource::collection($results),
            Response::HTTP_OK
        );
    }

    public function update(UpdateJobDto $dto, Job $job): JsonResponse
    {
        try {
            DB::beginTransaction();

            $jobData = (array) $dto;
            $user = Auth::user();

            // Check if job exists and belongs to the user
            $checkJob = Job::where('id', $job->id)
                ->where('user_id', $user->id)
                ->first();

            if (!$checkJob) {
                return $this->notFoundApiResponse(
                    "Job not found or you don't have permission to update it"
                );
            }

            if (isset($dto->company_logo)) {
                $jobData['company_logo'] = $this->storeCompanyLogo($dto->company_logo, $job->company_logo);
            }

            // Filter out null values
            $filteredData = array_filter($jobData, function ($value) {
                return $value !== null;
            }, ARRAY_FILTER_USE_BOTH);

            $job->update($filteredData);

            DB::commit();

            return $this->successApiResponse(
                "Job updated successfully!",
                new JobResource($job),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return $this->errorApiResponse(
                "An error occurred while updating the job.",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function delete(Job $job): JsonResponse
    {
        if ($job->company_logo) {
            Storage::delete(str_replace('/storage/', 'public/', $job->company_logo));
        }
        $job->delete();

        return $this->successNoDataApiResponse("Job deleted successfully!", Response::HTTP_NO_CONTENT);

    }

    public function application(ApplicationDto $dto, Job $job): JsonResponse
    {
        try {
        DB::beginTransaction();

        $applicationData = (array) $dto;
        if (isset($dto->document)) {
            $path = $dto->document->store('public/applicant_doc');
            $applicationData['document'] = Storage::url(str_replace('public/', '', $path));
        }
        $applicationData['job_id']= $job->id;

        Application::create($applicationData);

        DB::commit();
        return $this->successNoDataApiResponse(
            "Application successfully submitted!",
            Response::HTTP_CREATED
        );
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e);
            return $this->errorApiResponse(
                "Failed to submit application",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getApplications(Job $job, int $perPage = 15): JsonResponse
    {
        $cacheKey = "job_{$job->id}_applications";
        $applications = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($job, $perPage) {
            return Application::where('job_id', $job->id)
                ->latest()
                ->paginate($perPage);
        });
        return $this->successApiResponse(
            "Applications retrieved successully!",
            ApplicationResource::collection($applications),
            Response::HTTP_OK
        );
    }

    private function storeCompanyLogo($logo, ?string $existingLogo = null): string
    {

        // Delete the old logo if exists
        if ($existingLogo) {
            Storage::delete(str_replace('/storage/', 'public/', $existingLogo));
        }

        // Store new logo
        $path = $logo->store('public/companies_logo');

        return Storage::url(str_replace('public/', '', $path));
    }
}
