<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Support\Media;
use App\Support\Slugs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index()
    {
        return view('admin.vehicles.index', ['vehicles' => Vehicle::orderBy('sort_order')->get()]);
    }

    public function create()
    {
        return view('admin.vehicles.form', ['vehicle' => new Vehicle(['seats' => 4, 'ac' => true, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $vehicle = Vehicle::create($this->payload($request));

        return redirect()->route('admin.vehicles.edit', $vehicle)->with('status', 'Vehicle created.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.form', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $vehicle->update($this->payload($request, $vehicle));

        return redirect()->route('admin.vehicles.edit', $vehicle)->with('status', 'Vehicle saved.');
    }

    public function destroy(Vehicle $vehicle)
    {
        Media::delete($vehicle->image_path);
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehicle removed.');
    }

    private function payload(Request $request, ?Vehicle $vehicle = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('vehicles', 'slug')->ignore($vehicle)],
            'example' => ['nullable', 'string', 'max:120'],
            'seats' => ['required', 'integer', 'min:1', 'max:30'],
            'price_label' => ['required', 'string', 'max:80'],
            'blurb' => ['required', 'string', 'max:800'],
            'local_value' => ['required', 'string', 'max:40'],
            'local_detail' => ['nullable', 'string', 'max:80'],
            'extra_km' => ['required', 'string', 'max:40'],
            'outstation' => ['required', 'string', 'max:40'],
            'image_alt' => ['required', 'string', 'max:180'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $path = $vehicle?->image_path;
        $url = $vehicle?->image_url;
        if ($request->hasFile('image')) {
            Media::delete($path);
            $path = Media::store($request->file('image'), 'vehicles');
            $url = null;
        }

        return [
            'name' => $data['name'],
            'slug' => ($data['slug'] ?? null) ?: Slugs::unique(Vehicle::class, $data['name'], $vehicle?->id),
            'example' => $data['example'] ?? '',
            'seats' => $data['seats'],
            'ac' => $request->boolean('ac'),
            'price_label' => $data['price_label'],
            'blurb' => $data['blurb'],
            'rates' => [
                ['label' => 'Local hire', 'value' => $data['local_value'], 'detail' => $data['local_detail'] ?? ''],
                ['label' => 'Extra kilometre', 'value' => $data['extra_km']],
                ['label' => 'Outstation', 'value' => $data['outstation']],
            ],
            'image_path' => $path,
            'image_url' => $url,
            'image_alt' => $data['image_alt'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
