<?php

namespace Rarst\Laps\Event;

/**
 * Events for Hybrid Core based themes
 *
 * @link http://themehybrid.com/hybrid-core
 */
class Hybrid_Events implements Hook_Event_Config_Interface {

	/**
	 * @return array
	 */
	public function get_events() {

		$events = [];

		if ( ! function_exists( 'hybrid_get_prefix' ) ) {
			return $events;
		}

		$prefix = hybrid_get_prefix();

		$events["{$prefix}_before_header"][10] = [
			'event'    => 'Header',
			'category' => 'theme',
		];

		$events["{$prefix}_after_header"][10] = [
			'action'   => 'stop',
			'event'    => 'Header',
			'category' => 'theme',
		];

		$sidebars = get_theme_support( 'hybrid-core-sidebars' );

		if ( ! empty( $sidebars[0] ) ) {
			foreach ( $sidebars[0] as $sidebar ) {

				$events["{$prefix}_before_{$sidebar}"][10] = [
					'event'    => "Sidebar ({$sidebar})",
					'category' => 'theme',
				];

				$events["{$prefix}_after_{$sidebar}"][10] = [
					'action'   => 'stop',
					'event'    => "Sidebar ({$sidebar})",
					'category' => 'theme',
				];
			}
		}

		return $events;
	}
}
