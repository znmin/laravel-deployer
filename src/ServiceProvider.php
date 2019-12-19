<?php

/*
 * This file is part of the znmin/laravel-deployer.
 *
 * (c) jimmy.xie <jimmy.xie@znmin.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Znmin\LaravelDeployer;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Str;
use Znmin\Deployer\Deployer;
use Znmin\Deployer\Exceptions\DeployException;

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
                : 'Znmin\\Deployer\\Adapters\\'.Str::studly($default_adapter_name).'Adapter';

            if (! class_exists($default_adapter)) {
                throw new DeployException('指定驱动不存在');
            }

            return new Deployer(new $default_adapter($config->get('deploy.drives.'.$default_adapter_name)));
        });

        /** @var Router $router */
        $router = $this->app->get(Router::class);
        $router->any('git-hook/gitee', 'Znmin\LaravelDeployer\Controllers\GitHookController@gitee');
    }
}
