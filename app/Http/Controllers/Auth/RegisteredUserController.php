<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Jobs\SendSms;
use App\Mail\SendMessages;
use App\Models\CodeVerify;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
             'phone' => ['required', 'string', 'lowercase','max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        //dd($request->all());
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        //dd($user);
        event(new Registered($user));
        
        Auth::login($user);
        $userId=Auth::id();

        $verificationCode=rand(1000,9999);
        $data=CodeVerify::create([
            'user_id'=>$userId,
            'code'=>$verificationCode
        ]);
       SendSms::dispatch($data,$user);
        return redirect(route('userverify', absolute: false));
    }
}
