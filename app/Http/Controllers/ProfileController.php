<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information (AJAX).
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'phone'  => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = $request->user();
        $user->name  = $request->name;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        if ($user->isDirty('email')) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'success' => 'Profile updated successfully!',
            'avatar_url' => asset('storage/' . $user->avatar)
        ]);
    }

    /**
     * Change the user's password (AJAX).
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'old_password'     => ['required'],
            'new_password'     => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        if (!Hash::check($request->old_password, $request->user()->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 422);
        }

        $request->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['success' => 'Password changed successfully!']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
