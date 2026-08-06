<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EofficeUser;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLog,
    ) {}

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect'))
                ->user();
        } catch (Throwable) {
            return redirect()
                ->route('login')
                ->with('error', 'ไม่สามารถเข้าสู่ระบบด้วย Google ได้ กรุณาลองใหม่อีกครั้ง');
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));

        if ($email === '') {
            return redirect()
                ->route('login')
                ->with('error', 'ไม่พบอีเมลจากบัญชี Google');
        }

        $user = EofficeUser::query()
            ->whereRaw('LOWER(TRIM(email)) = ?', [$email])
            ->first();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('auth_denied', true)
                ->with('denied_email', $email);
        }

        if (! $user->isActiveEmployment() || ! $user->hasSciregAccess()) {
            return redirect()
                ->route('login')
                ->with('auth_no_access', true)
                ->with('denied_email', $email);
        }

        Auth::login($user);

        request()->session()->regenerate();
        request()->session()->put('google_avatar', $googleUser->getAvatar());
        request()->session()->put('google_name', $googleUser->getName());

        $this->auditLog->write(
            module: 'auth',
            action: 'login',
            description: 'เข้าสู่ระบบด้วย Google',
            requestData: ['email' => $email],
            statusCode: 302,
        );

        return redirect()->intended(route('home'));
    }

    public function logout(): RedirectResponse
    {
        $this->auditLog->write(
            module: 'auth',
            action: 'logout',
            description: 'ออกจากระบบ',
            statusCode: 302,
        );

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
