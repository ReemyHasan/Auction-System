<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(){
        return view("auth.register");
    }
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        User::create($validated);
        return redirect()->route("login")->with("success","Registered successfully");
    }
    public function login()
    {
        return view("auth.login");
    }
    public function authenticate(AuthRequest $request)
    {
        $remember = (boolean)$request->remember;
        $validated = $request->validated();
        if (auth()->attempt($validated, $remember)) {
            request()->session()->regenerate();
            return redirect()->route("home")->with("success", "LOGGED IN SUCCESSFULLY");
        }
        return redirect()->back()->with("error", "Error ");
    }
    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route("login")->with("success", "LOGGED OUT SUCCESSFULLY");

    }


}
