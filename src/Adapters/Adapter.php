<?php

namespace Jimmy\LaravelDeployer\Adapters;

use Jimmy\LaravelDeployer\Contracts\Adapter as AdapterContract;
use Jimmy\LaravelDeployer\Exceptions\DeployException;

abstract class Adapter implements AdapterContract
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return false|mixed|string
     * @throws DeployException
     */
    protected function getDeployPath()
    {
        if (!empty($this->config['deploy_path'])) {
            return $this->config['deploy_path'];
        }

        do {
            $deploy_path = realpath(($deploy_path ?? __DIR__).'/../');

            if ('/' == $deploy_path) {
                throw new DeployException('deploy path not defined.');
            }
        } while (!file_exists($deploy_path.'/.git'));

        return $deploy_path;
    }
}
