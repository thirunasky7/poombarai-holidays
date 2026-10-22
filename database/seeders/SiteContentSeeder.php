<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Review;
use App\Models\Room;
use App\Models\Setting;
use App\Models\TourPlan;
use App\Models\User;
use App\Models\Vehicle;
use App\Support\SiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@poombarai.local'],
            [
                'name' => 'Super Admin',
                'password' => 'Poombarai#Admin1',
                'is_superadmin' => true,
                'email_verified_at' => now(),
            ]
        );

        $settings = app(SiteSettings::class);
        foreach ($settings->defaults() as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('site.settings');

        $this->pages();
        $rooms = $this->rooms();
        $this->vehicles();
        $places = $this->destinations();
        $this->packages($places);
        $this->plans($places);
        $this->reviews();
        $this->faqs();
        $this->banners();

        unset($rooms);
    }

    private function photo(string $id): string
    {
        return 'https://images.unsplash.com/'.$id.'?auto=format&fit=crop&w=1400&q=70';
    }

    private function pages(): void
    {
        $rows = [
            [
                'slug' => 'home',
                'nav_label' => 'Home',
                'meta_title' => 'Poombarai Homestay & Car Rental | Kodaikanal Village Stays',
                'meta_description' => 'Stay in Poombarai village, Kodaikanal. Cottages and homestays, plus a car and tourist guide for the lake, viewpoints and forest road. Enquire on WhatsApp.',
                'keywords' => 'Poombarai homestay, Poombarai cottage Kodaikanal, car rental Poombarai, village stay Palani hills',
                'eyebrow' => 'Poombarai village, Kodaikanal',
                'h1' => 'Stay in the village. See Kodaikanal by car.',
                'intro' => 'We have cottages in Poombarai and a car with a local guide. Send a WhatsApp message. We reply with the price. You do not pay on this website.',
                'banner_url' => $this->photo('photo-1470071459604-3b5ec3a7fe05'),
                'banner_alt' => 'Mist over green hills in the Palani range near Kodaikanal',
                'sort_order' => 1,
            ],
            [
                'slug' => 'stays',
                'nav_label' => 'Stays',
                'meta_title' => 'Cottages & Homestays in Poombarai Village, Kodaikanal',
                'meta_description' => 'Rooms, cottages and homestays in Poombarai, a farming village near Kodaikanal. Check dates and enquire on WhatsApp. No payment on this site.',
                'keywords' => 'Poombarai cottage, Poombarai homestay, stay in Poombarai village, Kodaikanal village accommodation',
                'eyebrow' => 'Room stay',
                'h1' => 'Rooms, cottages and homestays in Poombarai',
                'intro' => 'These stays are in and around Poombarai village, about 18 km from Kodaikanal town. Check-in is from 1:00 PM and check-out is by 11:00 AM. Prices are per night for the guests listed. Open a photo, then send your dates on WhatsApp.',
                'banner_url' => $this->photo('photo-1449158743715-0a90ebb6d2d8'),
                'banner_alt' => 'Wooden cottage among trees, used as a stand-in for a Poombarai stay',
                'sort_order' => 2,
            ],
            [
                'slug' => 'cars',
                'nav_label' => 'Cars & guide',
                'meta_title' => 'Poombarai Car Rental with Tourist Guide | Kodaikanal',
                'meta_description' => 'Hire a sedan, SUV, Innova or tempo from Poombarai village. Local sightseeing, outstation and Madurai transfers, with an optional Tamil and English guide.',
                'keywords' => 'Poombarai car rental, taxi from Poombarai, Kodaikanal tourist guide, sightseeing car Poombarai',
                'eyebrow' => 'Cars and guide',
                'h1' => 'A car from Poombarai, with a driver who knows the ghats',
                'intro' => 'Pickup is usually at your Poombarai stay. We also collect guests from Kodaikanal town, Madurai airport and Madurai Junction. Fares below are sedan-style starting estimates. The WhatsApp reply is the fare that counts.',
                'banner_url' => $this->photo('photo-1469474968028-56623f02e42e'),
                'banner_alt' => 'Sunlit path through green hills',
                'sort_order' => 3,
            ],
            [
                'slug' => 'places',
                'nav_label' => 'Places',
                'meta_title' => 'Places to Visit from Poombarai, Kodaikanal',
                'meta_description' => 'Poombarai village, the terraced farms and Murugan temple, plus Kodai Lake, Coaker\'s Walk, Pillar Rocks, Berijam Lake and other sights a short drive away.',
                'keywords' => 'Poombarai tourist places, things to do in Poombarai, Kodaikanal sightseeing from Poombarai',
                'eyebrow' => 'From the village',
                'h1' => 'Poombarai and the sights around Kodaikanal',
                'intro' => 'Start in the village, then drive out to the lake and the viewpoints. Guna Caves is a viewpoint only. Berijam Lake needs an official forest permit. Tick the places you want and send them on WhatsApp.',
                'banner_url' => $this->photo('photo-1506905925346-21bda4d32df4'),
                'banner_alt' => 'Mountain ridge above a valley',
                'sort_order' => 4,
            ],
            [
                'slug' => 'contact',
                'nav_label' => 'Contact',
                'meta_title' => 'Contact a Poombarai Homestay | WhatsApp Kodaikanal',
                'meta_description' => 'WhatsApp, call or email Mist & Pine in Poombarai village, Kodaikanal. Ask about cottages, homestays and a sightseeing car. We reply during posted hours.',
                'keywords' => 'Poombarai homestay contact, WhatsApp Kodaikanal stay, Poombarai village phone',
                'eyebrow' => 'Contact',
                'h1' => 'Write, call, or send a note',
                'intro' => 'The fastest reply is WhatsApp. Calls and email work too. We answer during the hours below from Poombarai village.',
                'banner_url' => $this->photo('photo-1511497584788-876760111969'),
                'banner_alt' => 'Tall pine trees in a quiet forest',
                'sort_order' => 5,
            ],
        ];

        foreach ($rows as $row) {
            Page::query()->updateOrCreate(['slug' => $row['slug']], $row);
        }
    }

    private function rooms(): void
    {
        $rows = [
            ['poombarai-valley-cottage', 'Poombarai Valley Cottage', 'Cottage', '₹6,500 / night', 4, 'Poombarai village', 'A detached cottage with a sit-out above the terraces. On clear mornings you look across the valley toward the plains. Best for a family or two couples.', ['Wi-Fi', 'Parking', 'Hot water', 'Heater', 'Mountain view', 'Kitchenette'], true, ['photo-1470770841072-f978cf4d019e' => 'House beside still water at dusk, used as a stand-in photo', 'photo-1600210492486-724fe5c67fb0' => 'Bright living room with a sofa and daylight', 'photo-1616594039964-ae9021a400a0' => 'Quiet bedroom with white linens and warm light']],
            ['poombarai-homestay', 'Poombarai Family Homestay', 'Homestay', '₹3,200 / night', 3, 'Poombarai village centre', 'A family home in the village, with breakfast at the table. The hosts live here and can point you to the temple path and the shorter terrace walks.', ['Wi-Fi', 'Parking', 'Breakfast', 'Hot water', 'Heater', 'Caretaker'], true, ['photo-1518780664697-55e3ad937233' => 'Wooden house surrounded by trees', 'photo-1502672260266-1c1ef2d93688' => 'Sunlit living room with a sofa and plants', 'photo-1414235077428-338989a2e8c0' => 'Breakfast table set with plates and coffee']],
            ['terrace-room', 'Terrace View Room', 'Room', '₹2,800 / night', 2, 'Poombarai, above the fields', 'A double room for two, with a kettle and a window over the vegetable terraces. An easy base if you want a quiet village night and a car into Kodaikanal after breakfast.', ['Wi-Fi', 'Hot water', 'Heater', 'Mountain view'], true, ['photo-1611892440504-42a792e24d32' => 'Bedroom with a made bed and side tables', 'photo-1590490360182-c33d57733427' => 'White bed with cushions by a window', 'photo-1582719478250-c89cae4dc85b' => 'Tidy bedroom with cool-toned linens']],
            ['family-cottage', 'Poombarai Family Cottage', 'Cottage', '₹8,400 / night', 6, 'Poombarai lane', 'Two bedrooms and a small kitchen for a bigger family. The lane is narrow, so a sedan is easier than a tempo at the door. Tell us your vehicle when you enquire.', ['Wi-Fi', 'Parking', 'Kitchenette', 'Hot water', 'Heater', 'Fireplace'], false, ['photo-1449158743715-0a90ebb6d2d8' => 'Wood cabin with a porch among green trees', 'photo-1600607687939-ce8a6c25118c' => 'Living room with seating', 'photo-1556912173-46c336c7fd55' => 'Kitchen with wooden cabinets']],
            ['town-homestay', 'Kodaikanal Town Homestay', 'Homestay', '₹3,800 / night', 4, 'Kodaikanal town, near Bryant Park', 'Two rooms that share a garden sit-out, if you would rather sleep in town and visit Poombarai as a day trip. Breakfast is included.', ['Wi-Fi', 'Parking', 'Breakfast', 'Hot water', 'Heater', 'Caretaker'], false, ['photo-1564013799919-ab600027ffc6' => 'House with a lawn and large windows', 'photo-1522708323590-d24dbb6b0267' => 'Airy bedroom with a window', 'photo-1416879595882-3373a0480b5b' => 'Garden bed with green leaves']],
            ['ghat-suite', 'Ghat Road Suite', 'Room', '₹4,200 / night', 3, 'Between the ghat and Poombarai', 'A larger room with space for a child on a rollaway. Useful if you arrive up the ghat late and want an easy pickup point the next morning.', ['Wi-Fi', 'Parking', 'Breakfast', 'Hot water', 'Heater', 'TV', 'Mountain view'], false, ['photo-1578683010236-d716f9a3f461' => 'Large bedroom with layered bedding', 'photo-1560185007-cde436f6a4d0' => 'Minimal bedroom with white walls', 'photo-1596394516093-501ba68a0ba6' => 'Bedroom with a lamp and neutral linens']],
        ];

        foreach ($rows as $i => [$slug, $name, $type, $price, $occupancy, $location, $summary, $amenities, $featured, $images]) {
            $room = Room::query()->updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'type' => $type,
                'price_label' => $price,
                'occupancy' => $occupancy,
                'location' => $location,
                'summary' => $summary,
                'amenities' => $amenities,
                'featured' => $featured,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
            $room->images()->delete();
            $n = 0;
            foreach ($images as $id => $alt) {
                $room->images()->create([
                    'url' => $this->photo($id),
                    'alt' => $alt,
                    'sort_order' => $n++,
                ]);
            }
        }
    }

    private function vehicles(): void
    {
        $rows = [
            ['sedan', 'Sedan', 'Dzire or similar', 4, '₹2,400 for 8 hrs / 80 km', 'The easiest car for Poombarai lanes and town viewpoints. Comfortable for two or three people with luggage.', '₹2,400', '8 hours / 80 km', '₹14', '₹3,200 / day', 'photo-1549317661-bd32c8ce0db2', 'White sedan on a wet road, stand-in photo'],
            ['suv', 'SUV', 'Scorpio, XUV or similar', 6, '₹3,400 for 8 hrs / 80 km', 'More ground clearance for the ghat and the village road, with room for a family and bags.', '₹3,400', '8 hours / 80 km', '₹18', '₹4,500 / day', 'photo-1519641471654-76ce0107ad1b', 'Dark SUV on a hillside track, stand-in photo'],
            ['innova', 'Innova', 'Innova Crysta or similar', 6, '₹3,800 for 8 hrs / 80 km', 'A smoother ride if you want a quieter day from Poombarai into Kodaikanal. Six passengers, with space for luggage.', '₹3,800', '8 hours / 80 km', '₹18', '₹5,000 / day', 'photo-1533473359331-0135ef1b58bf', 'White SUV parked among trees, stand-in photo'],
            ['tempo', 'Tempo Traveller', '12-seater, AC', 12, '₹6,500 for 8 hrs / 80 km', 'For bigger groups. Pickup in Kodaikanal town is easier than a narrow Poombarai lane. Tell us your headcount when you enquire.', '₹6,500', '8 hours / 80 km', '₹28', '₹8,500 / day', 'photo-1464219789935-c2d9d9aba644', 'Van parked on an open road at dusk, stand-in photo'],
        ];

        foreach ($rows as $i => [$slug, $name, $example, $seats, $price, $blurb, $local, $detail, $extra, $out, $photo, $alt]) {
            Vehicle::query()->updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'example' => $example,
                'seats' => $seats,
                'ac' => true,
                'price_label' => $price,
                'blurb' => $blurb,
                'rates' => [
                    ['label' => 'Local hire', 'value' => $local, 'detail' => $detail],
                    ['label' => 'Extra kilometre', 'value' => $extra],
                    ['label' => 'Outstation', 'value' => $out],
                ],
                'image_url' => $this->photo($photo),
                'image_alt' => $alt,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function destinations(): array
    {
        $rows = [
            ['poombarai', 'Poombarai Village', 'பூம்பாறை', true, 'Early morning, when the terraces are quiet', '2–3 hours in the village', 'A farming village in the Palani hills, about 18 km from Kodaikanal town. Walk the terraced fields and visit the hilltop Murugan temple. This is the home base for our stays. The lanes are narrow, so a small car is easier than a tempo.', 'photo-1470071459604-3b5ec3a7fe05', 'Mist over green hills, a stand-in for Poombarai terraces'],
            ['coakers-walk', "Coaker's Walk", 'கோக்கர்ஸ் வாக்', true, 'Early morning, before the mist lifts', '45–60 minutes', 'A paved walk of about a kilometre on the edge of Kodaikanal town. On a clear day you look down to the plains. Many mornings it sits in cloud. About a 45-minute drive from Poombarai.', 'photo-1469474968028-56623f02e42e', 'Sunlit path through green hills'],
            ['pillar-rocks', 'Pillar Rocks', 'பிள்ளார் பாறை', true, 'Morning, for a better chance of a clear view', '60–90 minutes', 'Three tall rock columns near a fenced viewpoint, plus a short walk toward the gorge. The road from Poombarai takes under an hour on a normal day.', 'photo-1506905925346-21bda4d32df4', 'Mountain ridge above a valley'],
            ['kodai-lake', 'Kodai Lake', 'கொடைக்கானல் ஏரி', true, 'Morning, or late afternoon', '1–2 hours', 'The star-shaped lake in Kodaikanal town. People walk the path, hire a boat, or ride a cycle. It is the usual first stop when you drive in from Poombarai.', 'photo-1476514525535-07fb3b4ae5f1', 'Calm lake with hills reflected in the water'],
            ['bryant-park', 'Bryant Park', 'பிரையன்ட் பூங்கா', false, 'When the flowers are open, late morning', '45 minutes', 'A planted garden beside the lake path. Flower beds change through the year. Pair it with the lake rather than making it a whole outing from the village.', 'photo-1490750967868-88aa4486c946', 'Colourful flower bed in a garden'],
            ['guna-caves', 'Guna Caves viewpoint', 'குனா குகை', true, 'Morning, and only from the viewpoint', '30–45 minutes', 'The cave itself is closed to visitors. You can look from the official viewpoint only. Do not climb the fence or the rocks. The drop is real, and this is not a cave tour.', 'photo-1520962880247-cfaf541c8724', 'Rocky cliff edge above a drop'],
            ['pine-forest', 'Pine Forest', 'பைன் காடு', true, 'Late afternoon, when the light is soft', '45–60 minutes', 'A quiet stretch of pine trees on the way toward Berijam. Good for a short walk if you already have the car for the forest road.', 'photo-1448375240586-882707db888b', 'Tall pine trunks with sunlight between them'],
            ['berijam', 'Berijam Lake', 'பெரியம் ஏரி', true, 'Early, and only with a permit', 'Half day including the drive', 'A forest lake past a checkpoint. Entry needs an official permit, and numbers are limited. We can ask about a permit when you book the car. Do not plan on walking in without one.', 'photo-1439066615861-d1af74d74000', 'Still lake surrounded by forest'],
            ['silver-cascade', 'Silver Cascade', 'சில்வர் கேஸ்கேட்', false, 'After rain, when the fall is fuller', '20–30 minutes', 'A roadside waterfall on the ghat below Kodaikanal. You see it from the road. It is a stop on the way up or down, not a long walk from Poombarai.', 'photo-1432405972618-c60b0225b8f9', 'Water falling over dark rocks'],
            ['dolphins-nose', "Dolphin's Nose", 'டால்பின் நோஸ்', false, 'Morning, if the path is open', '2 hours including the walk', 'A viewpoint reached by a walk through woods. The path can be steep and slippery. Wear shoes with a grip, and skip it if you are not comfortable on uneven ground.', 'photo-1426604966848-d7adac402bff', 'Forest path leading toward a viewpoint'],
            ['bear-shola', 'Bear Shola Falls', 'பேர் சோலா நீர்வீழ்ச்சி', false, 'Monsoon and just after, when water is running', '45–60 minutes', 'A short forest walk to a small fall near town. It is modest in the dry months. Easy to add after the lake if you still have time before the drive back to Poombarai.', 'photo-1472214103451-9374bd1c798e', 'Small waterfall in green forest'],
        ];

        $models = [];
        foreach ($rows as $i => $row) {
            [$slug, $name, $ta, $popular, $best, $time, $description, $photo, $alt] = $row;
            $models[$slug] = Destination::query()->updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'name_ta' => $ta,
                'popular' => $popular,
                'best_time' => $best,
                'time_needed' => $time,
                'description' => $description,
                'image_url' => $this->photo($photo),
                'image_alt' => $alt,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }

        return $models;
    }

    private function packages(array $places): void
    {
        $rows = [
            ['village-morning', 'Poombarai Village Morning', '4 hours', '₹1,800', 'From ₹1,800 per car', 'A slow start in the village: terraces, the Murugan temple path, and a viewpoint stop if the mist has lifted. Sedan estimate for up to 4 guests.', ['poombarai', 'coakers-walk'], true, 'photo-1501785888041-af3ef285b470', 'Green valley seen from a high path'],
            ['lake-town', 'Lake and Town Half Day', '4 hours', '₹1,800', 'From ₹1,800 per car', 'Drive from Poombarai to Kodai Lake, walk the path, and stop at Bryant Park if you want flowers. Back in the village for a late lunch.', ['kodai-lake', 'bryant-park', 'coakers-walk'], true, 'photo-1476514525535-07fb3b4ae5f1', 'Lake and hills'],
            ['viewpoint-day', 'Viewpoint Day', '8 hours', '₹2,800', 'From ₹2,800 per car', 'Pillar Rocks, Guna Caves viewpoint only, and Coaker\'s Walk. We do not enter the closed cave.', ['pillar-rocks', 'guna-caves', 'coakers-walk', 'poombarai'], true, 'photo-1464822759023-fed622ff2c3b', 'Sharp mountain peaks in clear weather'],
            ['waterfall-morning', 'Waterfall Morning', '5 hours', '₹2,200', 'From ₹2,200 per car', 'Bear Shola if the path is open, then Silver Cascade on the way back toward the ghat.', ['bear-shola', 'silver-cascade'], false, 'photo-1433086966358-54859d0ed716', 'Water moving over rocks in a green gorge'],
            ['berijam-pines', 'Berijam and Pine Forest', '8 hours', '₹3,600', 'From ₹3,600 per car, permit extra', 'Pine Forest and Berijam Lake. The forest permit is extra and is not guaranteed. We check before you leave Poombarai.', ['pine-forest', 'berijam'], false, 'photo-1511497584788-876760111969', 'Pine trees in a quiet forest'],
            ['two-day-hills', 'Two Days from the Village', '2 days', '₹6,400', 'From ₹6,400 for the car, stays extra', 'Day one in town: lake, park and Coaker\'s Walk. Day two toward the forest road if a permit is possible, plus a village walk.', ['poombarai', 'kodai-lake', 'bryant-park', 'coakers-walk', 'pillar-rocks', 'pine-forest'], false, 'photo-1441974231531-c6227db76b6e', 'Sun through a green forest canopy'],
        ];

        foreach ($rows as $i => [$slug, $name, $duration, $fare, $price, $summary, $placeSlugs, $featured, $photo, $alt]) {
            $package = Package::query()->updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'duration' => $duration,
                'fare' => $fare,
                'price_label' => $price,
                'summary' => $summary,
                'featured' => $featured,
                'image_url' => $this->photo($photo),
                'image_alt' => $alt,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
            $package->destinations()->sync(collect($placeSlugs)->map(fn ($key) => $places[$key]->id)->all());
        }
    }

    private function plans(array $places): void
    {
        $one = TourPlan::query()->updateOrCreate(['slug' => 'one-day-poombarai'], [
            'title' => '1-day from Poombarai',
            'duration' => '1 day',
            'summary' => 'Village morning, then the lake, Coaker\'s Walk, Pillar Rocks and the Guna Caves viewpoint. Back to Poombarai before dark.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $two = TourPlan::query()->updateOrCreate(['slug' => 'two-day-hills'], [
            'title' => '2-day hills and forest',
            'duration' => '2 days',
            'summary' => 'Day 1 stays closer to town. Day 2 tries the pine forest and Berijam if a permit is available, and ends with a village walk.',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $one->stops()->delete();
        foreach (['poombarai', 'kodai-lake', 'coakers-walk', 'pillar-rocks', 'guna-caves', 'bryant-park'] as $i => $slug) {
            $one->stops()->create(['destination_id' => $places[$slug]->id, 'day_label' => null, 'sort_order' => $i]);
        }

        $two->stops()->delete();
        $dayOne = ['kodai-lake', 'bryant-park', 'coakers-walk', 'pillar-rocks'];
        $dayTwo = ['pine-forest', 'berijam', 'poombarai'];
        $order = 0;
        foreach ($dayOne as $slug) {
            $two->stops()->create(['destination_id' => $places[$slug]->id, 'day_label' => 'Day 1', 'sort_order' => $order++]);
        }
        foreach ($dayTwo as $slug) {
            $two->stops()->create(['destination_id' => $places[$slug]->id, 'day_label' => 'Day 2', 'sort_order' => $order++]);
        }
    }

    private function reviews(): void
    {
        $rows = [
            [5, 'The cottage was quiet and the morning over the terraces was the reason we stayed a second night.', 'Sample guest · Anitha', 'Couple, 2 nights in Poombarai'],
            [5, 'The driver knew which lane a sedan could manage, and we still reached the lake before the crowd.', 'Sample guest · Karthik', 'Family of 4, sedan day'],
            [4, 'We liked sleeping in the village and driving into town only for the viewpoints. The mist hid Coaker\'s Walk, which they had warned us about.', 'Sample guest · Meera', 'Two friends, weekend'],
            [5, 'Breakfast at the homestay was simple and hot. They helped us ask about the Berijam permit the evening before.', 'Sample guest · Joseph', 'Family, homestay'],
            [4, 'Clear note that Guna Caves is only a viewpoint. We did not try to go further, and the rest of the day was easy.', 'Sample guest · Divya', 'Couple, viewpoint day'],
        ];

        foreach ($rows as $i => [$stars, $quote, $name, $detail]) {
            Review::query()->updateOrCreate(['guest_name' => $name], [
                'stars' => $stars,
                'quote' => $quote,
                'detail' => $detail,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function faqs(): void
    {
        $rows = [
            ['Is the stay in Poombarai or in Kodaikanal town?', 'Most cottages and the family homestay are in Poombarai village, about 18 km from Kodaikanal town. One homestay is in town if you prefer that. The car page covers both pickups.'],
            ['How do I book?', 'Send your dates, group size and the stay or car you want on WhatsApp. We reply with availability and a fare. Nothing is booked until you confirm that reply.'],
            ['Can I pay on this website?', 'No. This site does not take payment or card details. You confirm the fare on WhatsApp and pay the host as agreed.'],
            ['What are the stay times?', 'Check-in is from 1:00 PM and check-out is by 11:00 AM. Bring a photo ID. Early arrivals can leave bags if the room is not ready.'],
            ['Do you pick up from Madurai?', 'Yes. Airport and railway station transfers are separate from a local sightseeing day. Tell us the flight or train time so we can quote the right car.'],
            ['Is the guide included?', 'A local guide who speaks Tamil and English can come with the car. Say yes or no on the form. The guide charge is confirmed with the fare.'],
            ['Can we visit Berijam Lake?', 'Only with an official forest permit, and only if permits are still available that day. We will not plan a walk past the checkpoint without one.'],
            ['Is Guna Caves open?', 'No. The cave is closed. You can stop at the official viewpoint only. Do not cross the fence.'],
            ['How cold does Poombarai get?', 'Evenings are cool through the year, and winter nights need a heater or an extra blanket. We keep heaters in the stays listed here.'],
        ];

        foreach ($rows as $i => [$question, $answer]) {
            Faq::query()->updateOrCreate(['question' => $question], [
                'answer' => $answer,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }

    private function banners(): void
    {
        if (Banner::query()->exists()) {
            return;
        }

        $rows = [
            ['Stay in Poombarai', 'Cottages and homestays in the village.', 'See stays', '/stays', 'photo-1449158743715-0a90ebb6d2d8', 'Wooden cottage among trees'],
            ['A car and a guide', 'See the lake and viewpoints from the village.', 'See cars', '/cars', 'photo-1469474968028-56623f02e42e', 'Sunlit path through green hills'],
            ['Places nearby', 'The village, Kodai Lake, and the viewpoints.', 'See places', '/places', 'photo-1506905925346-21bda4d32df4', 'Mountain ridge above a valley'],
        ];

        foreach ($rows as $i => [$title, $text, $label, $url, $photo, $alt]) {
            Banner::query()->create([
                'title' => $title,
                'text' => $text,
                'button_label' => $label,
                'button_url' => $url,
                'image_url' => $this->photo($photo),
                'image_alt' => $alt,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
