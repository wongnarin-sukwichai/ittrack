<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'การเข้าสู่ระบบผ่าน Google ไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            return redirect('/login')->with('error',
                "อีเมล {$googleUser->getEmail()} ไม่มีสิทธิ์เข้าใช้งานระบบ กรุณาติดต่อเจ้าหน้าที่ IT เพื่อขอสิทธิ์"
            );
        }

        $user->update([
            'google_id' => $googleUser->getId(),
            'name'      => $googleUser->getName(),
            'avatar'    => $googleUser->getAvatar(),
        ]);

        Auth::login($user, remember: true);

        return redirect()->intended(route('home'));
    }
}
