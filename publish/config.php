<?php

/*
 * This file is part of the znmin/laravel-deployer.
 *
 * (c) jimmy.xie <jimmy.xie@znmin.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [

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
];
