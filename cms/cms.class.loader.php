<?php
/**
* CLASS LOADER
* @package 
* @version 1.0, 2015-05-15
* @since 1.0, 2015-05
*/

/* load classes in lib with naming convention: [prefix].class.php */
function classLoader($class) {
    $filename = strtolower($class) . '.class.php';
    $classpath = __DIR__ . "/lib/" . $filename;
    if (!file_exists($classpath)) {
	    error_log("L14: classLoader error: ".$classpath." doesn't exists.");
        return false;
    }
    include $classpath;
    }
spl_autoload_extensions('.class.php');
spl_autoload_register('classLoader');
?>
