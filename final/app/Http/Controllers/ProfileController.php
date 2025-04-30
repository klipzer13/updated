<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    // Show profile settings page
    public function show()
    {
        $user = Auth::user();
        $departments = \App\Models\Department::all(); // Make sure you have a Department model

        return view('chairperson.settings', [
            'user' => $user,
            'departments' => $departments
        ]);
    }
    public function showemployee()
    {
        $user = Auth::user();
        $departments = \App\Models\Department::all(); // Make sure you have a Department model

        return view('employee.settings', [
            'user' => $user,
            'departments' => $departments
        ]);
    }

    // Update profile information
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
      

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,png',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // Store new avatar
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('profile/avatars', 'public');
                $validated['avatar'] = 'storage/' . $path;
            } else {
                $validated['avatar'] = 'storage/profile/avatars/profile.png';
            }
        }
        

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect']);
        }

        $user->password = Hash::make($request->password);
        $user->save(); // Use save() instead of update()

        return back()->with('success', 'Password updated successfully!');
    }
    // Store a new department
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        
        ]);

        \App\Models\Department::create($validated);

        return back()->with('success', 'Department created successfully!');
    }

}