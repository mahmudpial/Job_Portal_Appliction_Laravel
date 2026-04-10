<?php

namespace App\Policies;

use App\Models\EmployeeProfile;
use App\Models\User;

class EmployeeProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isEmployee();
    }

    public function view(User $user, EmployeeProfile $employeeProfile): bool
    {
        return $user->isEmployee() && $employeeProfile->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isEmployee();
    }

    public function update(User $user, EmployeeProfile $employeeProfile): bool
    {
        return $user->isEmployee() && $employeeProfile->user_id === $user->id;
    }

    public function delete(User $user, EmployeeProfile $employeeProfile): bool
    {
        return $user->isEmployee() && $employeeProfile->user_id === $user->id;
    }

    public function restore(User $user, EmployeeProfile $employeeProfile): bool
    {
        return $user->isEmployee() && $employeeProfile->user_id === $user->id;
    }

    public function forceDelete(User $user, EmployeeProfile $employeeProfile): bool
    {
        return $user->isEmployee() && $employeeProfile->user_id === $user->id;
    }
}
