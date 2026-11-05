<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($pages as $page)
  @php
    if ($page->slug === 'stays') {
        continue;
    }
    $path = match ($page->slug) {
        'home' => '/',
        default => '/'.$page->slug,
    };
  @endphp
  <url>
    <loc>{{ $base }}{{ $path === '/' ? '/' : $path }}</loc>
    <lastmod>{{ $page->updated_at->toDateString() }}</lastmod>
    <changefreq>{{ $page->slug === 'home' ? 'weekly' : 'monthly' }}</changefreq>
    <priority>{{ $page->slug === 'home' ? '1.0' : '0.8' }}</priority>
  </url>
@endforeach
@foreach($packages as $package)
  <url>
    <loc>{{ $base }}/packages/{{ $package->slug }}</loc>
    <lastmod>{{ $package->updated_at->toDateString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
    @if(!empty($package->image_src))
    <image:image>
      <image:loc>{{ $package->image_src }}</image:loc>
      <image:title>{{ $package->name }}</image:title>
      <image:caption>{{ $package->image_alt ?: ($package->name.' from Poombarai, Kodaikanal') }}</image:caption>
    </image:image>
    @endif
  </url>
@endforeach
</urlset>
