@extends('layouts.admin')
@section('title', $enquiry->reference)
@section('content')
  <div class="admin-top"><h1>{{ $enquiry->reference }}</h1><a class="btn line" href="{{ route('admin.enquiries.index') }}">All enquiries</a></div>
  <article class="card">
    <p><strong>{{ $enquiry->name }}</strong> · {{ $enquiry->phone }}@if($enquiry->email) · {{ $enquiry->email }}@endif</p>
    <p>{{ ucfirst($enquiry->service_type) }}@if($enquiry->service_name) — {{ $enquiry->service_name }}@endif</p>
    <p>Destination: {{ $enquiry->destination ?: 'Not specified' }}</p>
    <p>Travel date: {{ $enquiry->travel_date ? $enquiry->travel_date->format('j M Y') : 'Not specified' }}</p>
    <p>Guests: {{ $enquiry->adults }} adults, {{ $enquiry->children }} children</p>
    <p>Preferred reply: {{ ucfirst($enquiry->contact_method) }}</p>
    @if($enquiry->requirements)<p>{{ $enquiry->requirements }}</p>@endif
    <p>Received {{ $enquiry->created_at->format('j M Y, g:i a') }}</p>
  </article>
  <form class="card" method="post" action="{{ route('admin.enquiries.update', $enquiry) }}" style="margin-top:1rem">
    @csrf
    @method('PUT')
    <div>
      <label for="status">Status</label>
      <select id="status" name="status">
        @foreach(['new', 'pending', 'confirmed', 'closed'] as $status)
          <option value="{{ $status }}" @selected(old('status', $enquiry->status) === $status)>{{ ucfirst($status) }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label for="internal_note">Internal note</label>
      <textarea id="internal_note" name="internal_note">{{ old('internal_note', $enquiry->internal_note) }}</textarea>
    </div>
    <button class="btn" type="submit">Save</button>
  </form>
@endsection
