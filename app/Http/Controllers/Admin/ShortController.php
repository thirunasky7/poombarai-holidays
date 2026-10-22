<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortVideo;
use App\Support\Media;
use Illuminate\Http\Request;

class ShortController extends Controller
{
    public function index()
    {
        return view('admin.shorts.index', [
            'shorts' => ShortVideo::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.shorts.form', [
            'short' => new ShortVideo(['is_active' => true, 'button_label' => 'Enquire on WhatsApp']),
        ]);
    }

    public function store(Request $request)
    {
        $short = ShortVideo::create($this->payload($request, true));

        return redirect()->route('admin.shorts.edit', $short)->with('status', 'Short video saved.');
    }

    public function edit(ShortVideo $short)
    {
        return view('admin.shorts.form', compact('short'));
    }

    public function update(Request $request, ShortVideo $short)
    {
        $short->update($this->payload($request, false, $short));

        return redirect()->route('admin.shorts.edit', $short)->with('status', 'Short video saved.');
    }

    public function destroy(ShortVideo $short)
    {
        Media::delete($short->video_path);
        Media::delete($short->poster_path);
        $short->delete();

        return redirect()->route('admin.shorts.index')->with('status', 'Short video removed.');
    }

    private function payload(Request $request, bool $creating, ?ShortVideo $short = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:80'],
            'caption' => ['nullable', 'string', 'max:240'],
            'button_label' => ['required', 'string', 'max:40'],
            'video' => [$creating ? 'required' : 'nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:30720'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $videoPath = $short?->video_path;
        if ($request->hasFile('video')) {
            Media::delete($videoPath);
            $videoPath = Media::store($request->file('video'), 'shorts');
        }

        $posterPath = $short?->poster_path;
        if ($request->hasFile('poster')) {
            Media::delete($posterPath);
            $posterPath = Media::store($request->file('poster'), 'shorts');
        }

        return [
            'title' => $data['title'],
            'caption' => $data['caption'] ?? '',
            'button_label' => $data['button_label'],
            'video_path' => $videoPath,
            'poster_path' => $posterPath,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
