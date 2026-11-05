<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\ShortVideo;
use App\Models\TourPlan;

class FrontController extends Controller
{
    public function home()
    {
        return view('front.home', [
            'page' => Page::where('slug', 'home')->firstOrFail(),
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'shorts' => ShortVideo::where('is_active', true)->orderBy('sort_order')->get(),
            'packages' => Package::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function places()
    {
        return view('front.places', [
            'page' => Page::where('slug', 'places')->firstOrFail(),
            'places' => Destination::where('is_active', true)->orderBy('sort_order')->get(),
            'plans' => TourPlan::with('stops.destination')->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function contact()
    {
        return view('front.contact', [
            'page' => Page::where('slug', 'contact')->firstOrFail(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get(),
            'places' => Destination::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function package(Package $package)
    {
        abort_unless($package->is_active, 404);
        $package->load(['images', 'destinations']);
        $frames = $package->galleryFrames();
        $summary = trim((string) $package->summary);
        $metaDescription = $summary !== ''
            ? \Illuminate\Support\Str::limit($summary.' Enquire on WhatsApp from Poombarai, Kodaikanal. No payment on this site.', 155)
            : $package->name.' sightseeing package from Poombarai village near Kodaikanal. View photos and enquire on WhatsApp.';

        $page = new Page([
            'meta_title' => $package->name.' from Poombarai | Kodaikanal Sightseeing',
            'meta_description' => $metaDescription,
            'keywords' => implode(', ', array_filter([
                $package->name,
                'Poombarai package',
                'Kodaikanal sightseeing',
                'Poombarai Homestay',
                'Village Stay Kodaikanal',
                'Kodaikanal Car Rental',
            ])),
            'banner_url' => $package->imageSrc() ?: null,
            'banner_alt' => $package->image_alt ?: ($package->name.' from Poombarai, Kodaikanal'),
            'h1' => $package->name,
        ]);

        return view('front.package', [
            'page' => $page,
            'package' => $package,
            'frames' => $frames,
            'related' => Package::where('is_active', true)
                ->where('id', '!=', $package->id)
                ->orderBy('sort_order')
                ->take(4)
                ->get(['id', 'slug', 'name']),
        ]);
    }
}
