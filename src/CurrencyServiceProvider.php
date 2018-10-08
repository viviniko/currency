<?php

namespace Viviniko\Currency;

use Viviniko\Currency\Console\Commands\CurrencyTableCommand;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/currency.php' => config_path('currency.php'),
        ]);

        $this->commands('command.currency.table');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/currency.php', 'currency');
        $this->registerRepositories();
        $this->registerService();
        $this->registerCommands();
    }

    protected function registerRepositories()
    {
        $this->app->singleton(
            \Viviniko\Currency\Repositories\Currency\CurrencyRepository::class,
            \Viviniko\Currency\Repositories\Currency\CurrencyRepositoryImpl::class
        );
    }

    protected function registerService()
    {
        $this->app->singleton('currency', function ($app) {
            $currencyService = $app->make(\Viviniko\Currency\Services\Currency\CurrencyServiceImpl::class);

            return $currencyService;
        });

        $this->app->alias('currency', \Viviniko\Currency\Services\CurrencyService::class);
    }

    protected function registerCommands()
    {
        $this->app->singleton('command.currency.table', function ($app) {
            return new CurrencyTableCommand($app['files'], $app['composer']);
        });
    }

    public function provides()
    {
        return [
            'currency',
            \Viviniko\Currency\Services\CurrencyService::class,
        ];
    }
}