<?php

namespace App\Services;

use phpseclib3\Net\SSH2;

class SshService
{
    protected $ssh;

    public function __construct($host, $port, $username, $password)
    {
        $this->ssh = new SSH2($host, $port);

        if (!$this->ssh->login($username, $password)) {
            throw new \Exception('Login failed');
        }
    }

    public function execute($command)
    {
        return $this->ssh->exec($command);
    }
}