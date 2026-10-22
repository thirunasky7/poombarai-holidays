<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Review;
use App\Models\Room;
use App\Models\ShortVideo;
use App\Models\RoomImage;
use App\Models\TourPlan;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'counts' => [
                'Stays' => Room::count(),
                'Stay photos' => RoomImage::count(),
                'Vehicles' => Vehicle::count(),
                'Packages' => Package::count(),
                'Places' => Destination::count(),
                'Tour plans' => TourPlan::count(),
                'Reviews' => Review::count(),
                'FAQs' => Faq::count(),
                'Short videos' => ShortVideo::count(),
            ],
        ]);
    }
}
