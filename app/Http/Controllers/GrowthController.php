<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AntropometriBbTbLaki;
use App\Models\AntropometriBbTbPerempuan;
use App\Models\AntropometriBbULaki;
use App\Models\AntropometriBbUPerempuan;
use App\Models\AntropometriTbULaki;
use App\Models\AntropometriTbUPerempuan;
use App\Models\AntropometriImtULaki;
use App\Models\AntropometriImtUPerempuan;
use Carbon\Carbon;

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
