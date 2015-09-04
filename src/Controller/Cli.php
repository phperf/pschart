<?php

namespace Phperf\Ps\Controller;


use Phperf\Ps\History;
use Phperf\Ps\Reader;
use Phperf\Ps\State;
use Phperf\Ps\View\Layout;
use Yaoi\BaseClass;
use Yaoi\Date\TimeMachine;
use Phperf\Ps\View\HighCharts;

class Cli extends BaseClass
{
    public $minCpuPercent = 10;
    public $minMemPercent;
    public $delay = 1;
    public $saveInterval = 5;
    public $timeLimit;

    public function route($arguments) {
        print_r($arguments);
        $this->execute();
    }

    public function help() {

    }

    /** @var  History */
    private $history;

    public function execute() {
        set_time_limit(0);
        $this->history = new History();
        $this->history->minCpuPercent = $this->minCpuPercent;
        $this->history->minMemPercent = $this->minMemPercent;

        $reader = new Reader();
        $time = TimeMachine::getInstance();
        $start = $time->microNow();
        $lastUpdate = $start;
        do {
            $processStates = $reader->get();
            $this->history->add($processStates);
            echo '.';
            $now = $time->microNow();
            if ($lastUpdate < $now - $this->saveInterval) {
                $lastUpdate = $now;
                echo 's';
                $this->renderReport();
            }
            sleep($this->delay);
        }
        while (($this->timeLimit === null) || $now > $start + $this->timeLimit);
    }

    private function renderReport() {
        $layout = new Layout();

        $cpuChart = new HighCharts();
        $cpuChart->withDateAxis();

        $memChart = new HighCharts();
        $memChart->withDateAxis();

        $layout->content->push($cpuChart);
        $layout->content->push($memChart);

        foreach ($this->history->states as $pid => $pidData) {
            $process = $this->history->processes[$pid];

            /**
             * @var int $ut
             * @var State $state
             */
            foreach ($pidData as $ut => $state) {
                $ut = 1000 * $ut;
                $cpuChart->addRow($ut, $state->cpuPercent, $process->getShortName());
                $memChart->addRow($ut, $state->memPercent, $process->getShortName());

            }
        }

        file_put_contents('report.html', $layout);
    }
}