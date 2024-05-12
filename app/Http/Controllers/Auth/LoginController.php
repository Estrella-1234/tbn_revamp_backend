<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Attempt to log in the user
        if ($this->attemptLogin($request)) {
            // Check if the authenticated user has the role of 'admin'
            if ($this->guard()->user()->user_role == 'admin') {
                // Redirect admin users to the intended location after login
                return $this->sendLoginResponse($request);
            } else {
                // If the user is not an admin, logout and redirect back with an error message
                $this->guard()->logout();
                $request->session()->invalidate();
                throw ValidationException::withMessages([
                    $this->username() => [trans('Not authorized for admin access.')],
                ]);
            }
        }

        // If the authentication attempt was unsuccessful, redirect back with error message
        return $this->sendFailedLoginResponse($request);
    }

    protected function redirectTo()
    {
        session()->flash('success', 'You are logged in!');
        return $this->redirectTo;
    }
}
