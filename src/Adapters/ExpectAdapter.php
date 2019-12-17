<?php

/*
 * This file is part of the znmin/laravel-deployer.
 *
 * (c) jimmy.xie <jimmy.xie@znmin.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Znmin\LaravelDeployer\Adapters;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Znmin\LaravelDeployer\Exceptions\ExpectDeployException;

class ExpectAdapter extends Adapter
{
    protected $exit_code_map = [
        1 => 'username not find',
        2 => 'no such deploy_path directory ',
    ];

    /**
     * @throws ExpectDeployException
     * @throws \Znmin\LaravelDeployer\Exceptions\DeployException
     */
    public function deploy()
    {
        if (! $this->expectIsInstalled()) {
            throw new ExpectDeployException('not find expect');
        }

        $username = $this->getUsername();
        $password = $this->getPassword();
        $deploy_path = $this->getDeployPath();
        $remote = $this->getRemote();
        $branch = $this->getBranch();

        $deploy_shell = <<<EOF
FILE='/tmp/.login.sh'
echo '#!/usr/bin/expect' > \$FILE
echo 'set timeout 30' >> \$FILE

echo 'spawn su - $username' >> \$FILE

echo 'expect {' >> \$FILE
echo '    "*assword" {send "$password\\r"}' >> \$FILE
echo '    "No passwd" {exit 1}' >> \$FILE
echo '}' >> \$FILE

echo 'expect {' >> \$FILE
echo '    "Sorry" {send "cd $deploy_path && git pull $remote $branch\\r"}' >> \$FILE
echo '    "$username" {send "cd $deploy_path && git pull $remote $branch\\r"}' >> \$FILE
echo '}' >> \$FILE

echo 'expect {' >> \$FILE
echo '    "yes/no)?" {send "yes\\r"}' >> \$FILE
echo '    "no such file or directory" {exit 2}' >> \$FILE
echo '    "Already up" {exit}' >> \$FILE
echo '}' >> \$FILE

echo 'expect {' >> \$FILE
echo '    "Already up" {exit}' >> \$FILE
echo '}' >> \$FILE

echo 'expect eof' >> \$FILE
echo 'exit' >> \$FILE

chmod a+x \$FILE
\$FILE
echo '' > \$FILE

EOF;

        $process = Process::fromShellCommandline($deploy_shell);
        $process->run();

        if (! $process->isSuccessful()) {
            $exit_code = $process->getExitCode();

            if (! empty($this->exit_code_map[$exit_code])) {
                throw new ExpectDeployException($this->exit_code_map[$exit_code]);
            }

            throw new ProcessFailedException($process);
        }
    }

    /**
     * @return mixed
     *
     * @throws ExpectDeployException
     */
    protected function getUsername()
    {
        return $this->config['username'] ?? '';
    }

    /**
     * @return mixed
     *
     * @throws ExpectDeployException
     */
    protected function getPassword()
    {
        return $this->config['password'] ?? '';
    }

    /**
     * @return mixed
     *
     * @throws ExpectDeployException
     */
    protected function getBranch()
    {
        if (! empty($this->config['branch'])) {
            return $this->config['branch'];
        }

        throw new ExpectDeployException('expect deploy branch not defined.');
    }

    /**
     * @return mixed
     *
     * @throws ExpectDeployException
     */
    protected function getRemote()
    {
        if (! empty($this->config['remote'])) {
            return $this->config['remote'];
        }

        throw new ExpectDeployException('expect deploy remote not defined.');
    }

    /**
     * 判断 expect 是否安装.
     */
    protected function expectIsInstalled()
    {
        return file_exists('/usr/bin/expect');
    }
}
