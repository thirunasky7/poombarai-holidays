<?php

namespace App\Providers;

use App\Support\SiteSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteSettings::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            static $bundle = false;

            if ($bundle === false) {
                $bundle = null;
                try {
                    if (Schema::hasTable('settings')) {
                        $settings = app(SiteSettings::class);
                        $bundle = [
                            'site' => $settings->all(),
                            'heroUrl' => $settings->heroUrl(),
                            'logoUrl' => $settings->logoUrl(),
                            'faviconUrl' => $settings->faviconUrl(),
                            'faviconType' => $settings->faviconType(),
                            'waConfig' => $settings->whatsappConfig(),
                            'waData' => Schema::hasTable('rooms') ? $settings->scriptData() : [],
                            'navPages' => Schema::hasTable('pages') ? $settings->pages() : collect(),
                        ];
                    }
                } catch (\Throwable $e) {
                    $bundle = null;
                }
            }

            if (is_array($bundle)) {
                $view->with($bundle);
            }
        });
    }
}
