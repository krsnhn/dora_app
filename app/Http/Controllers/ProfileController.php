<?php

namespace App\Http\Controllers;

use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary) {}

    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $url = $this->cloudinary->upload($request->file('profile_photo'), 'dora/profile-photos');

        if ($url) {
            $user->update(['profile_photo' => $url]);
        }

        return redirect()->route('profile.edit')->with('success', 'Profile photo updated.');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];

        if ($user->role === 'agency') {
            $rules = array_merge($rules, [
                'business_name'  => ['nullable', 'string', 'max:255'],
                'phone'          => ['nullable', 'string', 'max:50'],
                'address'        => ['nullable', 'string', 'max:500'],
                'facebook_page'  => ['nullable', 'url', 'max:255'],
            ]);
        }

        $validated = $request->validate($rules);
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request): RedirectResponse
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
