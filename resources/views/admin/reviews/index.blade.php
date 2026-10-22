@extends('layouts.admin')
@section('title', 'Reviews')
@section('content')
  <div class="admin-top"><h1>Reviews</h1><a class="btn" href="{{ route('admin.reviews.create') }}">Add review</a></div>
  <div class="table-wrap"><table>
    <thead><tr><th>Guest</th><th>Stars</th><th></th></tr></thead>
    <tbody>
      @foreach($reviews as $review)
        <tr><td>{{ $review->guest_name }}</td><td>{{ $review->stars }}</td><td><a href="{{ route('admin.reviews.edit', $review) }}">Edit</a></td></tr>
      @endforeach
    </tbody>
  </table></div>
@endsection
