<?php

namespace Jimmy\LaravelDeployer\Tests;

use Jimmy\LaravelDeployer\Adapters\ExpectAdapter;
use Jimmy\LaravelDeployer\Deployer;
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
