<?php

namespace Rarst\Laps;

/**
 * Events for Thematic Framework based themes
 *
 * @link http://thematictheme.com/
 */
class Thematic_Events extends Laps_Events {

	/**
	 * @return bool
	 */
	protected function is_applicable() {

		return function_exists( 'thematic_init' );
	}

	/**
	 * @return array
	 */
	protected function get_events() {

		$events = array();

		$events['thematic_aboveheader'][10] = array(
			'event'    => 'Header',
			'category' => 'theme',
		);

		$events['thematic_belowheader'][10] = array(
			'action'   => 'stop',
			'event'    => 'Header',
			'category' => 'theme',
		);

		$events['widget_area_primary_aside'][10] = array(
			'event'    => 'Sidebar (primary aside)',
			'category' => 'theme',
		);

		$events['thematic_betweenmainasides'][10] = array(
			'action'   => 'stop',
			'event'    => 'Sidebar (primary aside)',
			'category' => 'theme',
		);

		$events['widget_area_secondary_aside'][10] = array(
			'event'    => 'Sidebar (secondary aside)',
			'category' => 'theme',
		);

		$events['thematic_belowmainasides'][10] = array(
			'action'   => 'stop',
			'event'    => 'Sidebar (secondary aside)',
			'category' => 'theme',
		);

		return $events;
	}
}
