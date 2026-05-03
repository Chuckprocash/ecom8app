<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Response;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    public function showLoginForm() {

        if(Auth::guard()->check() && Auth::user()->isAdmin == 1){
            return redirect()->route('admin.dashboard');
        }

        return Inertia::render('Admin/Auth/Login');
    }    

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'isAdmin' => true])){
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->with('error', 'Invalid credentials.');

    }

    public function logout(Request $request){
        // dd($request);
        Auth::guard('web')->logout();
        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }


     /**
     * Display the registration view.
     */
    public function create()
    {
        // Check if user is logged in and is admin
        if (!Auth::check() || Auth::user()->isAdmin !== 1) {
            return redirect()->route('home.index')->with([
                'error' => 'Unauthorized access. Admin only.'
            ]);
        }
        //
        return Inertia::render('Admin/Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if user is logged in and is admin
        if (!Auth::check() || Auth::user()->isAdmin !== 1) {
            return redirect()->route('home.index')->with([
                'error' => 'Unauthorized access. Admin only.'
            ]);
        }
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'isAdmin' => 1,
        ]);

        event(new Registered($user));
        if($user){
            Auth::guard('web')->logout();
            $request->session()->invalidate();
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function createPrimaryAdmin() {
        // Check if there is any existing admin
        $admins = User::where('isAdmin', 1)->get();
        if ($admins->count()) {
            return redirect()->route('home.index')->with([
                'error' => 'Unauthorized access.'
            ]);
        }
        //

        $user = User::create([
            'name' => env('ADMIN_NAME', 'Admin'),  // ✅ Add quotes and default value
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD')),  // ✅ Use dedicated password
            'isAdmin' => 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

}
