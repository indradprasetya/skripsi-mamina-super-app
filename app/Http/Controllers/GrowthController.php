<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AntropometriBbTbLaki;

class GrowthController extends Controller
{
    public function index()
    {
        $children = Child::with(['records' => function($query) {
                $query->orderBy('tanggal_catatan', 'desc');
            }])
            ->where('id_pelanggan', Auth::id())
            ->get();

        return Inertia::render('Growth', [
            'children' => $children
        ]);
    }

}
