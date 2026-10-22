<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin') · {{ $site['business_name'] ?? 'Poombarai' }}</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
  <div class="admin-shell">
    <nav class="admin-nav" aria-label="Admin">
      <strong>Superadmin</strong>
      <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>Dashboard</a>
      <a href="{{ route('admin.settings.edit') }}">Site settings</a>
      <a href="{{ route('admin.pages.index') }}">Pages &amp; SEO</a>
      <a href="{{ route('admin.banners.index') }}">Home banners</a>
      <a href="{{ route('admin.shorts.index') }}">Short videos</a>
      <a href="{{ route('admin.rooms.index') }}">Stays &amp; photos</a>
      <a href="{{ route('admin.vehicles.index') }}">Vehicles</a>
      <a href="{{ route('admin.packages.index') }}">Packages</a>
      <a href="{{ route('admin.destinations.index') }}">Places</a>
      <a href="{{ route('admin.plans.index') }}">Tour plans</a>
      <a href="{{ route('admin.reviews.index') }}">Reviews</a>
      <a href="{{ route('admin.faqs.index') }}">FAQs</a>
      <a href="{{ route('home') }}" target="_blank" rel="noopener">View website</a>
      <form method="post" action="{{ route('admin.logout') }}" style="margin-top:1rem">
        @csrf
        <button class="btn line" type="submit">Log out</button>
      </form>
    </nav>
    <main class="admin-main">
      @if(session('status'))<p class="flash">{{ session('status') }}</p>@endif
      @if($errors->any())
        <div class="errors"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
      @endif
      @yield('content')
    </main>
  </div>
</body>
</html>
