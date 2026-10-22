<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Support\Media;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return view('admin.banners.index', [
            'banners' => Banner::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $banner = Banner::create($this->payload($request));

        return redirect()->route('admin.banners.edit', $banner)->with('status', 'Banner saved.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $banner->update($this->payload($request, $banner));

        return redirect()->route('admin.banners.edit', $banner)->with('status', 'Banner saved.');
    }

    public function destroy(Banner $banner)
    {
        Media::delete($banner->image_path);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('status', 'Banner removed.');
    }

    private function payload(Request $request, ?Banner $banner = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:80'],
            'text' => ['required', 'string', 'max:240'],
            'button_label' => ['nullable', 'string', 'max:40'],
            'button_url' => ['nullable', 'string', 'max:200'],
            'image_alt' => ['required', 'string', 'max:180'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $path = $banner?->image_path;
        $url = $banner?->image_url;
        if ($request->hasFile('image')) {
            Media::delete($path);
            $path = Media::store($request->file('image'), 'banners');
            $url = null;
        }

        return [
            'title' => $data['title'],
            'text' => $data['text'],
            'button_label' => $data['button_label'] ?? '',
            'button_url' => $data['button_url'] ?? '',
            'image_path' => $path,
            'image_url' => $url,
            'image_alt' => $data['image_alt'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
