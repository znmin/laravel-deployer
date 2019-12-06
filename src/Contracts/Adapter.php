<?php

namespace Jimmy\LaravelDeployer\Contracts;

interface Adapter
{
    public function __construct(array $config);

    public function deploy();
}
