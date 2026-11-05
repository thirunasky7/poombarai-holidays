@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
  <div class="admin-top"><h1>Dashboard</h1></div>
  <div class="counts">
    @foreach($counts as $label => $count)
      <article class="count"><span>{{ $count }}</span>{{ $label }}</article>
    @endforeach
  </div>
  <p>Change the WhatsApp number, address and hero photo in site settings before you share the site. Upload your own photos on each stay, vehicle, package and place. Enquiry emails use the address in site settings and the mail settings in the environment file.</p>
  @if($recentEnquiries->isNotEmpty())
    <h2>Latest enquiries</h2>
    <div class="table-wrap"><table>
      <tbody>
        @foreach($recentEnquiries as $enquiry)
          <tr>
            <td>{{ $enquiry->reference }}</td>
            <td>{{ $enquiry->name }}</td>
            <td>{{ ucfirst($enquiry->status) }}</td>
            <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">Open</a></td>
          </tr>
        @endforeach
      </tbody>
    </table></div>
  @endif
@endsection
