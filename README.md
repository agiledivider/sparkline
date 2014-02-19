sparkline
=========

[![Build Status](https://travis-ci.org/cogitatio/sparkline.png?branch=master)](https://travis-ci.org/cogitatio/sparkline)
[![Coverage Status](https://coveralls.io/repos/cogitatio/sparkline/badge.png)](https://coveralls.io/r/cogitatio/sparkline)


Basic usage
-----------

```php
<?php

    use Cogitatio\Sparkline\Sparkline;

    $values = [ 3, 4, 5, 6, 12, 90, 1, 0, 0 ];
    $sparkline = new Sparkline(100, 100);
    // Add a dataset
    $sparkline->addDataSet($values, 'nrOfUsers');
    
    // decide how to show it:
    $sparline->addSpark('line', array('nrOfUsers'));
    // other posiblities:
    $sparline->addSpark('percentShift', array('nrOfUsers'));
    $sparline->addSpark('difference', array('nrOfUsers'));


    // Then render the svg element somewhere on your page:

    echo $sparkline->render();

```    
Styling
-------
Example css style:

```css
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

```
    

