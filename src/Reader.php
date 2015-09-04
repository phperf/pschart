<?php

namespace Phperf\Ps;


use Yaoi\Mock;
use Yaoi\Mock\Able;
use Yaoi\String\Parser;

class Reader implements Able
{
    private function exec() {
        if ($this->mock) {
            $out = $this->mock->get(null, function(){
                exec('ps aux', $out);
                return $out;
            });
        }
        else {
            exec('ps aux', $out);
        }
        return $out;
    }

    public function get() {
        $out = $this->exec();
        $hd = preg_split('/\s+/',$out[0]);

        $result = array();
        unset($out[0]);
        foreach ($out as $line) {

            $line = new Parser($line);
            $data = preg_split('/\s+/', $line);


            $info = array();
            foreach ($hd as $i => $field) {
                $info[$field] = $data[$i];
                unset($data[$i]);
            }
            $info[$field] .= ' ' . implode(' ', $data);
            //print_r($info);

            $processInfo = new ProcessState();
            $processInfo->user = $info['USER'];
            $processInfo->pid = $info['PID'];
            $processInfo->cpuPercent = $info['%CPU'];
            $processInfo->memPercent = $info['%MEM'];
            $processInfo->vsz = $info['VSZ'];
            $processInfo->rss = $info['RSS'];
            $processInfo->tt = $info['TT'];
            $processInfo->stat = $info['STAT'];
            $processInfo->started = $info['STARTED'];
            $processInfo->time = $info['TIME'];
            $processInfo->command = $info['COMMAND'];

            $result []= $processInfo;
        }


        return $result;
    }

    /** @var  Mock */
    private $mock;
    public function mock(Mock $dataSet = null)
    {
        $this->mock = $dataSet;
    }


}