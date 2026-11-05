<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Package;
use App\Support\Media;
use App\Support\Slugs;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    public function index()
    {
        return view('admin.packages.index', [
            'packages' => Package::with('destinations')->orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.packages.form', [
            'package' => new Package(['is_active' => true]),
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $package = Package::create($this->payload($request));
        $package->destinations()->sync($request->input('destinations', []));
        $this->storePhotos($request, $package);

        return redirect()->route('admin.packages.edit', $package)->with('status', 'Package created.');
    }

    public function edit(Package $package)
    {
        $package->load(['destinations', 'images']);

        return view('admin.packages.form', [
            'package' => $package,
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Package $package)
    {
        $package->update($this->payload($request, $package));
        $package->destinations()->sync($request->input('destinations', []));
        $this->syncPhotos($request, $package);
        $this->storePhotos($request, $package);

        return redirect()->route('admin.packages.edit', $package)->with('status', 'Package saved.');
    }

    public function destroy(Package $package)
    {
        foreach ($package->images as $image) {
            Media::delete($image->path);
        }
        Media::delete($package->image_path);
        $package->delete();

        return redirect()->route('admin.packages.index')->with('status', 'Package removed.');
    }

    private function payload(Request $request, ?Package $package = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('packages', 'slug')->ignore($package)],
            'duration' => ['required', 'string', 'max:80'],
            'fare' => ['required', 'string', 'max:80'],
            'price_label' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:800'],
            'image_alt' => ['required', 'string', 'max:180'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'destinations' => ['nullable', 'array'],
            'destinations.*' => ['integer', 'exists:destinations,id'],
            'photos.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photo_alts.*' => ['nullable', 'string', 'max:180'],
            'existing_alts' => ['nullable', 'array'],
            'existing_alts.*' => ['nullable', 'string', 'max:180'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ]);

        $path = $package?->image_path;
        $url = $package?->image_url;
        if ($request->hasFile('image')) {
            Media::delete($path);
            $path = Media::store($request->file('image'), 'packages');
            $url = null;
        }

        return [
            'name' => $data['name'],
            'slug' => ($data['slug'] ?? null) ?: Slugs::unique(Package::class, $data['name'], $package?->id),
            'duration' => $data['duration'],
            'fare' => $data['fare'],
            'price_label' => $data['price_label'],
            'summary' => $data['summary'],
            'image_path' => $path,
            'image_url' => $url,
            'image_alt' => $data['image_alt'],
            'featured' => $request->boolean('featured'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }

    private function storePhotos(Request $request, Package $package): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $alts = $request->input('photo_alts', []);
        $start = (int) $package->images()->max('sort_order') + 1;

        foreach ($request->file('photos') as $index => $file) {
            if (! $file) {
                continue;
            }
            $package->images()->create([
                'path' => Media::store($file, 'packages/gallery'),
                'alt' => $alts[$index] ?? $package->name,
                'sort_order' => $start + $index,
            ]);
        }
    }

    private function syncPhotos(Request $request, Package $package): void
    {
        $alts = $request->input('existing_alts', []);
        $remove = array_map('intval', $request->input('remove_images', []));

        foreach ($package->images as $image) {
            if (in_array($image->id, $remove, true)) {
                Media::delete($image->path);
                $image->delete();
                continue;
            }
            if (isset($alts[$image->id])) {
                $image->update(['alt' => $alts[$image->id] ?: $package->name]);
            }
        }
    }
}
