<?php

namespace Rarst\Laps;

/**
 * Events for Yoast plugins
 */
class Yoast_Events extends Laps_Events {

	/**
	 * @return bool
	 */
	protected function is_applicable() {

		return class_exists( 'WPSEO_Frontend' );
	}

	/**
	 * @return array
	 */
	public function get_events() {

		$stops = array();

		$stops['wpseo_head'][1] = array(
			'event'    => 'WP SEO head hook',
			'category' => 'plugin',
		);

		$stops['wpseo_head'][100] = array(
			'action'   => 'stop',
			'event'    => 'WP SEO head hook',
			'category' => 'plugin',
		);

		return $stops;
	}
}
