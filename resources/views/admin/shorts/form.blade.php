@extends('layouts.admin')
@section('title', $short->exists ? 'Edit short' : 'New short')
@section('content')
  <div class="admin-top"><h1>{{ $short->exists ? 'Edit short' : 'New short' }}</h1><a class="btn line" href="{{ route('admin.shorts.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $short->exists ? route('admin.shorts.update', $short) : route('admin.shorts.store') }}" enctype="multipart/form-data">
    @csrf
    @if($short->exists) @method('PUT') @endif
    <div><label for="title">Title</label><input id="title" name="title" value="{{ old('title', $short->title) }}" required maxlength="80"></div>
    <div><label for="caption">Short line</label><input id="caption" name="caption" value="{{ old('caption', $short->caption) }}" maxlength="240"></div>
    <div class="grid two">
      <div><label for="button_label">Enquiry button</label><input id="button_label" name="button_label" value="{{ old('button_label', $short->button_label ?: 'Enquire on WhatsApp') }}" required maxlength="40"></div>
      <div><label for="sort_order">Order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $short->sort_order ?? 0) }}"></div>
    </div>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $short->exists ? $short->is_active : true))> Show on the homepage</label>
    <div>
      <label for="video">Video (MP4, WebM, or MOV, up to 30 MB)</label>
      @if($short->videoUrl())<p><a href="{{ $short->videoUrl() }}" target="_blank" rel="noopener">Current video</a></p>@endif
      <input id="video" name="video" type="file" accept="video/mp4,video/webm,video/quicktime" @unless($short->exists) required @endunless>
    </div>
    <div>
      <label for="poster">Cover image (optional)</label>
      @if($short->posterUrl())<img class="thumb" src="{{ $short->posterUrl() }}" alt="">@endif
      <input id="poster" name="poster" type="file" accept="image/jpeg,image/png,image/webp">
    </div>
    <button class="btn" type="submit">Save short</button>
  </form>
  @if($short->exists)
    <form method="post" action="{{ route('admin.shorts.destroy', $short) }}" onsubmit="return confirm('Remove this short video?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection
