<?php

namespace Rarst\Laps\Event;

/**
 * Events for Theme Hook Alliance
 *
 * @link http://zamoose.github.io/themehookalliance/
 */
class THA_Events implements Hook_Event_Config_Interface {

	/**
	 * @return array
	 */
	public function get_events() {

		$events = [];

		if ( ! defined( 'THA_HOOKS_VERSION' ) ) {
			return $events;
		}

		$events['tha_header_before'][10] = [
			'event'    => 'Header',
			'category' => 'theme',
		];

		$events['tha_header_after'][10] = [
			'action'   => 'stop',
			'event'    => 'Header',
			'category' => 'theme',
		];

		$events['tha_sidebars_before'][10] = [
			'event'    => 'Sidebars',
			'category' => 'theme',
		];

		$events['tha_sidebars_after'][10] = [
			'action'   => 'stop',
			'event'    => 'Sidebars',
			'category' => 'theme',
		];

		return $events;
	}
}
