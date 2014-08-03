<?php
namespace Cogitatio\Sparkline\Svg;
class Svg
{
    private $_width;
    private $_height;
    private $_options = array();
    private $_children = array();

    public function __construct($width, $height, $options = array())
    {
        $this->_width = (int) $width;
        $this->_height = (int) $height;
        $this->_options = $options;
    }

    public function add($item)
    {
        array_push($this->_children, $item);
    }

    public function render()
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
        if (!isset($this->_options['inlineStyle'])) {
            $inlineStyle = '';
        } elseif ($this->_options['inlineStyle'] !== true) {
            $inlineStyle = sprintf('<style>%s</style>', $this->_options['inlineStyle']);
        }
        $components = '';
        foreach ($this->_children as $child) {
            $components .= $child->render();
        }

        return sprintf('<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d">%s%s</svg>', $this->_width, $this->_height, $inlineStyle, $components);
    }
}
