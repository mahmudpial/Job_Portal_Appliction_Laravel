<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function users(): View
    {
        $users = User::latest()->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function companies(): View
    {
        $companies = Company::with('user')->latest()->paginate(10);

        return view('admin.companies', compact('companies'));
    }

    public function jobs(): View
    {
        $jobs = Job::with('company')->latest()->paginate(10);

        return view('admin.jobs', compact('jobs'));
    }

    public function settings(): View
    {
        return view('admin.settings');
    }

    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function destroyCompany(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies')->with('success', 'Company deleted successfully.');
    }

    public function destroyJob(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs')->with('success', 'Job deleted successfully.');
    }
}
