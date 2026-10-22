<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\Media;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.pages.index', ['pages' => Page::orderBy('sort_order')->get()]);
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'nav_label' => ['required', 'string', 'max:40'],
            'meta_title' => ['required', 'string', 'max:180'],
            'meta_description' => ['required', 'string', 'max:320'],
            'keywords' => ['nullable', 'string', 'max:320'],
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'h1' => ['required', 'string', 'max:180'],
            'intro' => ['required', 'string', 'max:800'],
            'banner_alt' => ['nullable', 'string', 'max:180'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('banner')) {
            Media::delete($page->banner_path);
            $data['banner_path'] = Media::store($request->file('banner'), 'pages');
            $data['banner_url'] = null;
        }

        unset($data['banner']);
        $page->update($data);

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Page content saved.');
    }
}
