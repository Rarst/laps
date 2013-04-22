<?php

namespace Rarst\Laps;

class Core_Events extends Laps_Events {

	public function get_events() {

		$stops = array();

		$stops['plugins_loaded'][20] = array(
			'action'   => 'stop',
			'event'    => 'Plugins Load',
			'category' => 'plugin',
		);

		$stops['setup_theme'][0] = array(
			'event'    => 'Themes Load',
			'category' => 'theme',
		);

		$stops['after_setup_theme'][20] = array(
			'action'   => 'stop',
			'event'    => 'Themes Load',
			'category' => 'theme',
		);

		$stops['init'][- 1] = array(
			'event'    => 'Core Init',
			'category' => 'core',
		);

		$stops['wp_loaded'][20] = array(
			'action'   => 'stop',
			'event'    => 'Core Init',
			'category' => 'core',
		);

		$stops['loop_start'][10] = array(
			'event'    => 'Main Loop',
			'category' => 'theme',
		);

		$stops['loop_end'][10] = array(
			'action'   => 'stop',
			'event'    => 'Main Loop',
			'category' => 'theme',
		);

		return $stops;
	}
}