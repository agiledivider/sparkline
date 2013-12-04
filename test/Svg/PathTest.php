<?php
spl_autoload_register(function ($class) {
	$file = 'lib/vendor/' . str_replace('\\','/',$class) . '.php';
	if (file_exists($file))
    include $file;
});
use Cogitatio\Sparkline\Svg\Path;

/**
 * @covers Cogitatio\Sparkline\Sparkline
 */
class SvgPathTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @pathProvider
     */
    public function testSimplePath($data, $expected, $options = null)
    {
        $path = new Path($data, $options);
		$this->assertEquals(
            $expected, 
            $path->render()
        );
    }

    public function pathProvider ()
    {
        return array(
            array(
                array(array(0,0), array(1,2), array(3.1234567,1.1234567), array(100,40)),
                '<path class="path" d="M0,0L1,2,3.123,1.123,100,40"></path>'
            )
        );
    }
}


