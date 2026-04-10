<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        $company = $user->company;
        if (! $company) {
            return view('jobs.index', ['jobs' => collect([]), 'company' => null]);
        }

        $jobs = $company->jobs()->latest()->get();

        return view('jobs.index', compact('jobs', 'company'));
    }

    public function publicIndex(Request $request): View
    {
        $query = Job::with('company')->where('status', 'active');

        // Search by title or company name
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by location
        if ($request->has('location') && ! empty($request->location)) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Filter by job type
        if ($request->has('job_type') && ! empty($request->job_type)) {
            $query->where('job_type', $request->job_type);
        }

        // Filter by salary range
        if ($request->has('salary_min') && ! empty($request->salary_min)) {
            $query->where('salary_max', '>=', $request->salary_min);
        }

        if ($request->has('salary_max') && ! empty($request->salary_max)) {
            $query->where('salary_min', '<=', $request->salary_max);
        }

        $jobs = $query->latest()->paginate(12);

        return view('jobs.browse', compact('jobs'));
    }

    public function show(Job $job): View
    {
        // Only show active jobs to public
        if ($job->status !== 'active') {
            abort(404);
        }

        $job->load('company');

        return view('jobs.show', compact('job'));
    }

    public function create(): View
    {
        $company = auth()->user()->company;

        if (! $company) {
            return view('jobs.create', ['company' => null]);
        }

        return view('jobs.create', compact('company'));
    }

    public function store(Request $request): RedirectResponse
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('company.index')->with('error', 'Please create your company first.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'vacancy' => 'required|integer|min:1',
            'deadline' => 'nullable|date|after:today',
        ]);

        $company->jobs()->create($validated);

        return redirect()->route('jobs.index')->with('success', 'Job posted successfully.');
    }

    public function edit(Job $job): View
    {
        $this->authorize('update', $job);

        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $this->authorize('update', $job);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,closed',
            'vacancy' => 'required|integer|min:1',
            'deadline' => 'nullable|date',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $this->authorize('delete', $job);
        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }
}
