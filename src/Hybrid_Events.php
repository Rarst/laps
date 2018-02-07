<?php

namespace Rarst\Laps;

/**
 * Events for Hybrid Core based themes
 *
 * @link http://themehybrid.com/hybrid-core
 */
class Hybrid_Events extends Laps_Events {

	/**
	 * @return bool
	 */
	protected function is_applicable() {

		return defined( 'HYBRID_VERSION' ) && function_exists( 'hybrid_get_prefix' );
	}

	/**
	 * @return array
	 */
	protected function get_events() {

		$events = array();
		$prefix = hybrid_get_prefix();

		$events["{$prefix}_before_header"][10] = array(
			'event'    => 'Header',
			'category' => 'theme',
		);

		$events["{$prefix}_after_header"][10] = array(
			'action'   => 'stop',
			'event'    => 'Header',
			'category' => 'theme',
		);

		$sidebars = get_theme_support( 'hybrid-core-sidebars' );

		if ( ! empty( $sidebars[0] ) ) {
			foreach ( $sidebars[0] as $sidebar ) {

				$events["{$prefix}_before_{$sidebar}"][10] = array(
					'event'    => "Sidebar ({$sidebar})",
					'category' => 'theme',
				);

				$events["{$prefix}_after_{$sidebar}"][10] = array(
					'action'   => 'stop',
					'event'    => "Sidebar ({$sidebar})",
					'category' => 'theme',
				);
			}
		}

		return $events;
	}
}
