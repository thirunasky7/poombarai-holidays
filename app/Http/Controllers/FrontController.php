<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Room;
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

    public function rooms()
    {
        return view('front.rooms', [
            'page' => Page::where('slug', 'stays')->firstOrFail(),
            'rooms' => Room::with('images')->where('is_active', true)->orderBy('sort_order')->get(),
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
        $package->load('images');
        $frames = $package->galleryFrames();

        $page = new Page([
            'meta_title' => $package->name.' | Poombarai package',
            'meta_description' => 'Photos of '.$package->name.' in Poombarai, Kodaikanal. Enquire on WhatsApp. Nothing is booked or paid here.',
            'keywords' => $package->name.', Poombarai package, Kodaikanal Homestay, Village Stay Kodaikanal',
            'banner_url' => $package->imageSrc() ?: null,
            'banner_alt' => $package->image_alt,
            'h1' => $package->name,
        ]);

        return view('front.package', [
            'page' => $page,
            'package' => $package,
            'frames' => $frames,
        ]);
    }
}
