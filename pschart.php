<?php

require_once __DIR__ . '/vendor/autoload.php';

if (PHP_SAPI === 'cli') {
    \Phperf\Ps\Controller\Cli::create()->route($_SERVER['argv']);
}
else {
    die('CLI mode required');
}
