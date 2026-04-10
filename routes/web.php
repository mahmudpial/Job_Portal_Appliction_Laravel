<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        $users = User::count();
        $companies = Company::count();
        $jobs = Job::count();

        return view('dashboard', ['role' => 'admin', 'users' => $users, 'companies' => $companies, 'jobs' => $jobs]);
    } elseif ($user->isEmployer()) {
        $company = $user->company;
        $jobs = $company ? $company->jobs()->latest()->get() : collect([]);

        return view('dashboard', ['role' => 'employer', 'company' => $company, 'jobs' => $jobs]);
    } else {
        $profile = $user->employeeProfile()->with(['educations', 'workExperiences'])->first();
        $availableJobs = Job::where('status', 'active')->latest()->limit(10)->get();

        return view('dashboard', ['role' => 'employee', 'profile' => $profile, 'availableJobs' => $availableJobs]);
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('company', CompanyController::class)->only(['index', 'store', 'update']);
    Route::get('/my-jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::resource('jobs', JobController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/my-profile', [EmployeeProfileController::class, 'index'])->name('profile.employee');
    Route::post('/my-profile', [EmployeeProfileController::class, 'store'])->name('profile.employee.store');
    Route::patch('/my-profile/{profile}', [EmployeeProfileController::class, 'update'])->name('profile.employee.update');

    // Job Applications
    Route::get('/jobs/{job}/apply', [JobApplicationController::class, 'create'])->name('job-applications.create');
    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])->name('job-applications.store');
    Route::get('/my-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
    Route::get('/my-applications/{application}', [JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::patch('/my-applications/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('job-applications.update-status');

    // Admin Routes
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/admin/companies', [AdminController::class, 'companies'])->name('admin.companies');
        Route::get('/admin/jobs', [AdminController::class, 'jobs'])->name('admin.jobs');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::delete('/admin/companies/{company}', [AdminController::class, 'destroyCompany'])->name('admin.companies.destroy');
        Route::delete('/admin/jobs/{job}', [AdminController::class, 'destroyJob'])->name('admin.jobs.destroy');
    });
});

// Public job routes
Route::get('/jobs', [JobController::class, 'publicIndex'])->name('jobs.browse');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

require __DIR__.'/auth.php';
