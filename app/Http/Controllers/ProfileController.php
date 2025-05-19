<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Provinsi;
use App\Models\Cabang;

class ProfileController extends Controller
{

    public function index()
    {
        return Inertia::render('Profile/Index',);
    }

    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'user' => $request->user(),
            'provinsi' => Provinsi::all(),
            'cabang' => Cabang::all(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            // Remove password if empty
            if (empty($validated['password'])) {
                unset($validated['password']);
            }

            // Update user data
            $user->update($validated);

            return Redirect::route('profile.index')->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
