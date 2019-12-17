<?php

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
