<?php

/*
Plugin Name: Laps
Plugin URI: https://github.com/Rarst/laps
Description: Light WordPress profiler.
Author: Andrey "Rarst" Savchenko
Version: 1.4.4
Author URI: http://www.rarst.net/
Text Domain: laps
Domain Path: /lang
License: MIT
*/

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

$laps = new \Rarst\Laps\Plugin();

$laps->run();
