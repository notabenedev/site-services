<?php

namespace Notabenedev\SiteServices;

use App\Services;
use Illuminate\Support\ServiceProvider;

class SiteServicesServiceProvider extends ServiceProvider
{
    public function boot()
    {

        // Подгрузка миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Подгрузка шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'site-services');

        // Копирование шаблонов.
        $this->publishes([
            __DIR__ . '/resources/views/site/services' => resource_path('views/vendor/site-services/site/services'),
        ], 'views-site');
        $this->publishes([
            __DIR__ . '/resources/views/admin/services' => resource_path('views/vendor/site-services/admin/services'),
        ], 'views-admin');

        // Подгрузка роутов.
        if (base_config()->get("services", "route-name", false) && file_exists(base_path("routes/" . base_config()->get("services", "route-name", false) . ".php"))) {
            $this->loadRoutesFrom(base_path("routes/" . base_config()->get("services", "route-name", false) . ".php"));
        }
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Console.
        if ($this->app->runningInConsole()) {
//            $this->commands([
//                NewsMakeCommand::class,
//            ]);
        }

        // Подключаем метатеги.
        $seo = app()->config['seo-integration.models'];
        $seo['services'] = Services::class;
        app()->config['seo-integration.models'] = $seo;

        // Подключаем галлерею.
        $gallery = app()->config['gallery.models'];
        $gallery['services'] = Services::class;
        app()->config['gallery.models'] = $gallery;

        $imagecache = app()->config['imagecache.paths'];
        $imagecache[] = 'storage/gallery/services';
        $imagecache[] = 'storage/services/main';
        app()->config['imagecache.paths'] = $imagecache;

        $imagecache = app()->config['imagecache.templates'];
        $imagecache['services-main'] = ServicesShowMain::class;
        app()->config['imagecache.templates'] = $imagecache;

        // Подписаться на обновление изображений.
        //$this->app['events']->listen(ImageUpdate::class, ClearCacheOnUpdateImage::class);
    }
    public function register()
    {

    }
    
}