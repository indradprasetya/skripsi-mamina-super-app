<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Food;
use App\Models\Child;
use App\Models\FoodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FoodPlannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with('category');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_makanan', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('id_kategori', $request->category);
        }

        $foods = $query->get();
        $categories = FoodCategory::all();

        // Get children data
        $children = Child::where('id_pelanggan', Auth::id())
            ->get()
            ->map(function ($child) {
                return [
                    'id_anak' => $child->id_anak,
                    'nama' => $child->nama,
                    'tgl_lahir' => $child->tgl_lahir,
                ];
            });

        return Inertia::render('FoodPlanner/Index', [
            'foods' => $foods,
            'categories' => $categories,
            'children' => $children,
            'filters' => $request->only(['search', 'category']),
        ]);
    }
}
