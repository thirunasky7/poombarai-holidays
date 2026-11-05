@php
  $base = rtrim($site['site_url'] ?: url('/'), '/');
  $path = request()->getPathInfo() ?: '/';
  $canonical = $base.($path === '/' ? '/' : $path);
  $schema = [
      '@context' => 'https://schema.org',
      '@type' => ['LocalBusiness', 'LodgingBusiness'],
      'name' => $site['business_name'],
      'url' => $base.'/',
      'telephone' => $site['phone'],
      'email' => $site['email'],
      'image' => $heroUrl,
      'description' => $page->meta_description,
      'address' => [
          '@type' => 'PostalAddress',
          'streetAddress' => $site['address'],
          'addressLocality' => $site['locality'],
          'addressRegion' => $site['region'],
          'postalCode' => $site['postal_code'],
          'addressCountry' => 'IN',
      ],
      'geo' => [
          '@type' => 'GeoCoordinates',
          'latitude' => $site['geo_lat'],
          'longitude' => $site['geo_lng'],
      ],
      'openingHours' => $site['hours'],
      'priceRange' => $site['price_range'] ?: '₹₹',
      'areaServed' => [
          ['@type' => 'Place', 'name' => 'Poombarai'],
          ['@type' => 'Place', 'name' => 'Kodaikanal'],
      ],
  ];
  $wa = 'https://wa.me/'.preg_replace('/\D+/', '', $site['whatsapp_number']);
  $current = request()->route()?->getName();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $page->meta_title }}</title>
  <meta name="description" content="{{ $page->meta_description }}">
  <meta name="keywords" content="{{ $page->keywords ?: $site['keywords'] }}">
  <link rel="canonical" href="{{ $canonical }}">
  <meta name="geo.region" content="IN-TN">
  <meta name="geo.placename" content="Poombarai, Kodaikanal">
  <meta name="geo.position" content="{{ $site['geo_lat'] }};{{ $site['geo_lng'] }}">
  <meta name="ICBM" content="{{ $site['geo_lat'] }}, {{ $site['geo_lng'] }}">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="{{ $site['business_name'] }}">
  <meta property="og:title" content="{{ $page->meta_title }}">
  <meta property="og:description" content="{{ $page->meta_description }}">
  <meta property="og:url" content="{{ $canonical }}">
  <meta property="og:image" content="{{ $page->bannerUrl() ?: $heroUrl }}">
  <meta property="og:locale" content="en_IN">
  <meta name="twitter:card" content="summary_large_image">
  <link rel="icon" href="{{ $faviconUrl }}" type="{{ $faviconType }}">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link rel="stylesheet" href="https://fonts.bunny.net/css?family=source-sans-3:400,600,700|source-serif-4:600,700&display=swap">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('js/tailwind-config.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <script type="application/ld+json" id="local-business-schema">@json($schema)</script>
  @stack('head')
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>
  <header class="site-header">
    <div class="wrap header-bar">
      <a class="brand" href="{{ route('home') }}">
        @if($logoUrl)
          <img class="brand-logo" src="{{ $logoUrl }}" alt="">
        @else
          <span class="brand-mark" aria-hidden="true">P</span>
        @endif
        <span class="brand-text">
          <span class="brand-name" data-bind="name">{{ $site['business_name'] }}</span>
          <span class="brand-place">Poombarai village</span>
        </span>
      </a>
      <nav class="desktop-nav" aria-label="Primary">
        @foreach($navPages as $nav)
          @php $navRoute = $nav->slug === 'home' ? 'home' : ($nav->slug === 'stays' ? 'rooms' : $nav->slug); @endphp
          <a href="{{ route($navRoute) }}" @if($current === $navRoute) aria-current="page" @endif>{{ $nav->nav_label }}</a>
        @endforeach
      </nav>
      <div class="header-tools">
        <div class="lang-switch desktop-lang" role="group" aria-label="WhatsApp message language">
          <button type="button" data-lang="en" aria-pressed="true">EN</button>
          <button type="button" data-lang="ta" aria-pressed="false">TA</button>
        </div>
        <button type="button" class="btn btn-clay header-book" data-open-enquiry>Book now</button>
        <a class="btn btn-wa header-wa" data-enquiry-type="general" data-item-name="Header WhatsApp" href="{{ $wa }}">WhatsApp</a>
      </div>
      <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu">
        <span class="nav-toggle-bars" aria-hidden="true"></span>
        <span class="nav-toggle-label visually-hidden">Menu</span>
      </button>
    </div>
    <div class="wrap header-book-bar">
      <button type="button" class="btn btn-clay" data-open-enquiry>Book now</button>
    </div>
    <nav id="mobile-nav" class="mobile-nav" hidden>
      @foreach($navPages as $nav)
        @php $navRoute = $nav->slug === 'home' ? 'home' : ($nav->slug === 'stays' ? 'rooms' : $nav->slug); @endphp
        <a href="{{ route($navRoute) }}" @if($current === $navRoute) aria-current="page" @endif>{{ $nav->nav_label }}</a>
      @endforeach
      <div class="mobile-nav-extra">
        <p class="mobile-nav-label">WhatsApp message language</p>
        <div class="lang-switch" role="group" aria-label="WhatsApp message language">
          <button type="button" data-lang="en" aria-pressed="true">English</button>
          <button type="button" data-lang="ta" aria-pressed="false">Tamil</button>
        </div>
        <p class="lang-status" data-lang-status>WhatsApp message: English</p>
        <div class="mobile-nav-actions">
          <button type="button" class="btn btn-clay" data-open-enquiry>Book now</button>
          <a class="btn btn-wa" data-enquiry-type="general" data-item-name="Menu WhatsApp" href="{{ $wa }}">WhatsApp</a>
          <a class="btn btn-clay" data-bind-href="tel" href="tel:{{ preg_replace('/[^\d+]/', '', $site['phone']) }}">Call</a>
        </div>
      </div>
    </nav>
  </header>
  <main id="main">
    @if(session('enquiry'))
      <section class="enquiry-success-band" aria-labelledby="enquiry-saved-heading">
        <div class="wrap enquiry-success">
          <h2 id="enquiry-saved-heading">Enquiry {{ session('enquiry.reference') }} is saved</h2>
          <p>This is not a confirmed booking. @if(session('enquiry.mail_sent')) We also emailed the details. @else Email could not be sent from this server, so use WhatsApp or the phone number if you want a faster reply. @endif WhatsApp opens with the details filled in. The message is sent only after you tap send there.</p>
          <a class="btn btn-wa" href="{{ session('enquiry.whatsapp') }}">Open WhatsApp with this enquiry</a>
        </div>
      </section>
    @endif
    @yield('content')
  </main>
  <dialog id="book-dialog" class="book-dialog" aria-labelledby="book-dialog-heading" @if($errors->any()) data-open-on-load @endif>
    <div class="enquiry-card book-dialog-card">
      <button type="button" class="book-dialog-close" data-close-enquiry aria-label="Close">×</button>
      @include('front.partials.enquiry-form', [
        'prefix' => 'q',
        'heading' => 'Plan your trip',
        'headingId' => 'book-dialog-heading',
        'service' => 'room',
        'destinations' => $enquiryPlaces ?? collect(),
      ])
    </div>
  </dialog>
  <footer class="site-footer">
    <div class="wrap footer-grid">
      <div>
        <p class="footer-name" data-bind="name">{{ $site['business_name'] }}</p>
        <p data-bind="address">{{ $site['address'] }}</p>
        <p data-bind="hours">{{ $site['hours'] }}</p>
      </div>
      <div>
        <h2 class="footer-title">Explore</h2>
        <ul>
          @foreach($navPages as $nav)
            <li><a href="{{ $nav->slug === 'home' ? route('home') : route($nav->slug === 'stays' ? 'rooms' : $nav->slug) }}">{{ $nav->nav_label }}</a></li>
          @endforeach
        </ul>
      </div>
      <div>
        <h2 class="footer-title">Enquire</h2>
        <ul>
          <li><a data-bind-href="tel" data-bind="phone" href="tel:{{ preg_replace('/[^\d+]/', '', $site['phone']) }}">{{ $site['phone'] }}</a></li>
          <li><a data-bind-href="mail" data-bind="email" href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></li>
        </ul>
        <p class="footer-cta"><a class="btn btn-wa" data-enquiry-type="general" data-item-name="Footer WhatsApp" href="{{ $wa }}">WhatsApp</a></p>
      </div>
      <div>
        <h2 class="footer-title">Stay times</h2>
        <p>Check-in from <span data-bind="checkin">{{ $site['check_in'] }}</span></p>
        <p>Check-out by <span data-bind="checkout">{{ $site['check_out'] }}</span></p>
        <p>{{ $site['footer_note'] }}</p>
      </div>
    </div>
    <p class="copyright">© <span data-year>{{ date('Y') }}</span> <span data-bind="name">{{ $site['business_name'] }}</span></p>
  </footer>
  <script>window.SITE_CONFIG = @json($waConfig); window.SITE_DATA = @json($waData);</script>
  <script src="{{ asset('js/whatsapp.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
