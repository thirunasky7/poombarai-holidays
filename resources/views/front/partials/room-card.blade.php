<article class="stay-card" @unless($compact ?? false) id="{{ $room->slug }}" @endunless data-room-type="{{ $room->type }}">
  @if($compact ?? false)
    <div class="media">
      @if($room->cover())
        <img src="{{ $room->cover()->src() }}" alt="{{ $room->cover()->alt }}" width="960" height="640" loading="lazy" decoding="async">
      @endif
    </div>
  @else
    @include('front.partials.gallery', ['images' => $room->images, 'kind' => 'room', 'id' => $room->slug, 'label' => $room->name])
  @endif
  <div class="card-body">
    <p class="kicker">{{ $room->type }}</p>
    <h3>{{ $room->name }}</h3>
    <p class="location">{{ $room->location }}</p>
    <p class="summary">{{ $room->summary }}</p>
    <ul class="amenity-list">
      @foreach($room->amenities ?? [] as $amenity)
        <li><span class="amenity-icon">{!! \App\Support\AmenityIcon::svg($amenity) !!}</span><span>{{ $amenity }}</span></li>
      @endforeach
    </ul>
    <div class="card-meta">
      <div>
        <p class="price">{{ $room->price_label }}</p>
        <p class="price-note">Confirmed on WhatsApp</p>
      </div>
      <p class="occupancy">Up to {{ $room->occupancy }} guests</p>
    </div>
    <button type="button" class="btn btn-wa" data-enquiry-type="{{ $room->type }}" data-item-name="{{ $room->name }}" data-price="{{ $room->price_label }}">Enquire on WhatsApp</button>
    @if($compact ?? false)
      <a class="text-link" href="{{ route('rooms') }}#{{ $room->slug }}">View details</a>
    @endif
  </div>
</article>
