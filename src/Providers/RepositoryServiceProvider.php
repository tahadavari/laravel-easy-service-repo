<?php

namespace TahaDavari\LaravelEasyServiceRepo\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        foreach (glob(app_path('Repositories/*/I*Repository.php')) as $repositoryInterface) {
            $repositoryClass = str_replace(
                ['app/', '/', '.php'],
                ['App\\', '\\', ''],
                $repositoryInterface
            );
            $concreteClass = str_replace('I', '', $repositoryClass);
            $this->app->bind($repositoryClass, $concreteClass);
        }
    }

    public function boot()
    {
        //
    }
}
