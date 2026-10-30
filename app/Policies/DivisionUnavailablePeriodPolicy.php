<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Division;
use App\Models\DivisionUnavailablePeriod;

class DivisionUnavailablePeriodPolicy
{
    private function hasAccess(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('division_admin');
    }

    public function viewAny(User $user): bool
    {
        return $this->hasAccess($user);
    }

    public function create(User $user): bool
    {
        return $this->hasAccess($user);
    }

    public function view(User $user): bool
    {
        return $this->hasAccess($user);
    }

    public function update(User $user): bool
    {
        return $this->hasAccess($user);
    }

    public function delete(User $user): bool
    {
        return $this->hasAccess($user);
    }
}
