<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $newsItems = News::all();
        return view('news.index', compact('newsItems'));
    }

    public function adminIndex()
    {
        $newsItems = News::orderByDesc('published_at')->paginate(10);
        return view('admin.news.index', compact('newsItems'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',          
        ]);
        $data['published_at'] = now();
        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'Nieuwsitem toegevoegd.');
    }

    public function show($id)
    {
        $newsItem = News::findOrFail($id);
        return view('admin.news.show', compact('newsItem'));
    }

    public function edit($id)
    {
        $newsItem = News::findOrFail($id);
        return view('admin.news.edit', compact('newsItem'));
    }

    public function update(Request $request, $id)
    {
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $newsItem = News::findOrFail($id);
        $newsItem->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Nieuwsitem bijgewerkt.');
    }

    public function destroy($id)
    {
        $newsItem = News::findOrFail($id);
        $newsItem->delete();
        return redirect()->route('admin.news.index')->with('success', 'Nieuwsitem verwijderd.');
    }
}
