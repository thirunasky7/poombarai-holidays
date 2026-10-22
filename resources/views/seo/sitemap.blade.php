<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($pages as $page)
  @php
    $path = match ($page->slug) {
        'home' => '/',
        'stays' => '/stays',
        default => '/'.$page->slug,
    };
  @endphp
  <url>
    <loc>{{ $base }}{{ $path === '/' ? '/' : $path }}</loc>
    <lastmod>{{ $page->updated_at->toDateString() }}</lastmod>
  </url>
@endforeach
</urlset>
