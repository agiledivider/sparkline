<?php
use Cogitatio\Sparkline\Svg\Path;

/**
 * @covers Cogitatio\Sparkline\Svg\Path
 */
class SvgPathTest extends \PHPUnit_Framework_TestCase
{
    private $data = array(array(0,0), array(1,2), array(3.12344,1.12344), array(100,40));
    /**
     * @dataProvider pathProvider
     */
    public function testSimplePath($data, $expected, $options = null)
    {
        $path = new Path($data, $options);
        $this->assertEquals(
            $expected,
            $path->render()
        );
    }

    public function pathProvider()
    {
        return array(
            array(
                array(array(0,0), array(1,2), array(3.1234567,1.1234567), array(100,40)),
                '<path class="path" d="M0,0L1,2,3.123,1.123,100,40"></path>'
            )
        );
    }

    public function testSimplePathWithPrecisionOption()
    {
        $path = new Path($this->data, array('precision' => 4));
        $this->assertEquals(
            '<path class="path" d="M0,0L1,2,3.1234,1.1234,100,40"></path>',
            $path->render()
        );
    }

    public function testSimplePathWithClassOption()
    {
        $path = new Path($this->data, array('class' => 'something'));
        $this->assertEquals(
            '<path class="something" d="M0,0L1,2,3.123,1.123,100,40"></path>',
            $path->render()
        );
    }
}
