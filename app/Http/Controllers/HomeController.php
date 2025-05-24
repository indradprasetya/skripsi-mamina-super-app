<?php

namespace App\Http\Controllers;

use App\Models\News;
use Inertia\Inertia;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $children = Child::with(['records' => function ($query) {
            $query->orderBy('tanggal_catatan', 'desc');
        }])
            ->where('id_pelanggan', Auth::id())
            ->get();

        $news = News::orderBy('created_at', 'desc')->limit(4)->get();


        return Inertia::render('Home', [
            'children' => $children,
            'news' => $news
        ]);
    }
}
