<article class="info-card" id="{{ $package->slug }}">
  @include('front.partials.gallery', [
      'images' => [['src' => $package->imageSrc(), 'alt' => $package->image_alt]],
      'kind' => 'package',
      'id' => $package->slug,
      'label' => $package->name,
      'wide' => true,
  ])
  <div class="card-body">
    <p class="kicker">Sightseeing</p>
    <h3>{{ $package->name }}</h3>
    <p class="summary">{{ $package->summary }}</p>
    <ul class="mini-list">
      @foreach($package->destinations as $place)
        <li>{{ $place->name }}</li>
      @endforeach
    </ul>
    <div class="card-meta">
      <p class="price">{{ $package->fare }}</p>
      <p class="occupancy">{{ $package->duration }}</p>
    </div>
    <p class="price-note">{{ $package->price_label }}. Final fare on WhatsApp.</p>
    <button type="button" class="btn btn-wa" data-enquiry-type="tour package" data-item-name="{{ $package->name }}" data-price="{{ $package->price_label }}">Enquire on WhatsApp</button>
  </div>
</article>
