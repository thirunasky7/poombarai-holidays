@extends('layouts.admin')
@section('title', 'Tour plans')
@section('content')
  <div class="admin-top"><h1>Tour plans</h1><a class="btn" href="{{ route('admin.plans.create') }}">Add plan</a></div>
  <div class="table-wrap"><table>
    <thead><tr><th>Plan</th><th>Stops</th><th></th></tr></thead>
    <tbody>
      @foreach($plans as $plan)
        <tr><td>{{ $plan->title }}</td><td>{{ $plan->stops_count }}</td><td><a href="{{ route('admin.plans.edit', $plan) }}">Edit</a></td></tr>
      @endforeach
    </tbody>
  </table></div>
@endsection
