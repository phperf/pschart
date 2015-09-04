<?php
/**
 * Created by PhpStorm.
 * User: vpoturaev
 * Date: 9/4/15
 * Time: 15:12
 */

namespace Phperf\Ps\View;


use Yaoi\BaseClass;
use Yaoi\View\Renderer;
use Yaoi\View\Stack;

class Layout extends BaseClass implements Renderer
{
    public function isEmpty()
    {
        // TODO: Implement isEmpty() method.
    }

    /** @var  Stack */
    public $content;

    public function __construct() {
        $this->content = new Stack();
    }




    public function render()
    {
        ?>
<html>
<head>
    <title><?=gethostname()?></title>
    <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="http://code.highcharts.com/stock/highstock.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
</head>
<body>
<?php
$this->content->render();
?>
</body>
</html>
        <?php
    }

    public function __toString()
    {
        ob_start();
        try {
            $this->render();
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        return ob_get_clean();
    }

}