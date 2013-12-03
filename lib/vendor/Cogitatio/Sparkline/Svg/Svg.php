<?php
namespace Cogitatio\Sparkline\Svg;
class Svg {

	private $_width;
	private $_height;
	private $_children = array();

	function __construct($width, $height)
	{
		$this->_width = (int) $width;
		$this->_height = (int) $height;
	}

	function add($item)
	{
		array_push($this->_children, $item);
	}

	function render()
	{
		$inlineStyle ='<style>
.line {
  fill: none;
  stroke: black;
  stroke-width: 1px;
}
.blueline {
	stroke: #2222ff;
}
.above { fill: #00aa00; stroke: none;}
.below { fill: #ff0000; stroke: none;}
</style>';
		$components = '';
		foreach ($this->_children as $child) 
		{
			$components .= $child->render();
		}
		
		return sprintf('<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d">%s%s</svg>', $this->_width, $this->_height, $inlineStyle, $components);
	}
}