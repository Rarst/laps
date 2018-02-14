<?php

namespace Rarst\Laps\Event;

/**
 * Events for Genesis Framework based themes
 *
 * @link http://my.studiopress.com/themes/genesis/
 */
class Genesis_Events implements Hook_Event_Config_Interface {

	/**
	 * @return array
	 */
	public function get_events() {

		$events = [];

		if ( ! function_exists( 'genesis' ) ) {
			return $events;
		}

		$events['genesis_before_header'][10] = [
			'event'    => 'Header',
			'category' => 'theme',
		];

		$events['genesis_after_header'][10] = [
			'action'   => 'stop',
			'event'    => 'Header',
			'category' => 'theme',
		];

		$events['genesis_before_sidebar_widget_area'][10] = [
			'event'    => 'Sidebar',
			'category' => 'theme',
		];

		$events['genesis_after_sidebar_widget_area'][10] = [
			'action'   => 'stop',
			'event'    => 'Sidebar',
			'category' => 'theme',
		];

		$events['genesis_before_sidebar_alt_widget_area'][10] = [
			'event'    => 'Sidebar (alternate)',
			'category' => 'theme',
		];

		$events['genesis_after_sidebar_alt_widget_area'][10] = [
			'action'   => 'stop',
			'event'    => 'Sidebar (alternate)',
			'category' => 'theme',
		];

		return $events;
	}
}
