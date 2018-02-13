<?php

namespace Rarst\Laps\Event;

/**
 * Main set of events for WordPress core
 */
class Core_Events implements Hook_Event_Config_Interface {

	/**
	 * @return array
	 */
	public function get_events() {

		$stops = [];

		$stops['plugins_loaded'][-2] = [
			'action'   => 'stop',
			'event'    => 'Plugins Load',
			'category' => 'plugin',
		];

		$stops['plugins_loaded'][-1] = [
			'event'    => 'Plugins Loaded Hook',
			'category' => 'plugin',
		];

		$stops['plugins_loaded'][20] = [
			'action'   => 'stop',
			'event'    => 'Plugins Loaded Hook',
			'category' => 'plugin',
		];

		$stops['setup_theme'][0] = [
			'event'    => 'Themes Load',
			'category' => 'theme',
		];

		$stops['after_setup_theme'][20] = [
			'action'   => 'stop',
			'event'    => 'Themes Load',
			'category' => 'theme',
		];

		$stops['init'][-1] = [
			'event'    => 'Core Init',
			'category' => 'core',
		];

		$stops['wp_loaded'][20] = [
			'action'   => 'stop',
			'event'    => 'Core Init',
			'category' => 'core',
		];

		$stops['admin_init'][-1] = [
			'event'    => 'Core Admin Init',
			'category' => 'core',
		];

		$stops['admin_init'][ PHP_INT_MAX - 1 ] = [
			'action'   => 'stop',
			'event'    => 'Core Admin Init',
			'category' => 'core',
		];

		$stops['_admin_menu'][-1] = [
			'event'    => 'Admin Menu',
			'category' => 'core',
		];

		$stops['admin_menu'][ PHP_INT_MAX - 1 ] = [
			'action'   => 'stop',
			'event'    => 'Admin Menu',
			'category' => 'core',
		];

		$stops['admin_bar_menu'][-1] = [
			'event'    => 'Toolbar',
			'category' => 'core',
		];

		$stops['loop_start'][10] = [
			'event'    => 'Main Loop',
			'category' => 'theme',
		];

		$stops['loop_end'][10] = [
			'action'   => 'stop',
			'event'    => 'Main Loop',
			'category' => 'theme',
		];

		$stops['admin_enqueue_scripts'][10] = [
			'event'    => 'Admin Print Scripts',
			'category' => 'core',
		];

		$stops['admin_print_scripts'][10] = [
			'action'   => 'stop',
			'event'    => 'Admin Print Scripts',
			'category' => 'core',
		];

		return $stops;
	}
}
