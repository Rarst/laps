<?php

namespace Rarst\Laps;

/**
 * Events for Theme Hook Alliance
 *
 * @link http://zamoose.github.io/themehookalliance/
 */
class THA_Events extends Laps_Events {

	/**
	 * @return bool
	 */
	protected function is_applicable() {

		return defined( 'THA_HOOKS_VERSION' );
	}

	/**
	 * @return array
	 */
	protected function get_events() {

		$events = array();

		$events['tha_header_before'][10] = array(
			'event'    => 'Header',
			'category' => 'theme',
		);

		$events['tha_header_after'][10] = array(
			'action'   => 'stop',
			'event'    => 'Header',
			'category' => 'theme',
		);

		$events['tha_sidebars_before'][10] = array(
			'event'    => 'Sidebars',
			'category' => 'theme',
		);

		$events['tha_sidebars_after'][10] = array(
			'action'   => 'stop',
			'event'    => 'Sidebars',
			'category' => 'theme',
		);

		return $events;
	}
}
