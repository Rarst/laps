<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Events for Yoast plugins
 *
 * @deprecated 3.2:4.0 Events going to be moved to Extension_Events class.
 */
class Yoast_Events implements Hook_Event_Config_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_events(): array {

		return class_exists( 'WPSEO_Frontend' ) ? [
			[ 'WP SEO head hook', 'plugin', 'wpseo_head' ],
		] : [];
	}
}
