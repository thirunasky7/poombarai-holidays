@extends('layouts.admin')
@section('title', 'Enquiries')
@section('content')
  <div class="admin-top"><h1>Enquiries</h1></div>
  <div class="table-wrap"><table>
    <thead>
      <tr><th>Reference</th><th>Guest</th><th>Service</th><th>Status</th><th>Received</th><th></th></tr>
    </thead>
    <tbody>
      @forelse($enquiries as $enquiry)
        <tr>
          <td>{{ $enquiry->reference }}</td>
          <td>{{ $enquiry->name }}<br>{{ $enquiry->phone }}</td>
          <td>{{ ucfirst($enquiry->service_type) }}@if($enquiry->service_name)<br>{{ $enquiry->service_name }}@endif</td>
          <td>{{ ucfirst($enquiry->status) }}</td>
          <td>{{ $enquiry->created_at->format('j M Y, g:i a') }}</td>
          <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">Open</a></td>
        </tr>
      @empty
        <tr><td colspan="6">No enquiries yet.</td></tr>
      @endforelse
    </tbody>
  </table></div>
  {{ $enquiries->links() }}
@endsection
