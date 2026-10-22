# Mist & Pine Poombarai

A Laravel site for stays and car-with-guide trips based in Poombarai village, Kodaikanal. Guests enquire on WhatsApp. Nothing is booked or paid on the site.

The public pages are server-rendered so place names, stays and fares are in the HTML for search engines. A superadmin panel edits the copy, photos, packages and business details.

## Run it locally

Requirements: PHP 8.1, Composer, and MySQL. The database name is `ragav_booking`.

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open http://127.0.0.1:8000

Create the MySQL database `ragav_booking` if it does not exist, then run the commands above. `.env` uses host `127.0.0.1`, port `3306`, user `root`, and an empty password.

On a live server, point the domain at the `public` folder. Set `APP_URL` and the public site URL in the admin settings to your real domain so canonical links and the sitemap match.

## Superadmin

- URL: http://127.0.0.1:8000/admin/login
- Email: `admin@poombarai.local`
- Password: `Poombarai#Admin1`

Change that password after the first login. There is no public registration. Only an account with superadmin access can open the panel.

From the panel you can edit:

- Business name, WhatsApp number (digits only, with 91), phone, email, Poombarai address, map, hours, and the homepage photo
- Page titles, descriptions, headings and banner photos
- Stays, with more than one photo per stay
- Vehicles, sightseeing packages, places, tour plans, sample reviews and FAQs

Uploaded photos go to `storage/app/public` and are served from `public/storage`. JPEG, PNG and WebP, up to 4 MB.

## Poombarai details to check before launch

The seeded business is a placeholder:

- Name: Mist & Pine Poombarai
- Address: Poombarai Village, Kodaikanal, Dindigul, Tamil Nadu 624103
- Map pin: about 10.2514, 77.4086
- WhatsApp: `919876543210` — replace this in Site settings. Do not message the placeholder number.
- Site URL: `https://mistandpine.example`
- Analytics: `G-XXXXXXXXXX`

Guest notes are labelled “Sample guest”. Replace them with real reviews or hide them. Fares are estimates until you confirm them on WhatsApp. Guna Caves stays a viewpoint only, and Berijam Lake stays permit-only.

## WhatsApp checklist

- Stay form rejects an empty name, a bad mobile number, a past date, and a check-out on or before check-in.
- Car form asks for a drop on outstation and transfer trips, and for at least one place on a local sightseeing trip.
- Passenger count cannot be higher than the vehicle seats.
- English and Tamil message buttons change the WhatsApp text, not the page language.
- If the browser blocks the new tab, the page shows “Tap here to open WhatsApp”.
- On a phone, the round WhatsApp button sits in the bottom bar beside WhatsApp and Call.
