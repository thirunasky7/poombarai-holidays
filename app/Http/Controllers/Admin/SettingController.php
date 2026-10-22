<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Media;
use App\Support\SiteSettings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit(SiteSettings $settings)
    {
        return view('admin.settings', ['values' => $settings->all()]);
    }

    public function update(Request $request, SiteSettings $settings)
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:120'],
            'whatsapp_number' => ['required', 'regex:/^\d{10,15}$/'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:120'],
            'address' => ['required', 'string', 'max:240'],
            'locality' => ['required', 'string', 'max:80'],
            'region' => ['required', 'string', 'max:80'],
            'postal_code' => ['required', 'string', 'max:12'],
            'maps_embed_url' => ['required', 'url', 'max:500'],
            'maps_link' => ['required', 'url', 'max:500'],
            'hours' => ['required', 'string', 'max:160'],
            'check_in' => ['required', 'string', 'max:40'],
            'check_out' => ['required', 'string', 'max:40'],
            'ga_id' => ['nullable', 'string', 'max:40'],
            'site_url' => ['required', 'url', 'max:200'],
            'hero_image_alt' => ['required', 'string', 'max:180'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:png,ico,svg,webp,jpg,jpeg', 'max:1024'],
            'price_range' => ['nullable', 'string', 'max:20'],
            'geo_lat' => ['required', 'numeric'],
            'geo_lng' => ['required', 'numeric'],
            'footer_note' => ['required', 'string', 'max:400'],
            'keywords' => ['nullable', 'string', 'max:320'],
        ]);

        $current = $settings->all();
        $path = $current['hero_image_path'] ?? '';
        $logoPath = $current['logo_path'] ?? '';
        $faviconPath = $current['favicon_path'] ?? '';

        if ($request->hasFile('hero_image')) {
            Media::delete($path ?: null);
            $path = Media::store($request->file('hero_image'), 'hero');
        }
        if ($request->boolean('remove_logo')) {
            Media::delete($logoPath ?: null);
            $logoPath = '';
        } elseif ($request->hasFile('logo')) {
            Media::delete($logoPath ?: null);
            $logoPath = Media::store($request->file('logo'), 'brand');
        }
        if ($request->boolean('remove_favicon')) {
            Media::delete($faviconPath ?: null);
            $faviconPath = '';
        } elseif ($request->hasFile('favicon')) {
            Media::delete($faviconPath ?: null);
            $faviconPath = Media::store($request->file('favicon'), 'brand');
        }

        unset($data['hero_image'], $data['logo'], $data['favicon']);
        $data['hero_image_path'] = $path;
        $data['logo_path'] = $logoPath;
        $data['favicon_path'] = $faviconPath;
        $data['country'] = 'IN';
        $settings->put($data);

        return back()->with('status', 'Site settings saved.');
    }
}
