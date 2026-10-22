@extends('layouts.admin')
@section('title', 'Short videos')
@section('content')
  <div class="admin-top"><h1>Short videos</h1><a class="btn" href="{{ route('admin.shorts.create') }}">Add short</a></div>
  <p>These play in a row under the homepage banner. Each video has an enquiry button that opens WhatsApp.</p>
  <div class="table-wrap"><table>
    <thead><tr><th>Title</th><th>Order</th><th></th></tr></thead>
    <tbody>
      @forelse($shorts as $short)
        <tr>
          <td>{{ $short->title }}@unless($short->is_active) <em>(hidden)</em>@endunless</td>
          <td>{{ $short->sort_order }}</td>
          <td><a href="{{ route('admin.shorts.edit', $short) }}">Edit</a></td>
        </tr>
      @empty
        <tr><td colspan="3">No short videos yet.</td></tr>
      @endforelse
    </tbody>
  </table></div>
@endsection
