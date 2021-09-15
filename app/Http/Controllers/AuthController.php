<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $tokenString = 'NovinPardakhtByMojSkin';

    public function __construct() {
        return $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        return view('auth.login');
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // User is exists?
        $user = User::whereEmail($request->email)->first();
        if (!$user or Hash::check($request->password, $user->password)) {
            return response(['status' => false, 'message' => 'Invalid credentials.'], 401);
        }

        // Login user
        Auth::loginUsingId($user->id, true);

        // Associate authenticate token for API uses
        $user_token = Auth::user()->createToken($this->tokenString)->plainTextToken;

        return response(['status' => true, 'token' => $user_token], 200);
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::loginUsingId($user->id, true);
        $user_token = $user->createToken($this->tokenString)->plainTextToken;

        return response(['status' => true, 'token' => $user_token], 201);
    }

    public function logout(Request $request) {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
            Auth::logout();
        }
        return redirect()->route('welcome');
    }
}
