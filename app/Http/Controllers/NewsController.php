<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('date', 'desc')->get();

        return Inertia::render('News/Index', [
            'news' => $news
        ]);
    }

    public function show($id)
    {
        $article = News::findOrFail($id);

        return Inertia::render('News/Show', [
            'article' => $article
        ]);
    }
}
