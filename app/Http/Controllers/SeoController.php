<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\SiteSettings;

class SeoController extends Controller
{
    public function sitemap(SiteSettings $settings)
    {
        $site = $settings->all();
        $base = rtrim($site['site_url'] ?: url('/'), '/');
        $pages = Page::query()->orderBy('sort_order')->get();

        return response()
            ->view('seo.sitemap', compact('base', 'pages'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots(SiteSettings $settings)
    {
        $site = $settings->all();
        $base = rtrim($site['site_url'] ?: url('/'), '/');

        $body = "User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: {$base}/sitemap.xml\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
