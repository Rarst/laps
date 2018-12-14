<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Main set of events for WordPress core
 */
class Core_Events implements Hook_Event_Config_Interface {

	/**
	 * @return array
	 */
	public function get_events(): array {

		return [
			[ 'Plugins Load', 'plugin', '', 'plugins_loaded', - 1, - 2 ],
			[ 'Plugins Loaded Hook', 'plugin', 'plugins_loaded' ],
			[ 'Themes Load', 'theme', 'setup_theme', 'after_setup_theme' ],
			[ 'Core Init', 'core', 'init', 'wp_loaded' ],
			[ 'Admin Init', 'core', 'admin_init' ],
			[ 'Admin Menu', 'core', '_admin_menu', 'admin_menu' ],
			[ 'Toolbar', 'core', 'admin_bar_menu', '' ],
			[ 'Main Loop', 'theme', 'loop_start', 'loop_end' ],
			[ 'Admin Print Scripts', 'core', 'admin_enqueue_scripts', 'admin_print_scripts' ],
		];
	}
}
