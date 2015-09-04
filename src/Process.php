<?php

namespace Phperf\Ps;


class Process
{
    public $user;
    public $pid;
    public $started;
    public $command;

    public function getShortName() {
        $l = explode(' ', $this->command, 2);
        return basename($l[0]) . ':' . $this->pid;
    }
}