<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::all();
        return view('dashboard.usermanagement.index', compact('users'));
    }

    public function editRole($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.usermanagement.edit-role', compact('user'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:0,1,2,3', // validasi angka
        ]);

        User::where('id', $id)->update([
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Role updated successfully.');
    }


    public function editPassword($id)
    {
        $user = User::findOrFail($id);
        return view('dashboard.usermanagement.change-password', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        User::where('id', $id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('users.index')->with('success', 'Password updated successfully.');
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        if ($id == Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        User::where('id', $id)->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
