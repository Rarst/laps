<?php

namespace Rarst\Laps;

/**
 * Main set of events for WordPress core
 */
class Core_Events extends Laps_Events {

	/**
	 * @return array
	 */
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

		$stops['admin_init'][- 1] = array(
			'event'    => 'Core Admin Init',
			'category' => 'admin',
		);

		$stops['admin_init'][ PHP_INT_MAX -1 ] = array(
			'action'   => 'stop',
			'event'    => 'Core Admin Init',
			'category' => 'admin',
		);

		$stops['_admin_menu'][- 1] = array(
			'event'    => 'Admin Menu',
			'category' => 'admin',
		);
		$stops['admin_menu'][ PHP_INT_MAX -1 ] = array(
			'action'   => 'stop',
			'event'    => 'Admin Menu',
			'category' => 'admin',
		);

		$stops['admin_bar_menu'][-1] = array(
			'event'    => 'Admin Bar',
			'category' => 'admin',
		);
		$stops['admin_bar_menu'][ PHP_INT_MAX -1 ] = array(
			'action'   => 'stop',
			'event'    => 'Admin Bar',
			'category' => 'admin',
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

		$stops['admin_enqueue_scripts'][10] = array(
			'event'    => 'Admin Print Scripts',
			'category' => 'admin',
		);

		$stops['admin_print_scripts'][10] = array(
			'action'   => 'stop',
			'event'    => 'Admin Print Scripts',
			'category' => 'admin',
		);

		return $stops;
	}
}