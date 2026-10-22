<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Support\Media;
use App\Support\Slugs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index()
    {
        return view('admin.rooms.index', [
            'rooms' => Room::with('images')->orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.rooms.form', ['room' => new Room(['type' => 'Cottage', 'occupancy' => 2, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $room = Room::create($this->validated($request));
        $this->storePhotos($request, $room);

        return redirect()->route('admin.rooms.edit', $room)->with('status', 'Stay created.');
    }

    public function edit(Room $room)
    {
        $room->load('images');

        return view('admin.rooms.form', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $room->update($this->validated($request, $room));
        $this->syncPhotos($request, $room);
        $this->storePhotos($request, $room);

        return redirect()->route('admin.rooms.edit', $room)->with('status', 'Stay saved.');
    }

    public function destroy(Room $room)
    {
        foreach ($room->images as $image) {
            Media::delete($image->path);
        }
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('status', 'Stay removed.');
    }

    private function validated(Request $request, ?Room $room = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('rooms', 'slug')->ignore($room)],
            'type' => ['required', Rule::in(['Room', 'Cottage', 'Homestay'])],
            'price_label' => ['required', 'string', 'max:80'],
            'occupancy' => ['required', 'integer', 'min:1', 'max:20'],
            'location' => ['required', 'string', 'max:160'],
            'summary' => ['required', 'string', 'max:800'],
            'amenities' => ['nullable', 'string', 'max:400'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'photos.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photo_alts.*' => ['nullable', 'string', 'max:180'],
        ]);

        return [
            'name' => $data['name'],
            'slug' => ($data['slug'] ?? null) ?: Slugs::unique(Room::class, $data['name'], $room?->id),
            'type' => $data['type'],
            'price_label' => $data['price_label'],
            'occupancy' => $data['occupancy'],
            'location' => $data['location'],
            'summary' => $data['summary'],
            'amenities' => $this->amenities($data['amenities'] ?? ''),
            'featured' => $request->boolean('featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }

    private function amenities(string $raw): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    private function storePhotos(Request $request, Room $room): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $alts = $request->input('photo_alts', []);
        $start = (int) $room->images()->max('sort_order') + 1;

        foreach ($request->file('photos') as $index => $file) {
            if (! $file) {
                continue;
            }
            $room->images()->create([
                'path' => Media::store($file, 'rooms'),
                'alt' => $alts[$index] ?? $room->name,
                'sort_order' => $start + $index,
            ]);
        }
    }

    private function syncPhotos(Request $request, Room $room): void
    {
        $alts = $request->input('existing_alts', []);
        $remove = array_map('intval', $request->input('remove_images', []));

        foreach ($room->images as $image) {
            if (in_array($image->id, $remove, true)) {
                Media::delete($image->path);
                $image->delete();
                continue;
            }
            if (isset($alts[$image->id])) {
                $image->update(['alt' => $alts[$image->id] ?: $room->name]);
            }
        }
    }
}
