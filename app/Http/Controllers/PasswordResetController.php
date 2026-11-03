<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('auth.forgot-password');
    }

    public function email(Request $r)
    {
        $r->validate(['email' => 'required|email']);
        $user = User::where('email', $r->input('email'))->first();
        $demoLink = null;

        if ($user) {
            $token = Password::createToken($user);

            if (in_array(config('mail.default'), ['log', 'array'])) {
                // No mail server is set up (local demo): show the link on screen instead of emailing it.
                $demoLink = route('password.reset', ['token' => $token, 'email' => $user->email]);
            } else {
                $user->sendPasswordResetNotification($token);
            }
        }

        return back()
            ->with('status', 'If that email has an account, a password reset link has been prepared.')
            ->with('demo_link', $demoLink);
    }

    public function form(Request $r, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $r->query('email')]);
    }

    public function update(Request $r)
    {
        $r->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $r->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Your password was changed. You can log in now.')
            : back()->withErrors(['email' => 'This reset link is invalid or has expired. Please request a new one.'])
                ->withInput($r->only('email'));
    }
}
