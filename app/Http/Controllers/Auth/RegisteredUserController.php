<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'traveler');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:traveler,agency'],
            'terms' => ['accepted'],
        ];

        if ($role === 'agency') {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:30'];
            $rules['address'] = ['required', 'string', 'max:500'];
            $rules['valid_id'] = ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'];
        }

        $validated = $request->validate($rules);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'status' => 'active',
        ];

        if ($role === 'agency') {
            $idPath = $this->cloudinary->upload($request->file('valid_id'), 'dora/agency-ids');
            $userData = array_merge($userData, [
                'business_name' => $validated['business_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'valid_id_path' => $idPath,
                'agency_status' => 'pending',
                'status' => 'pending',
            ]);
        }

        $user = User::create($userData);

        event(new Registered($user));
        Auth::login($user);

        if ($user->isAgency()) {
            return redirect()->route('agency.pending');
        }

        return redirect(route('home', absolute: false));
    }
}
