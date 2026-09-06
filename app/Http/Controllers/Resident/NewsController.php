<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Use case: "Melihat info berita terbaru" (Resident).
     */
    public function index(): View
    {
        $newsList = News::with('author')->latest()->paginate(6);

        return view('resident.news.index', compact('newsList'));
    }

    public function show(News $news): View
    {
        $news->load('author');

        return view('resident.news.show', compact('news'));
    }
}
