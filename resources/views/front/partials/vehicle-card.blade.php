<article class="info-card" id="{{ $vehicle->slug }}">
  @include('front.partials.gallery', [
      'images' => [['src' => $vehicle->imageSrc(), 'alt' => $vehicle->image_alt]],
      'kind' => 'vehicle',
      'id' => $vehicle->slug,
      'label' => $vehicle->name,
      'wide' => true,
  ])
  <div class="card-body">
    <p class="kicker">Car rental</p>
    <h3>{{ $vehicle->name }}</h3>
    <p class="location">{{ $vehicle->example }}</p>
    <div class="badge-row">
      <span class="badge">Up to {{ $vehicle->seats }} passengers</span>
      <span class="badge">{{ $vehicle->ac ? 'AC' : 'Non-AC' }}</span>
      <span class="badge">Driver included</span>
    </div>
    <p class="summary">{{ $vehicle->blurb }}</p>
    <dl class="rate-list">
      @foreach($vehicle->rates ?? [] as $rate)
        <div class="rate-row">
          <dt>{{ $rate['label'] }}</dt>
          <dd>{{ $rate['value'] }}{{ !empty($rate['detail']) ? ' · '.$rate['detail'] : '' }}</dd>
        </div>
      @endforeach
    </dl>
    <button type="button" class="btn btn-wa" data-enquiry-type="car rental" data-item-name="{{ $vehicle->name }}" data-price="{{ $vehicle->price_label }}" data-vehicle-name="{{ $vehicle->name }}">Enquire for driver &amp; guide</button>
  </div>
</article>
