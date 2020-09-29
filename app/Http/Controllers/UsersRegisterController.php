<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UsersRegisterController extends Controller
{
    protected function hashPassword($password)
    {
        return Crypt::encrypt($password);
    }

    public function store(Request $request)
    {

        $attributes = $this->validate($request, [
            'username' => 'required|unique:users,username',
            'email' => 'required|email:rfc|unique:users,email',
            'password' => 'required|confirmed|min:5',
        ]);

        $attributes['password'] = $this->hashPassword($attributes['password']);

        User::create([
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => $attributes['password'],
        ]);

        return response()->json(['created' => true], 201);
    }
}
