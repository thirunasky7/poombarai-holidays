@extends('layouts.admin')
@section('title', $plan->exists ? 'Edit plan' : 'New plan')
@section('content')
  @php
    $stops = $plan->exists ? $plan->stops : collect();
  @endphp
  <div class="admin-top"><h1>{{ $plan->exists ? 'Edit plan' : 'New plan' }}</h1><a class="btn line" href="{{ route('admin.plans.index') }}">Back</a></div>
  <form class="card grid" method="post" action="{{ $plan->exists ? route('admin.plans.update', $plan) : route('admin.plans.store') }}">
    @csrf
    @if($plan->exists) @method('PUT') @endif
    <div class="grid two">
      <div><label for="title">Title</label><input id="title" name="title" value="{{ old('title', $plan->title) }}" required></div>
      <div><label for="duration">Duration</label><input id="duration" name="duration" value="{{ old('duration', $plan->duration) }}" required></div>
      <div><label for="sort_order">Sort order</label><input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"></div>
    </div>
    <div><label for="summary">Summary</label><textarea id="summary" name="summary" required>{{ old('summary', $plan->summary) }}</textarea></div>
    <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->exists ? $plan->is_active : true))> Visible</label>
    <fieldset>
      <legend>Stops, in order</legend>
      @for($i = 0; $i < 8; $i++)
        @php $stop = $stops[$i] ?? null; @endphp
        <div class="grid two" style="margin-bottom:0.6rem">
          <select name="stops[{{ $i }}][destination_id]" aria-label="Stop {{ $i + 1 }}">
            <option value="">—</option>
            @foreach($destinations as $place)
              <option value="{{ $place->id }}" @selected((string) old('stops.'.$i.'.destination_id', $stop->destination_id ?? '') === (string) $place->id)>{{ $place->name }}</option>
            @endforeach
          </select>
          <input name="stops[{{ $i }}][day_label]" value="{{ old('stops.'.$i.'.day_label', $stop->day_label ?? '') }}" placeholder="Day label, or leave blank">
        </div>
      @endfor
    </fieldset>
    <button class="btn" type="submit">Save plan</button>
  </form>
  @if($plan->exists)
    <form method="post" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Remove this plan?')" style="margin-top:1rem">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form>
  @endif
@endsection
