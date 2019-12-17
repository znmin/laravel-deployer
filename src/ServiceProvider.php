<?php

namespace Znmin\LaravelDeployer;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Str;
use Znmin\LaravelDeployer\Exceptions\DeployException;
use Illuminate\Routing\Router;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../publish/config.php' => config_path('deploy.php'),
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../publish/config.php', 'deploy');

        $this->app->singleton(Deployer::class, function (Application $app) {
            /** @var Repository $config */
            $config = $app->get('config');

            $default_adapter_name = $config->get('deploy.default');

            $default_adapter = Str::contains($default_adapter_name, '\\')
                ? $default_adapter_name
                : 'Znmin\\LaravelDeployer\\Adapters\\' . Str::studly($default_adapter_name) . 'Adapter';

            if (! class_exists($default_adapter)) {
                throw new DeployException('指定驱动不存在');
            }

            return new Deployer(new $default_adapter($config->get('deploy.drives.' . $default_adapter_name)));
        });

        /** @var Router $router */
        $router = $this->app->get(Router::class);
        $router->any('git-hook/gitee', 'Znmin\LaravelDeployer\Controllers\GitHookController@gitee');
    }
}
