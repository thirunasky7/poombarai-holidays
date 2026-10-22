@extends('layouts.admin')
@section('title', $faq->exists ? 'Edit question' : 'New question')
@section('content')
  <div class="admin-top"><h1>{{ $faq->exists ? 'Edit question' : 'New question' }}</h1><a class="btn line" href="{{ route('admin.faqs.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
    @csrf
    @if($faq->exists) @method('PUT') @endif
    <div><label for="question">Question</label><input id="question" name="question" value="{{ old('question', $faq->question) }}" required></div>
    <div><label for="answer">Answer</label><textarea id="answer" name="answer" required>{{ old('answer', $faq->answer) }}</textarea></div>
    <div><label for="sort_order">Sort order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $faq->sort_order ?? 0) }}"></div>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->exists ? $faq->is_active : true))> Visible</label>
    <button class="btn" type="submit">Save question</button>
  </form>
  @if($faq->exists)
    <form method="post" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Remove this question?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection
