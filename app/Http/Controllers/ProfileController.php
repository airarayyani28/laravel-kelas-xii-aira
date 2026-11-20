<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('profile', 'role');
        return view('components.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'umur' => ['required', 'integer', 'min:1'],
            'alamat' => ['required', 'string'],
            'bio' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update data tabel users
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update atau buat data tabel profiles
        if ($user->profile) {
            $user->profile->update([
                'umur' => $request->umur,
                'alamat' => $request->alamat,
                'bio' => $request->bio ?? '-',
            ]);
        } else {
            $profile = Profile::create([
                'umur' => $request->umur,
                'alamat' => $request->alamat,
                'bio' => $request->bio ?? '-',
            ]);
            $user->profile_id = $profile->id;
            $user->save();
        }

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }
}