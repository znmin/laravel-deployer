<?php

/*
 * This file is part of the znmin/laravel-deployer.
 *
 * (c) jimmy.xie <jimmy.xie@znmin.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Znmin\LaravelDeployer\Contracts;

interface Adapter
{
    public function __construct(array $config);

    public function deploy();
}
