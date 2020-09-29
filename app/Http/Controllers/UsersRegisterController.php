<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class UsersRegisterController extends Controller
{
    /**
     * hash incoming password
     *
     * @param $password
     *
     * @return string
     */
    private function hashPassword($password)
    {
        return Crypt::encrypt($password);
    }


    /**
     * generate a remember token
     *
     * @return string
     */
    private function generateRememberToken()
    {
        return Str::random(100);
    }

    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'username' => 'required|unique:users,username',
            'email' => 'required|email:rfc|unique:users,email',
            'password' => 'required|confirmed|min:5',
        ]);

        $attributes['password'] = $this->hashPassword($attributes['password']);
        $rememberToken = $this->generateRememberToken();

        User::create([
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
            'remember_token' => $rememberToken,
        ]);

        return response()->json([
            'created' => true,
            'remember_token' => $rememberToken,
        ], 201);
    }


}
