<?php

namespace App\Http\Controllers;

use App\Models\Kota;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Cabang;
use App\Models\Provinsi;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function indexRegister()
    {
        return Inertia::render('Auth/Register', [
            'provinsi' => Provinsi::all(['id_provinsi', 'nama_provinsi']),
            'cabang' => Cabang::all(['id_cabang', 'nama_cabang']),
        ]);
    }

    public function getKota($id_provinsi)
    {
        return Kota::where('id_provinsi', $id_provinsi)->get(['id_kota', 'nama_kota']);
    }

    public function getKecamatan($id_kota)
    {
        return Kecamatan::where('id_kota', $id_kota)->get(['id_kecamatan', 'nama_kecamatan']);
    }

    public function getKelurahan($id_kecamatan)
    {
        return Kelurahan::where('id_kecamatan', $id_kecamatan)->get(['id_kelurahan', 'nama_kelurahan']);
    }

    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();

        //Register
        $user = User::create($fields);

        //Login
        Auth::login($user);

        //Redirect
        return redirect()->route('home');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function editPassword()
    {
        return Inertia::render('Auth/EditPassword');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'string', 'min:5', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui');
    }
}
