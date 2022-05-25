<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $rows = Page::all();
        return view('page.index', compact('rows'));
    }
    public function create()
    {
        return view('page.create');
    }
    public function store(Request $request)
    {
        $r = new Page;
        $r->title = $request->input('title');
        $r->slug = $request->input('slug');
        $r->body = $request->input('body');
        $r->layout = $request->input('layout');
        $r->published = $request->input('published');
        $r->save();
        return redirect()->route('page')->with('success', 'Berhasil menyimpan data');
    }
    public function edit($id)
    {
        $r = Page::findOrFail($id);
        return view('page.edit', compact('r'));
    }
    public function update(Request $request, $id)
    {
        $r = Page::findOrFail($id);
        $r->title = $request->input('title');
        $r->slug = $request->input('slug');
        $r->body = $request->input('body');
        $r->layout = $request->input('layout');
        $r->published = $request->input('published');
        $r->save();
        return redirect()->route('page')->with('success', 'Berhasil edit data');
    }
}
