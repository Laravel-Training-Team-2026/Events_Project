<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\Auth\RegisterController as BackpackRegisterController;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRegisterController extends BackpackRegisterController
{
    /**
     * Only Backpack's /admin/register flow creates administrator accounts.
     * The public /register route continues to create regular users.
     *
     * @param  array<string, mixed>  $data
     */
    protected function create(array $data): Authenticatable
    {
        $userModel = config('backpack.base.user_model_fqn');

        return $userModel::create([
            'name' => $data['name'],
            backpack_authentication_column() => $data[backpack_authentication_column()],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);
    }

    /**
     * Backpack's session middleware runs before registration and records the
     * current admin's password hash. Refresh it after logging in the newly
     * created admin so the first dashboard request remains authenticated.
     */
    public function register(Request $request)
    {
        $response = parent::register($request);

        if (backpack_auth()->check()) {
            $request->session()->put(
                'password_hash_'.backpack_guard_name(),
                backpack_user()->getAuthPassword()
            );
        }

        return $response;
    }
}
