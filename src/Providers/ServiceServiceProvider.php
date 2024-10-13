<?php

namespace TahaDavari\LaravelEasyServiceRepo\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        foreach (glob(app_path('Services/*/I*Service.php')) as $serviceInterface) {
            $serviceClass = str_replace(
                ['app/', '/', '.php'],
                ['App\\', '\\', ''],
                $serviceInterface
            );
            $concreteClass = str_replace('I', '', $serviceClass);

            $this->app->bind($serviceClass, $concreteClass);
        }
    }

    public function boot()
    {
        //
    }
}
