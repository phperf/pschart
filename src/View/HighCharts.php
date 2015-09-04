<?php
/**
 * Created by PhpStorm.
 * User: vpoturaev
 * Date: 9/4/15
 * Time: 16:04
 */

namespace Phperf\Ps\View;


class HighCharts extends \Yaoi\View\HighCharts
{
    public function __construct() {
        parent::__construct();
        $this->addOption('plotOptions', 'series', 'animation', false);
    }

}