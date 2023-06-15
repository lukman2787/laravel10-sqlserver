<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Fascades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {


        // $user = User::whereEmail($request->email);

        // $attributes = $request->validate([
        //     'email' => ['required', 'email'],
        //     'passsword' => ['required'],
        // ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // return redirect('/dashboard')->with('success', 'You are now login');

            if (auth()->user()->role == 'User') {
                return redirect()->route('user.page')->with('success', 'You are now login');
            }
            if (auth()->user()->role == 'Admin') {
                return redirect()->route('admin.page')->with('success', 'You are now login');
            }
        }

        throw ValidationException::withMessages([
            'username' => 'You provide credentials does not match our records.',
            'password' => 'Your password is wrong',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
