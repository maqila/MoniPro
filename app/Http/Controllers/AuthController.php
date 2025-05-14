<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        return view('auth.login');
    }
    public function register()
    {
        //
        return view('auth.register');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:dns|unique:users|',
            'username' => 'required|min:3|max:255|unique:users',
            'password' => 'required|min:6|max:255'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        return redirect('/')->with('success', 'User berhasil ditambahkan!');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|min:3',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return back()->with('loginError', 'login failed!');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Auth $auth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auth $auth)
    {
        //
        return view('auth.setting');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auth $auth)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Update langsung via Eloquent static method
        User::where('id', $userId)->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Name updated successfully.');
    }

    /**
     * Show the form for changing the password.
     */
    public function change()
    {
        return view('auth.change');
    }
    /**
     * Change the password of the authenticated user.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $userId = Auth::id();
        $currentHashedPassword = Auth::user()->password;

        // Cek apakah password lama cocok
        if (!Hash::check($request->current_password, $currentHashedPassword)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password menggunakan Eloquent static method
        User::where('id', $userId)->update([
            'password' => bcrypt($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auth $auth)
    {
        //
    }
}
