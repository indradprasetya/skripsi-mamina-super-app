<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $news = News::orderBy('date', 'desc')->get();

        return Inertia::render('Article', [
            'news' => $news
        ]);
    }

    public function show($id)
    {
        $article = News::findOrFail($id);

        return Inertia::render('Article/Show', [
            'article' => $article
        ]);
    }
}
