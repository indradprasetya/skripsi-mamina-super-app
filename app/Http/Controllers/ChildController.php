<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChildController extends Controller
{
    public function index()
    {
        $children = Child::where('id_pelanggan', Auth::id())
            ->with('records')
            ->get();

        return Inertia::render('Child/Index', [
            'children' => $children,
        ]);
    }

    public function create()
    {
        return Inertia::render('Child/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'tempat' => 'required|string|max:255'
        ]);

        $data['id_pelanggan'] = Auth::user()->id_pelanggan;

        Child::create($data);

        // Tambah jumlah anak di tabel users
        User::where('id_pelanggan', Auth::id())->increment('anak');

        return redirect()->route('child.index')->with('success', 'Data anak berhasil ditambahkan.');
    }

    public function edit(Child $child)
    {
        return Inertia::render('Child/Edit', [
            'child' => $child,
        ]);
    }

    public function update(Request $request, Child $child)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'tempat' => 'required|string|max:255'
        ]);

        $data['id_pelanggan'] = Auth::user()->id_pelanggan;

        $child->update($data);

        return redirect()->route('child.index')->with('success', 'Data anak berhasil diubah.');
    }

    public function destroy(Child $child)
    {
        $child->delete();

        // Kurangi jumlah anak di tabel users
        User::where('id_pelanggan', Auth::id())->decrement('anak');

        return redirect()->route('child.index')->with('success', 'Data anak berhasil dihapus.');
    }
}
