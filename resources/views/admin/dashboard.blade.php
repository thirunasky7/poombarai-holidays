@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
  <div class="admin-top"><h1>Dashboard</h1></div>
  <div class="counts">
    @foreach($counts as $label => $count)
      <article class="count"><span>{{ $count }}</span>{{ $label }}</article>
    @endforeach
  </div>
  <p>Change the WhatsApp number, address and hero photo in site settings before you share the site. Upload your own photos on each stay, vehicle, package and place.</p>
@endsection
