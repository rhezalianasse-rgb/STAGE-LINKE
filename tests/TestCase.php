<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function actingAs(Authenticatable $user, $guard = null)
    {
        $result = parent::actingAs($user, $guard);

        if ($user instanceof User) {
            $this->withSession(['auth_session_version' => $user->auth_session_version]);
        }

        return $result;
    }
}
