<?php
namespace Cogitatio\Sparkline\Svg;

/**
 * @covers Cogitatio\Sparkline\Svg\Svg
 */
class SvgTest extends \PHPUnit_Framework_TestCase
{
    public function testSvg()
    {
        $svg = new Svg(100, 100);
		$this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"></svg>', 
            $svg->render()
        );
    }

    public function testSvgInlineStyle()
    {
        $svg = new Svg(100, 100, array('inlineStyle' => '.line {fill: #cecece; }'));
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><style>.line {fill: #cecece; }</style></svg>', 
            $svg->render()
        );
    }
  
}


