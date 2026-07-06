<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('manager.settings.profile', compact('user'));
    }

    public function security()
    {
        return view('manager.settings.security');
    }

    public function team()
    {
        $user = Auth::user();
        $employees = User::where('id', '!=', $user->id)->orderBy('name')->get();
        return view('manager.settings.team', compact('employees'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:manager,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Employee account created successfully.');
    }

    public function destroyEmployee(User $employee)
    {
        if ($employee->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $employee->delete();
        return back()->with('success', 'Employee account deleted successfully.');
    }
}
