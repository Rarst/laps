<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Main set of events for WordPress core
 */
class Core_Events implements Hook_Event_Config_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_events(): array {

		return [
			[ 'Plugins Load', 'plugin', '', 'plugins_loaded', - 1, - 2 ],
			[ 'Plugins Loaded Hook', 'plugin', 'plugins_loaded' ],
			[ 'Themes Load', 'theme', 'setup_theme', 'after_setup_theme' ],
			[ 'Init Hook', 'core', 'init' ],
			[ 'WP Loaded Hook', 'core', 'wp_loaded' ],
			[ 'Query Setup', 'core', 'wp_loaded', 'template_redirect', PHP_INT_MAX, -1 ],
			[ 'Template Loader', 'theme', 'template_redirect', 'template_include' ],
			[ 'Admin Init Hook', 'core', 'admin_init' ],
			[ 'Admin Menu', 'core', '_admin_menu', 'admin_menu' ],
			[ 'Admin Print Scripts', 'core', 'admin_enqueue_scripts', 'admin_print_scripts' ],
			[ 'Head Hook', 'theme', 'wp_head' ],
			[ 'Main Loop', 'theme', 'loop_start', 'loop_end' ],
			[ 'Sidebar', 'theme', 'dynamic_sidebar_before', 'dynamic_sidebar_after' ],
			[ 'Toolbar', 'core', 'admin_bar_menu', '' ],
			[ 'Footer Hook', 'theme', 'wp_footer' ],
		];
	}
}
