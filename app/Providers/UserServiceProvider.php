<?php

namespace Plugins\Foundry\User\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(base_path('Plugins/Foundry/User/database/migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            base_path('Plugins/Foundry/User/config/config.php') => config_path('foundry_user.php'),
        ], 'config');
        $this->mergeConfigFrom(
            base_path('Plugins/Foundry/User/config/config.php'), 'foundry_user'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/foundry_user');

        $sourcePath = base_path('Plugins/Foundry/User/resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/foundry_user';
        }, \Config::get('view.paths')), [$sourcePath]), 'foundry_user');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/foundry_user');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'foundry_user');
        } else {
            $this->loadTranslationsFrom(base_path('Plugins/Foundry/User/resources/lang'), 'foundry_user');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(base_path('Plugins/Foundry/User/database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
