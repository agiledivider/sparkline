<?php
spl_autoload_register(function ($class) {
	$file = 'lib/vendor/' . str_replace('\\','/',$class) . '.php';
	if (file_exists($file))
    include $file;
});
use Cogitatio\Sparkline\Sparkline;

/**
 * @covers Cogitatio\Sparkline\Sparkline
 */
class StackTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider simpleLines
	 */
    public function testSimpleLinewithSvgRenderer($pattern, $values)
    {
        $sparkline = new Sparkline(100, 100);
		$sparkline->addDataSet($values, 'test');

		$sparkline->addSpark('line', array('test'));
		$this->assertEquals(1, preg_match($pattern, $sparkline->render()));
    }

    public function simpleLines()
    {
    	return array(
    		array(
    			'/0,100L100,0/', 
    			array(array(0, 0),array(100, 100))
			),array(
    			'/0,100L100,0/', 
    			array(array(0, 0),array(200, 100))
			),
			array(
    			'/0,100L100,0/', 
    			array(array(20, 0),array(100, 100))
			),
			array(
    			'/0,100L50,0,100,100/', 
    			array(array(0, 0),array(50, 100),array(100, 0))
			),
			array(
    			'/0,100L50,0,100,100/', 
    			array(array(0, 0),array(100, 300),array(200, 0))
			)
    	);
    }


    /**
	 * @dataProvider simpleLinesResized
	 */
    public function testSimpleLinewithSvgRendererWidthFixedXRange($pattern, $values, $options)
    {
        $sparkline = new Sparkline(100, 100, $options);
		$sparkline->addDataSet($values, 'test');

		$sparkline->addSpark('line', array('test'));
		$this->assertEquals(1, preg_match($pattern, $sparkline->render()));
    }

    public function simpleLinesResized()
    {
    	return array(
    		array(
    			'/0,100L100,0/', 
    			array(array(0, 0),array(100, 100)),
				array()
			),
			array(
    			'/0,100L50,0/', 
    			array(array(0, 0),array(100, 100)),
				array('xRange' => array(0,200))
			),
			array(
    			'/0,100L44.444,0/', 
    			array(array(20, 0),array(100, 100)),
				array('xRange' => array(20,200))
			),
			array(
    			'/10,100L50,0/', 
    			array(array(20, 0),array(100, 100)),
				array('xRange' => array(0,200))
			),
			array(
    			'/0,100L100,0/', 
    			array(array(100, 0),array(200, 100)),
				array('xRange' => array(100,200))
			),
			array(
    			'/0,100L50,0,100,100/', 
    			array(array(0, 0),array(50, 100),array(100, 0)),
				array()
			),
			array(
    			'/0,100L50,0,100,100/', 
    			array(array(0, 0),array(100, 300),array(200, 0)),
				array()
			)
    	);
    }

	/**
     * @expectedException InvalidArgumentException
     */
    public function testUndefinedChartTypeThrowsException()
    {
        $sparkline = new Sparkline(100, 100);
		$sparkline->addDataSet(array(array(0,0), array(100,100)), 'test');

		$sparkline->addSpark('notAvailableChartType', array('test'));
    }

    public function testSimpleLineWithOptions()
    {
        $sparkline = new Sparkline(100, 100);
		$sparkline->addDataSet(array(array(0,0), array(100,100)), 'test');

		$sparkline->addSpark('line', array('test'), array('class' => 'blueline'));
		$this->assertEquals(1, preg_match('/<path[^>]*class="blueline"/', $sparkline->render()));
    }
}


