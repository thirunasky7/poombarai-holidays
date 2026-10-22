<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Review;
use App\Models\Room;
use App\Models\ShortVideo;
use App\Models\TourPlan;
use App\Models\Vehicle;

class FrontController extends Controller
{
    public function home()
    {
        return view('front.home', [
            'page' => Page::where('slug', 'home')->firstOrFail(),
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'shorts' => ShortVideo::where('is_active', true)->orderBy('sort_order')->get(),
            'rooms' => Room::with('images')->where('is_active', true)->where('featured', true)->orderBy('sort_order')->take(3)->get(),
        ]);
    }

    public function rooms()
    {
        return view('front.rooms', [
            'page' => Page::where('slug', 'stays')->firstOrFail(),
            'rooms' => Room::with('images')->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function cars()
    {
        return view('front.cars', [
            'page' => Page::where('slug', 'cars')->firstOrFail(),
            'vehicles' => Vehicle::where('is_active', true)->orderBy('sort_order')->get(),
            'packages' => Package::with('destinations')->where('is_active', true)->orderBy('sort_order')->get(),
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
        ]);
    }
}
