<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\EmployeeProfile;
use App\Models\Job;
use App\Policies\CompanyPolicy;
use App\Policies\EmployeeProfilePolicy;
use App\Policies\JobPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Job::class => JobPolicy::class,
        Company::class => CompanyPolicy::class,
        EmployeeProfile::class => EmployeeProfilePolicy::class,
    ];

    public function boot(): void
    {
        Gate::define('isAdmin', function ($user) {
            return $user->user_type === 'admin';
        });
    }
}
