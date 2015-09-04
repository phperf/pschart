<?php

namespace Phperf\Ps;

use Yaoi\Date\TimeMachine;
use Yaoi\Mock;
use Yaoi\Storage;

class TheTest extends \Yaoi\Test\PHPUnit\TestCase
{
    public function testThe() {
        $ps = new Reader();
        $time = TimeMachine::getInstance();
        $time->mock(new Mock(new Storage('serialized-file:///' . __DIR__ .'/mocks/time.serialized')));
        $ps->mock(new Mock(new Storage('serialized-file:///' . __DIR__ .'/mocks/ps-reader.serialized')));

        $history = new History();

        $now = $time->microNow();
        do {
            $processStates = $ps->get();
            $history->add($processStates);
            echo '.';
            sleep(1);
        }
        while ($time->microNow() < $now + 5);



        print_r($history->processes);
    }

}