<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isEmployer();
    }

    public function view(User $user, Company $company): bool
    {
        return $user->isEmployer() && $company->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isEmployer();
    }

    public function update(User $user, Company $company): bool
    {
        return $user->isEmployer() && $company->user_id === $user->id;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->isEmployer() && $company->user_id === $user->id;
    }

    public function restore(User $user, Company $company): bool
    {
        return $user->isEmployer() && $company->user_id === $user->id;
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return $user->isEmployer() && $company->user_id === $user->id;
    }
}
