<?php

namespace App\Support;

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Review;
use App\Models\Room;
use App\Models\Setting;
use App\Models\TourPlan;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Cache;

class SiteSettings
{
    public function all(): array
    {
        $stored = Cache::remember('site.settings', 300, function () {
            return Setting::query()->pluck('value', 'key')->all();
        });

        return array_merge($this->defaults(), $stored);
    }

    public function put(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('site.settings');
    }

    public function heroUrl(): string
    {
        $site = $this->all();

        return Media::url($site['hero_image_path'] ?? null, $site['hero_image_url'] ?? null);
    }

    public function logoUrl(): string
    {
        return Media::url($this->all()['logo_path'] ?? null);
    }

    public function faviconUrl(): string
    {
        $path = $this->all()['favicon_path'] ?? '';

        return $path ? Media::url($path) : asset('favicon.svg');
    }

    public function faviconType(): string
    {
        $path = $this->all()['favicon_path'] ?? '';
        $ext = strtolower(pathinfo($path !== '' ? $path : 'favicon.svg', PATHINFO_EXTENSION));

        return match ($ext) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            'ico' => 'image/x-icon',
            default => 'image/png',
        };
    }

    public function whatsappConfig(): array
    {
        $site = $this->all();

        return [
            'BUSINESS_NAME' => $site['business_name'],
            'WHATSAPP_NUMBER' => preg_replace('/\D+/', '', $site['whatsapp_number']),
            'PHONE' => $site['phone'],
            'EMAIL' => $site['email'],
            'ADDRESS' => $site['address'],
            'GOOGLE_MAPS_EMBED_URL' => $site['maps_embed_url'],
            'MAPS_LINK' => $site['maps_link'],
            'BUSINESS_HOURS' => $site['hours'],
            'CHECK_IN_TIME' => $site['check_in'],
            'CHECK_OUT_TIME' => $site['check_out'],
            'GA_MEASUREMENT_ID' => $site['ga_id'],
            'SITE_URL' => $site['site_url'],
            'LOCALITY' => $site['locality'],
            'REGION' => $site['region'],
            'POSTAL_CODE' => $site['postal_code'],
            'LAT' => $site['geo_lat'],
            'LNG' => $site['geo_lng'],
            'HERO_IMAGE' => $this->heroUrl(),
            'PRICE_RANGE' => $site['price_range'],
        ];
    }

    public function scriptData(): array
    {
        $rooms = Room::with('images')->where('is_active', true)->orderBy('sort_order')->get();
        $vehicles = Vehicle::where('is_active', true)->orderBy('sort_order')->get();
        $packages = Package::with('destinations')->where('is_active', true)->orderBy('sort_order')->get();
        $places = Destination::where('is_active', true)->orderBy('sort_order')->get();
        $plans = TourPlan::with('stops.destination')->where('is_active', true)->orderBy('sort_order')->get();

        return [
            'rooms' => $rooms->map(function (Room $room) {
                return [
                    'id' => $room->slug,
                    'name' => $room->name,
                    'type' => $room->type,
                    'images' => $room->images->map(fn ($image) => [
                        'src' => $image->src(),
                        'alt' => $image->alt,
                    ])->values(),
                ];
            })->values(),
            'vehicles' => $vehicles->map(fn (Vehicle $vehicle) => [
                'id' => $vehicle->slug,
                'name' => $vehicle->name,
                'seats' => $vehicle->seats,
                'ac' => $vehicle->ac,
                'image' => ['src' => $vehicle->imageSrc(), 'alt' => $vehicle->image_alt],
            ])->values(),
            'packages' => $packages->map(fn (Package $package) => [
                'id' => $package->slug,
                'image' => ['src' => $package->imageSrc(), 'alt' => $package->image_alt],
            ])->values(),
            'destinations' => $places->map(fn (Destination $place) => [
                'id' => $place->slug,
                'name' => $place->name,
                'nameTa' => $place->name_ta,
                'image' => ['src' => $place->imageSrc(), 'alt' => $place->image_alt],
            ])->values(),
            'tourPlans' => $plans->map(function (TourPlan $plan) {
                $stops = $plan->stops->filter(fn ($stop) => $stop->destination);
                $days = [];
                foreach ($stops as $stop) {
                    $label = $stop->day_label ?: '';
                    $days[$label][] = $stop->destination->name;
                }
                $dayBlocks = [];
                foreach ($days as $label => $names) {
                    if ($label !== '') {
                        $dayBlocks[] = ['label' => $label, 'places' => array_values($names)];
                    }
                }

                return [
                    'id' => $plan->slug,
                    'title' => $plan->title,
                    'places' => $stops->map(fn ($stop) => $stop->destination->name)->unique()->values(),
                    'days' => $dayBlocks,
                ];
            })->values(),
            'reviews' => Review::where('is_active', true)->orderBy('sort_order')->get()->map(fn (Review $review) => [
                'stars' => $review->stars,
                'quote' => $review->quote,
                'name' => $review->guest_name,
                'detail' => $review->detail,
            ])->values(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get()->map(fn (Faq $faq) => [
                'q' => $faq->question,
                'a' => $faq->answer,
            ])->values(),
        ];
    }

    public function pages()
    {
        return Page::query()->orderBy('sort_order')->get();
    }

    public function defaults(): array
    {
        return [
            'business_name' => 'Mist & Pine Poombarai',
            'whatsapp_number' => '919876543210',
            'phone' => '+91 98765 43210',
            'email' => 'hello@mistandpine.example',
            'address' => 'Poombarai Village, Kodaikanal, Dindigul, Tamil Nadu 624103, India',
            'locality' => 'Poombarai',
            'region' => 'Tamil Nadu',
            'postal_code' => '624103',
            'country' => 'IN',
            'maps_embed_url' => 'https://maps.google.com/maps?q=Poombarai%2C%20Kodaikanal&z=14&output=embed',
            'maps_link' => 'https://www.google.com/maps/search/?api=1&query=Poombarai%20Kodaikanal',
            'hours' => 'Open daily, 7:00 AM – 9:00 PM IST',
            'check_in' => '1:00 PM',
            'check_out' => '11:00 AM',
            'ga_id' => 'G-XXXXXXXXXX',
            'site_url' => 'https://mistandpine.example',
            'hero_image_path' => '',
            'hero_image_url' => 'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=1600&q=70',
            'hero_image_alt' => 'Mist over green terraces in the Palani hills',
            'logo_path' => '',
            'favicon_path' => '',
            'price_range' => '₹₹',
            'geo_lat' => '10.2514',
            'geo_lng' => '77.4086',
            'footer_note' => 'Stays in Poombarai village, with cars and a guide for Kodaikanal. Fares are confirmed on WhatsApp before you travel.',
            'keywords' => 'Poombarai homestay, Poombarai cottage, Kodaikanal Poombarai village stay, car rental Poombarai, sightseeing from Poombarai',
        ];
    }
}
