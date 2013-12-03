<?php
namespace Cogitatio\Sparkline\Renderer;
use Cogitatio\Sparkline\Svg\Svg;
use Cogitatio\Sparkline\Svg\Path;
class SvgRenderer 
{
	private $_svg;
	private $_width;
	private $_height;

	function __construct($width, $height)
	{
		$this->_width = $width;
		$this->_height = $height;
		$this->_svg = new Svg($this->_width, $this->_height);
	}

	function addLine($path, $options = null)
	{
		for ($i = 0; $i < count($path); $i++) {
			$path[$i] = array(
				$path[$i][0],
				-1 * $path[$i][1] + $this->_height
			);
		}
		$this->_svg->add(new Path($path, $options));
	}

	function addPercentShift($path, $options = null)
	{

		$above = array();
		$below = array();

		var_dump($path);

		for ($i = 0; $i < count($path); $i++) {
			$above[$i] = array(
				$path[$i][0],
				(($path[$i][1] / 100) * ($this->_height/2))
			);
		}
		for ($i = 0; $i < count($above); $i++) {
			$below[$i] = array(
				$above[$i][0],
				$above[$i][1] + $this->_height/2
			);
		}
		
		array_unshift($above, array(0,$this->_height/2));
		array_push($above, array($above[count($above)-1][0],$this->_height/2));
		$this->_svg->add(new Path($above, array('class' => 'above')));
		array_unshift($below, array(0,$this->_height/2));
		array_push($below, array($below[count($below)-1][0],$this->_height/2));
		$this->_svg->add(new Path($below, array('class' => 'below')));
	}

	function addDifference($pathMain, $pathCompare, $options = null)
	{
		for ($i = 0; $i < count($pathMain); $i++) {
			$pathMain[$i] = array(
				$pathMain[$i][0],
				-1 * $pathMain[$i][1] + $this->_height
			);
		}

		for ($i = 0; $i < count($pathCompare); $i++) {
			$pathCompare[$i] = array(
				$pathCompare[$i][0],
				-1 * $pathCompare[$i][1] + $this->_height
			);
		}

		$abovePaths = array();
		$belowPaths = array();
		$intersectionAbove = null;

		$mainI = $compI = 0;
		$main = array();
		$comp = array();
		$keepOnGoing = true;
		$main[] = $pathMain[$mainI];
		$comp[] = $pathCompare[$compI];
		while ($keepOnGoing) {
			$intersection = false;
			echo sprintf("index main: %s , count main: %s, index comp: %s, count comp: %s\n", $mainI, count($pathMain), $compI, count($pathCompare));
				
			if($mainI + 1 < count($pathMain) && $compI + 1 < count($pathCompare)) {
				//var_dump('testing');
				$mm = ($pathMain[$mainI + 1][1] - $pathMain[$mainI][1]) / ($pathMain[$mainI + 1][0] - $pathMain[$mainI][0]);
				$mc = (($pathCompare[$compI + 1][0] - $pathCompare[$compI][0]) == 0) ? 0 : ($pathCompare[$compI + 1][1] - $pathCompare[$compI][1]) / ($pathCompare[$compI + 1][0] - $pathCompare[$compI][0]);
				echo sprintf("mm: %s , mc: %s\n", $mm, $mc);
				if ($mm != $mc) {
					$bm = $pathMain[$mainI][1] - $mm * $pathMain[$mainI][0];
					$bc = $pathCompare[$compI][1] - $mc * $pathCompare[$compI][0];
//echo sprintf ("Formel: %s * %s + %s = %s, should be: %s\n", $mm , $pathMain[$mainI +1][0] , $bm, $mm * $pathMain[$mainI +1][0] + $bm,$pathMain[$mainI+1][1]);
//echo sprintf ("Formel: %s * %s + %s = %s, should be: %s\n", $mc , $pathCompare[$compI +1][0] , $bc, $mc * $pathCompare[$compI +1][0] + $bc,$pathCompare[$compI+1][1]);

					$x =  ($bc - $bm) / ($mm - $mc);

					echo sprintf("Would intersect here: %s. between [%s, %s]\n", $x, $pathMain[$mainI][0], $pathMain[$mainI + 1][0]);
					if ($x < $pathMain[$mainI+1][0] && $x > $pathMain[$mainI][0] && $x < $pathCompare[$compI+1][0] && $x > $pathCompare[$compI][0])
					{
						echo "intersection !!!!!\n";
						$intersection = true;
						$intersectionPoint = array($x, $mm * $x + $bm);
						$intersectionAbove = $pathMain[$mainI][1] > $pathCompare[$compI][1];
					}
				}
			}

			if (!isset($pathMain[$mainI]) && !isset($pathCompare[$compI])) {
				$keepOnGoing = false;
			}

			if (!isset($pathMain[$mainI]) && isset($pathCompare[$compI])) {
				$comp[] = $pathCompare[$compI];
				$compI++;
				
			}

			if (isset($pathMain[$mainI]) && !isset($pathCompare[$compI])) {
				$main[] = $pathMain[$mainI];
				$mainI++;
				
			}

			if (isset($pathMain[$mainI+1]) && isset($pathCompare[$compI+1])) {
				if ($pathMain[$mainI+1][0] > isset($pathCompare[$compI+1][0])) {
					$comp[] = $pathCompare[$compI];
					$compI++;
				} else {
					$main[] = $pathMain[$mainI];
					$mainI++;
				}
			} elseif (isset($pathMain[$mainI+1])) {
				$main[] = $pathMain[$mainI];
				$mainI++;
			} else {
				if (isset($pathCompare[$compI])) {
					$comp[] = $pathCompare[$compI];
				}
				$compI++;
			}

			if ($intersection) {
				$main[] = $intersectionPoint;
				if ($intersectionAbove) {
					$abovePaths[] = array_merge($main, array_reverse($comp));
				} else {
					$belowPaths[] = array_merge($main, array_reverse($comp));
				}
				$main = array($intersectionPoint);
				$comp = array($intersectionPoint);
			}
		}
		if ($intersectionAbove === null) {
			$intersectionAbove = $pathMain[0][1] < $pathCompare[0][1];
		}
		if (!$intersectionAbove) {
			$abovePaths[] = array_merge($main, array_reverse($comp));
		} else {
			$belowPaths[] = array_merge($main, array_reverse($comp));
		}

		// find intersections
		// create top and bottom paths

		$calculatedPath = array_merge($pathMain, array_reverse($pathCompare));
		foreach($abovePaths as $path) {
			$this->_svg->add(new Path($path, array('class' => 'above')));
		}
		foreach($belowPaths as $path) {
			$this->_svg->add(new Path($path, array('class' => 'below')));
		}
		//$this->_svg->add(new Path($pathCompare, array('class' => 'bottom')));
	}

	function render() {
		return $this->_svg->render();;
	}
}