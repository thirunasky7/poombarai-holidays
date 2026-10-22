@extends('layouts.admin')
@section('title', $review->exists ? 'Edit review' : 'New review')
@section('content')
  <div class="admin-top"><h1>{{ $review->exists ? 'Edit review' : 'New review' }}</h1><a class="btn line" href="{{ route('admin.reviews.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $review->exists ? route('admin.reviews.update', $review) : route('admin.reviews.store') }}">
    @csrf
    @if($review->exists) @method('PUT') @endif
    <div class="grid two">
      <div><label for="guest_name">Name</label><input id="guest_name" name="guest_name" value="{{ old('guest_name', $review->guest_name) }}" required></div>
      <div><label for="detail">Detail</label><input id="detail" name="detail" value="{{ old('detail', $review->detail) }}"></div>
      <div><label for="stars">Stars</label><input id="stars" name="stars" type="number" min="1" max="5" value="{{ old('stars', $review->stars) }}" required></div>
      <div><label for="sort_order">Sort order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $review->sort_order ?? 0) }}"></div>
    </div>
    <div><label for="quote">Quote</label><textarea id="quote" name="quote" required>{{ old('quote', $review->quote) }}</textarea></div>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $review->exists ? $review->is_active : true))> Visible. Keep “Sample guest” in the name until this is a real review.</label>
    <button class="btn" type="submit">Save review</button>
  </form>
  @if($review->exists)
    <form method="post" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Remove this review?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection
