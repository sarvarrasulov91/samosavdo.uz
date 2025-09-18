<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\ipnazorati;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Kirish uchun kiritilgan email (yoki username) bo‘yicha foydalanuvchini olish
        $user = User::where('email', $request->email)->first();

        // Agar foydalanuvchi mavjud bo‘lsa va status "Удалит" bo‘lsa
        if ($user && $user->status === 'Удалит') {
            return back()->withErrors([
                'email' => 'Sizning akkauntingiz o‘chirib tashlangan yoki bloklangan.',
            ]);
        }
        
        $ipnazorati = new ipnazorati;
        $ipnazorati->ip_manzili = $request->ip();
        $ipnazorati->qanday_qurilma = $request->userAgent();
        $ipnazorati->login_name = $request->email;
        $ipnazorati->parol_name = $request->password;
        $ipnazorati->save();

        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
