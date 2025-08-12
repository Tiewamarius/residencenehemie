<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredAdminController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('adminauth.auth.register');
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
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Admin::class],
        'phone_number' => ['required', 'string', 'max:20', 'unique:'.Admin::class], // Ajout du numéro de téléphone
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $admin = Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number, // Ajout du numéro de téléphone
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($admin));

    Auth::guard('admin')->login($admin);

    return redirect(route('admin.homes', absolute: false));
}
}
