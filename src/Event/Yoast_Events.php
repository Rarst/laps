<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Events for Yoast plugins
 */
class Yoast_Events implements Hook_Event_Config_Interface {

	/**
	 * @return array
	 */
	public function get_events(): array {

		return class_exists( 'WPSEO_Frontend' ) ? [
			[ 'WP SEO head hook', 'plugin', 'wpseo_head' ],
		] : [];
	}
}
