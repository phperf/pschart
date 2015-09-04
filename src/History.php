<?php

namespace Phperf\Ps;


use Yaoi\Date\TimeMachine;

class History
{
    public function __construct() {
        $this->time = TimeMachine::getInstance();
    }

    public $minCpuPercent;
    public $minMemPercent;

    /** @var  TimeMachine */
    public $time;
    /** @var  Process[] */
    public $processes;
    public $groups;
    public $states = array();
    public $groupStates = array();

    /** @var State[]  */
    public $totals = array();



    /**
     * @param array|ProcessState[] $states
     */
    public function add(array $states) {

        $now = $this->time->now();

        $total = new State();

        /** @var State[] $groups */
        $groups = array();

        foreach ($states as $processState) {
            $total->memPercent += $processState->memPercent;
            $total->cpuPercent += $processState->cpuPercent;

            if ($processState->command === 'ps aux') {
                continue;
            }

            $group = &$groups[$processState->getProgramName()];
            if (null === $group) {
                $group = new State();
                $group->memPercent += $processState->memPercent;
                $group->cpuPercent += $processState->cpuPercent;
                $group->rss += $processState->rss;
            }

            if ($this->minCpuPercent && $processState->cpuPercent < $this->minCpuPercent) {
                //echo 'l';
                continue;
            }

            if ($this->minMemPercent && $processState->memPercent < $this->minMemPercent) {
                //echo 'l';
                continue;
            }


            if (isset($this->processes[$processState->pid])) {
                $process = $this->processes[$processState->pid];
            }
            else {
                $process = new Process();
                $process->user = $processState->user;
                $process->command = $processState->command;
                $process->pid = $processState->pid;
                $process->started = $processState->started;
                $this->processes[$processState->pid] = $process;
            }

            //echo 'o';


            $state = new State();
            $state->cpuPercent = $processState->cpuPercent;
            $state->memPercent = $processState->memPercent;
            $state->rss = $processState->rss;
            $state->stat = $processState->stat;
            $state->time = $processState->time;
            $state->tt = $processState->tt;
            $state->vsz = $processState->vsz;
            $this->states [$process->pid][$now]= $state;
        }

        foreach ($groups as $name => $state) {
            if ($this->minCpuPercent && $state->cpuPercent < $this->minCpuPercent) {
                //echo 'l';
                continue;
            }

            if ($this->minMemPercent && $state->memPercent < $this->minMemPercent) {
                //echo 'l';
                continue;
            }

            $this->groupStates [$name][$now] = $state;
        }

        $this->totals [$now]= $total;
    }

}