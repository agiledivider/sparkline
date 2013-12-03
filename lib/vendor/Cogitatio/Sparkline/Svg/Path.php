<?php
namespace Cogitatio\Sparkline\Svg;

class Path extends SvgElement
{
	private $_pathPoints;
	private $_options = array(
		'class' => 'path',
		'precision' => 3
	);

	function __construct($pathPoints, $options = null) {
		$this->_pathPoints = $pathPoints;
		if ($options) {
			$this->_options = array_merge($this->_options, $options);
		}
	}

	function render()
	{
		$precision = $this->_options['precision'];

		for ($i = 0; $i < count($this->_pathPoints); $i++) {
			$this->_pathPoints[$i] = array(
				round($this->_pathPoints[$i][0], $precision),
				round($this->_pathPoints[$i][1], $precision)
			);
		}

		$startPoint = join(',',$this->_pathPoints[0]);

		$points = array();
		for ($i = 1; $i < count($this->_pathPoints); $i++)
		{
			array_push($points, join(',', $this->_pathPoints[$i]));
		}
		$points = join(',',$points);

		return sprintf(
			'<path class="%s" d="M%sL%s"></path>',
			$this->_options['class'], 
			$startPoint, 
			$points
		);
	}
}