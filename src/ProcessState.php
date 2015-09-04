<?php

namespace Phperf\Ps;


class ProcessState
{
    public $user;
    public $pid;
    public $cpuPercent;
    public $memPercent;
    public $vsz;
    public $rss;
    public $tt;
    public $stat;
    public $started;
    public $time;
    public $command;


    public function getProgramName() {
        $l = explode(' ', $this->command, 2);
        return basename($l[0]);
    }
}