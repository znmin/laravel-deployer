<?php

namespace Jimmy\LaravelDeployer;

use Jimmy\LaravelDeployer\Contracts\Adapter;

class Deployer
{
    /**
     * 部署适配器
     *
     * @var Adapter
     */
    protected $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * 开始部署
     */
    public function run()
    {
        $this->adapter->deploy();
    }
}
