<?php

namespace Jimmy\LaravelDeployer\Adapters;

use Jimmy\LaravelDeployer\Exceptions\ExpectDeployException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ExpectAdapter extends Adapter
{
    protected $exit_code_map = [
        1 => 'username not find',
        2 => 'no such deploy_path directory ',
    ];

    /**
     * @throws ExpectDeployException
     * @throws \Jimmy\LaravelDeployer\Exceptions\DeployException
     */
    public function deploy()
    {
        $username = $this->getUsername();
        $password = $this->getPassword();
        $deploy_path = $this->getDeployPath();
        $remote = $this->getRemote();
        $branch = $this->getBranch();

        $deploy_shell = <<<EOF
FILE='/tmp/.login.sh'
echo '#!/usr/bin/expect -f' > \$FILE
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
echo '    "no such file or directory" {exit 2}' >> \$FILE
echo '    "Already up to date"' >> \$FILE
echo '}' >> \$FILE
chmod a+x \$FILE
\$FILE

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
     * @throws ExpectDeployException
     */
    protected function getUsername()
    {
        return $this->config['username'] ?? '';
    }

    /**
     * @return mixed
     * @throws ExpectDeployException
     */
    protected function getPassword()
    {
        return $this->config['password'] ?? '';
    }

    /**
     * @return mixed
     * @throws ExpectDeployException
     */
    protected function getBranch()
    {
        if (!empty($this->config['branch'])) {
            return $this->config['branch'];
        }

        throw new ExpectDeployException('expect deploy branch not defined.');
    }

    /**
     * @return mixed
     * @throws ExpectDeployException
     */
    protected function getRemote()
    {
        if (!empty($this->config['remote'])) {
            return $this->config['remote'];
        }

        throw new ExpectDeployException('expect deploy remote not defined.');
    }
}