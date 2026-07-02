<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\WpUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Corcel\Services\PasswordService;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('leads');
        }

        return view('login');
    }

    /**
     * Authenticate against WordPress users (via Corcel).
     *
     * We verify the password manually instead of using Auth::attempt(), because
     * Corcel's built-in PasswordService only understands MD5 and the old phpass
     * ($P$) format. WordPress 6.8+ stores passwords in a new bcrypt-based
     * format ($wp$2y$...), which Corcel cannot verify on its own.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login    = $request->input('login');
        $password = $request->input('password');

        // Match against user_login or user_email
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'user_email' : 'user_login';
        $user  = WpUser::where($field, $login)->first();

        if ($user && $this->verifyWordPressPassword($password, $user->user_pass)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended('leads');
        }

        return back()
            ->withErrors(['login' => 'These credentials do not match our records.'])
            ->withInput($request->only('login'));
    }

    /**
     * Verify a plain password against any WordPress hash format:
     *  - $wp$2y$...  (WordPress 6.8+ bcrypt-based)
     *  - $P$ / $H$   (legacy phpass)
     *  - 32-char MD5 (very old)
     */
    protected function verifyWordPressPassword(string $password, string $hash): bool
    {
        // WordPress 6.8+ : "$wp" prefix + a bcrypt hash of a pre-hashed password.
        if (str_starts_with($hash, '$wp')) {
            $prehashed = base64_encode(
                hash_hmac('sha384', $password, 'wp-sha384', true)
            );

            return password_verify($prehashed, substr($hash, 3));
        }

        // Legacy MD5
        if (strlen($hash) <= 32) {
            return hash_equals($hash, md5($password));
        }

        // Legacy phpass ($P$ / $H$) — let Corcel handle it
        return (new PasswordService())->check($password, $hash);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}