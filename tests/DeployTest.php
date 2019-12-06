<?php

/*
 * This file is part of the jimmy/laravel-deployer.
 *
 * (c) jimmy.xie <jimmy.xie@znmin.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Znmin\LaravelDeployer\Tests;

use Znmin\LaravelDeployer\Adapters\ExpectAdapter;
use Znmin\LaravelDeployer\Deployer;
use PHPUnit\Framework\TestCase;

class DeployTest extends TestCase
{
    public function testExpectDeploy()
    {
        $config = [
            'username' => '',
            'password' => '',
            'remote' => 'origin',
            'branch' => 'master',
        ];

        $deployer = new Deployer(new ExpectAdapter($config));
        $deployer->run();

        $this->assertTrue(true);
    }
}
