<article class="info-card package-only-card" id="{{ $package->slug }}">
  @if($package->imageSrc())
    <a class="package-thumb-link" href="{{ route('packages.show', $package) }}" tabindex="-1" aria-hidden="true">
      <div class="media media-wide">
        <img src="{{ $package->imageSrc() }}" alt="{{ $package->image_alt ?: $package->name }}" width="960" height="640" loading="lazy" decoding="async">
      </div>
    </a>
  @endif
  <div class="card-body">
    <h3>{{ $package->name }}</h3>
    @if($package->summary)
      <p>{{ \Illuminate\Support\Str::limit(strip_tags($package->summary), 110) }}</p>
    @endif
    <a class="btn btn-line" href="{{ route('packages.show', $package) }}">View detail</a>
  </div>
</article>
