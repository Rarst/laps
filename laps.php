<?php

/*
Plugin Name: Laps
Plugin URI: https://github.com/Rarst/laps
Description: Light WordPress profiler.
Author: Andrey “Rarst” Savchenko
Version: 3.3.7
Author URI: https://www.rarst.net/
Requires PHP: 7.1.3
Update URI: false
License: MIT
*/

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

if ( ! class_exists( '\Rarst\Laps\Plugin' ) ) {
	trigger_error( 'Laps not fully installed! Please install with Composer or download full release archive.', E_USER_ERROR );
}

if ( PHP_VERSION_ID < 70103 ) {
	trigger_error( 'Laps requires PHP version >=7.1.3.', E_USER_ERROR );
}

$laps = new \Rarst\Laps\Plugin();

$laps->run();
