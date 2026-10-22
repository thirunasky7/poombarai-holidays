@extends('layouts.admin')
@section('title', 'Edit page')
@section('content')
  <div class="admin-top"><h1>Edit {{ $page->nav_label }}</h1><a class="btn line" href="{{ route('admin.pages.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div><label for="nav_label">Menu label</label><input id="nav_label" name="nav_label" value="{{ old('nav_label', $page->nav_label) }}" required></div>
    <div><label for="meta_title">SEO title</label><input id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" required></div>
    <div><label for="meta_description">SEO description</label><textarea id="meta_description" name="meta_description" required>{{ old('meta_description', $page->meta_description) }}</textarea></div>
    <div><label for="keywords">Keywords</label><input id="keywords" name="keywords" value="{{ old('keywords', $page->keywords) }}"></div>
    <div><label for="eyebrow">Eyebrow</label><input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}"></div>
    <div><label for="h1">Heading</label><input id="h1" name="h1" value="{{ old('h1', $page->h1) }}" required></div>
    <div><label for="intro">Intro</label><textarea id="intro" name="intro" required>{{ old('intro', $page->intro) }}</textarea></div>
    <div>
      <label for="banner">Banner photo</label>
      @if($page->bannerUrl())<img class="thumb" src="{{ $page->bannerUrl() }}" alt="">@endif
      <input id="banner" name="banner" type="file" accept="image/jpeg,image/png,image/webp">
    </div>
    <div><label for="banner_alt">Banner description</label><input id="banner_alt" name="banner_alt" value="{{ old('banner_alt', $page->banner_alt) }}"></div>
    <button class="btn" type="submit">Save page</button>
  </form>
@endsection
