<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Support\Media;
use App\Support\Slugs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DestinationController extends Controller
{
    public function index()
    {
        return view('admin.destinations.index', [
            'destinations' => Destination::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.destinations.form', ['destination' => new Destination(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $destination = Destination::create($this->payload($request));

        return redirect()->route('admin.destinations.edit', $destination)->with('status', 'Place created.');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.form', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $destination->update($this->payload($request, $destination));

        return redirect()->route('admin.destinations.edit', $destination)->with('status', 'Place saved.');
    }

    public function destroy(Destination $destination)
    {
        Media::delete($destination->image_path);
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('status', 'Place removed.');
    }

    private function payload(Request $request, ?Destination $destination = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('destinations', 'slug')->ignore($destination)],
            'name_ta' => ['nullable', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1200'],
            'best_time' => ['required', 'string', 'max:160'],
            'time_needed' => ['required', 'string', 'max:80'],
            'image_alt' => ['required', 'string', 'max:180'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $path = $destination?->image_path;
        $url = $destination?->image_url;
        if ($request->hasFile('image')) {
            Media::delete($path);
            $path = Media::store($request->file('image'), 'places');
            $url = null;
        }

        return [
            'name' => $data['name'],
            'slug' => ($data['slug'] ?? null) ?: Slugs::unique(Destination::class, $data['name'], $destination?->id),
            'name_ta' => $data['name_ta'] ?? '',
            'description' => $data['description'],
            'best_time' => $data['best_time'],
            'time_needed' => $data['time_needed'],
            'popular' => $request->boolean('popular'),
            'image_path' => $path,
            'image_url' => $url,
            'image_alt' => $data['image_alt'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
