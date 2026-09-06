<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('admin.news.index', [
            'newsList' => News::with('author')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(StoreNewsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'), 'news');
        }

        $request->user()->news()->create($data);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dipublikasikan.');
    }

    public function show(News $news): View
    {
        $news->load('author');

        return view('admin.news.show', compact('news'));
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(UpdateNewsRequest $request, News $news): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $this->storeImage($request->file('image'), 'news');
        }

        $news->update($data);

        return redirect()
            ->route('admin.news.show', $news)
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Request $request, News $news): RedirectResponse
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    private function storeImage($file, string $folder): string
    {
        return $file->store($folder, 'public');
    }
}