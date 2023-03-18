<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole(config('roles.ADMIN'))) {
            return true;
        }

        return null;
    }

    /**
     * Determine if the given loan can be viewed by the user.
     */
    public function update(?User $user, Loan $loan): bool
    {
        return $user?->id === $loan->user_id;
    }
}
