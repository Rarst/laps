<?php

namespace Rarst\Laps\Event;

/**
 * Events for Yoast plugins
 */
class Yoast_Events implements Hook_Event_Config_Interface {

	/**
	 * @return array
	 */
	public function get_events() {

		$stops = [];

		if ( ! class_exists( 'WPSEO_Frontend' ) ) {
			return $stops;
		}

		$stops['wpseo_head'][1] = [
			'event'    => 'WP SEO head hook',
			'category' => 'plugin',
		];

		$stops['wpseo_head'][100] = [
			'action'   => 'stop',
			'event'    => 'WP SEO head hook',
			'category' => 'plugin',
		];

		return $stops;
	}
}
