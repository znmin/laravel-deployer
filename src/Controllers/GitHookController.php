<?php

/*
 * This file is part of the znmin/laravel-deployer.
 *
 * (c) jimmy.xie <jimmy.xie@znmin.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Znmin\LaravelDeployer\Controllers;

use Illuminate\Routing\Controller;
use Znmin\LaravelDeployer\Deployer;

class GitHookController extends Controller
{
    public function gitee(Deployer $deployer)
    {
        $deployer->run();
    }
}
