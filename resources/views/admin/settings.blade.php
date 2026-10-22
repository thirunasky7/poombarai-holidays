@extends('layouts.admin')
@section('title', 'Site settings')
@section('content')
  <div class="admin-top"><h1>Site settings</h1></div>
  <form class="card grid" method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="grid two">
      <div><label for="business_name">Business name</label><input id="business_name" name="business_name" value="{{ old('business_name', $values['business_name']) }}" required></div>
      <div><label for="whatsapp_number">WhatsApp number (digits only, with 91)</label><input id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $values['whatsapp_number']) }}" required pattern="\d{10,15}"></div>
      <div><label for="phone">Phone shown on the site</label><input id="phone" name="phone" value="{{ old('phone', $values['phone']) }}" required></div>
      <div><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email', $values['email']) }}" required></div>
    </div>
    <div><label for="address">Full address</label><input id="address" name="address" value="{{ old('address', $values['address']) }}" required></div>
    <div class="grid two">
      <div><label for="locality">Village / locality</label><input id="locality" name="locality" value="{{ old('locality', $values['locality']) }}" required></div>
      <div><label for="region">State</label><input id="region" name="region" value="{{ old('region', $values['region']) }}" required></div>
      <div><label for="postal_code">PIN code</label><input id="postal_code" name="postal_code" value="{{ old('postal_code', $values['postal_code']) }}" required></div>
      <div><label for="site_url">Public site URL</label><input id="site_url" name="site_url" type="url" value="{{ old('site_url', $values['site_url']) }}" required></div>
      <div><label for="geo_lat">Latitude</label><input id="geo_lat" name="geo_lat" value="{{ old('geo_lat', $values['geo_lat']) }}" required></div>
      <div><label for="geo_lng">Longitude</label><input id="geo_lng" name="geo_lng" value="{{ old('geo_lng', $values['geo_lng']) }}" required></div>
    </div>
    <div><label for="maps_embed_url">Google Maps embed URL</label><input id="maps_embed_url" name="maps_embed_url" type="url" value="{{ old('maps_embed_url', $values['maps_embed_url']) }}" required></div>
    <div><label for="maps_link">Google Maps link</label><input id="maps_link" name="maps_link" type="url" value="{{ old('maps_link', $values['maps_link']) }}" required></div>
    <div class="grid two">
      <div><label for="hours">Business hours</label><input id="hours" name="hours" value="{{ old('hours', $values['hours']) }}" required></div>
      <div><label for="ga_id">Google Analytics ID</label><input id="ga_id" name="ga_id" value="{{ old('ga_id', $values['ga_id']) }}"></div>
      <div><label for="check_in">Check-in</label><input id="check_in" name="check_in" value="{{ old('check_in', $values['check_in']) }}" required></div>
      <div><label for="check_out">Check-out</label><input id="check_out" name="check_out" value="{{ old('check_out', $values['check_out']) }}" required></div>
    </div>
    <div><label for="keywords">Default SEO keywords</label><input id="keywords" name="keywords" value="{{ old('keywords', $values['keywords']) }}"></div>
    <div><label for="footer_note">Footer note</label><textarea id="footer_note" name="footer_note" required>{{ old('footer_note', $values['footer_note']) }}</textarea></div>
    <div><label for="price_range">Price range label</label><input id="price_range" name="price_range" value="{{ old('price_range', $values['price_range']) }}"></div>
    <div class="grid two">
      <div>
        <label for="logo">Site logo</label>
        @if($logoUrl)<img class="thumb" src="{{ $logoUrl }}" alt="">@else<p>The header currently shows the letter mark.</p>@endif
        <input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp,image/svg+xml">
        @if($logoUrl)<label><input type="checkbox" name="remove_logo" value="1"> Remove logo</label>@endif
      </div>
      <div>
        <label for="favicon">Favicon</label>
        <img class="thumb" src="{{ $faviconUrl }}" alt="">
        <input id="favicon" name="favicon" type="file" accept="image/png,image/x-icon,image/svg+xml,image/webp,.ico">
        @if($values['favicon_path'] ?? '')<label><input type="checkbox" name="remove_favicon" value="1"> Use the default icon</label>@endif
      </div>
    </div>
    <div>
      <label for="hero_image">Homepage hero photo</label>
      @if($heroUrl)<img class="thumb" src="{{ $heroUrl }}" alt="">@endif
      <input id="hero_image" name="hero_image" type="file" accept="image/jpeg,image/png,image/webp">
    </div>
    <div><label for="hero_image_alt">Hero photo description</label><input id="hero_image_alt" name="hero_image_alt" value="{{ old('hero_image_alt', $values['hero_image_alt']) }}" required></div>
    <button class="btn" type="submit">Save settings</button>
  </form>
@endsection
