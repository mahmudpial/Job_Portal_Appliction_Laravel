<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationConfirmation;
use App\Mail\JobApplicationSubmitted;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function create(Job $job): View
    {
        // Only employees can apply
        if (! auth()->user()->isEmployee()) {
            abort(403);
        }

        // Check if user already applied
        $existingApplication = JobApplication::where('job_id', $job->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingApplication) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'You have already applied for this job.');
        }

        return view('job-applications.create', compact('job'));
    }

    public function store(Request $request, Job $job): RedirectResponse
    {
        // Only employees can apply
        if (! auth()->user()->isEmployee()) {
            abort(403);
        }

        // Check if job is still active
        if ($job->status !== 'active') {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'This job is no longer accepting applications.');
        }

        // Check if user already applied
        $existingApplication = JobApplication::where('job_id', $job->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingApplication) {
            return redirect()->route('jobs.show', $job)
                ->with('error', 'You have already applied for this job.');
        }

        $validated = $request->validate([
            'cover_letter' => 'required|string|min:50|max:2000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        $application = JobApplication::create([
            'job_id' => $job->id,
            'user_id' => auth()->id(),
            'cover_letter' => $validated['cover_letter'],
            'resume_path' => $resumePath,
            'applied_at' => now(),
        ]);

        // Send email notification to the employer
        $employer = $job->company->user;
        if ($employer && $employer->email) {
            Mail::to($employer->email)->send(new JobApplicationSubmitted($application));
        }

        // Send confirmation email to the applicant
        Mail::to($application->user->email)->send(new ApplicationConfirmation($application));

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Your application has been submitted successfully!');
    }

    public function index(): View
    {
        $user = auth()->user();

        if ($user->isEmployer()) {
            $applications = JobApplication::with(['job.company', 'user'])
                ->whereHas('job.company', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest('applied_at')
                ->get();

            return view('job-applications.index', [
                'applications' => $applications,
                'pageTitle' => 'Applications to My Jobs',
                'canManageStatus' => true,
            ]);
        }

        if ($user->isAdmin()) {
            $applications = JobApplication::with(['job.company', 'user'])
                ->latest('applied_at')
                ->get();

            return view('job-applications.index', [
                'applications' => $applications,
                'pageTitle' => 'All Job Applications',
                'canManageStatus' => true,
            ]);
        }

        $applications = $user->jobApplications()
            ->with('job.company')
            ->latest('applied_at')
            ->get();

        return view('job-applications.index', [
            'applications' => $applications,
            'pageTitle' => 'My Job Applications',
            'canManageStatus' => false,
        ]);
    }

    public function show(JobApplication $application): View
    {
        $application->load([
            'job.company',
            'user.employeeProfile.educations',
            'user.employeeProfile.workExperiences',
        ]);

        $currentUser = auth()->user();
        $isOwnerEmployee = $currentUser->id === $application->user_id;
        $isEmployerOwner = $currentUser->isEmployer() && $application->job->company->user_id === $currentUser->id;
        $isAdmin = $currentUser->isAdmin();

        if (! $isOwnerEmployee && ! $isEmployerOwner && ! $isAdmin) {
            abort(403);
        }

        $canManageStatus = $isEmployerOwner || $isAdmin;

        return view('job-applications.show', compact('application', 'canManageStatus'));
    }

    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        $application->load('job.company');

        $currentUser = auth()->user();
        $isEmployerOwner = $currentUser->isEmployer() && $application->job->company->user_id === $currentUser->id;
        $isAdmin = $currentUser->isAdmin();

        if (! $isEmployerOwner && ! $isAdmin) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
            'status_reason' => 'nullable|string|max:2000|required_if:status,rejected',
        ]);

        $statusReason = null;
        if ($validated['status'] === 'rejected' && ! empty($validated['status_reason'])) {
            $statusReason = trim($validated['status_reason']);
        }

        $application->update([
            'status' => $validated['status'],
            'status_reason' => $statusReason,
        ]);

        return redirect()
            ->route('job-applications.show', $application)
            ->with('success', 'Application status updated successfully.');
    }
}
