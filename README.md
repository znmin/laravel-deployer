<h1 align="left"><a href="javascript:void(0);">Laravel Deployer</a></h1>

[![Build Status](https://travis-ci.org/znmin/laravel-deployer.svg?branch=master)](https://travis-ci.org/znmin/laravel-deployer)
![StyleCI build status](https://github.styleci.io/repos/226277417/shield) 

## 依赖
* PHP >= 7.1

## 安装
```shell
$ composer require znmin/laravel-deployer
```

composer 安装之后，如果 Laravel 版本 < 5.5，则需要注册服务提供者
```php
// config/app.php

'providers' => [
    //...
    Znmin\LaravelDeployer\ServiceProvider::class,
],
```

发布配置文件
```shell
$ php artisan vendor:publish --provider=Znmin\LaravelDeployer\ServiceProvider
```

配置
```php
/*
|--------------------------------------------------------------------------
| 默认部署驱动
|--------------------------------------------------------------------------
|
| 指定默认的部署驱动
| 可选择的驱动：expect
|
*/
'default' => 'expect',

'drives' => [

    /*
     * expect 驱动配置
     */
    'expect' => [
        'username' => env('DEPLOY_EXPECT_USERNAME', ''),
        'password' => env('DEPLOY_EXPECT_PASSWORD', ''),
        'branch' => env('DEPLOY_EXPECT_BRANCH', 'master'),
        'remote' => 'origin',
    ],
],
```

## 使用
使用 expect 驱动需在部署服务器安装此命令：

Ubuntu
```shell
$ apt install expect
```

CentOS
```shell
$ yum install expect
```

配置码云 webhook

```shell
码云钩子
https://example.com/git-hook/gitee
```
`https://example.com` 替换为真实域名即可


