<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Sign in by user ID, avoiding the global scope on User
     *
     * @param int $id
     *
     * @return User
     */
    protected function signInById(int $id): User
    {
        $user = User::findOrFail($id);
        $this->actingAs($user);

        return $user;
    }
}
