<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds a local admin account so a fresh checkout has a way into /admin
 * without hand-editing a user's role via tinker.
 *
 * Intentionally local-only: production must never get a well-known admin
 * login from a seeder. Credentials come from the environment so they are
 * never hard-coded or committed.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $email = config('app.admin_seed_email');
        $password = config('app.admin_seed_password');

        if (! $email || ! $password) {
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => $password,
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ]
        );
    }
}
