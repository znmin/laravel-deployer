<?php

/*
 * This file is part of the jimmy/laravel-deployer.
 *
 * (c) jimmy.xie <jimmy.xie@znmin.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'default' => 'expect',

    'drives' => [
        // linux expect 驱动
        'expect' => [
            'user' => 'vagrant',
            'password' => 'vagrant',
        ],
    ],
];
