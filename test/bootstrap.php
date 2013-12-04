<?php
spl_autoload_register(function ($class) {
	$file = 'lib/vendor/' . str_replace('\\','/',$class) . '.php';
	if (file_exists($file))
    include $file;
});