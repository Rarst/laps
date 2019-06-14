<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Events for Theme Hook Alliance
 *
 * @link http://zamoose.github.io/themehookalliance/
 *
 * @deprecated 3.2:4.0 Events going to be moved to Extension_Events class.
 */
class THA_Events implements Hook_Event_Config_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_events(): array {

		return \defined( 'THA_HOOKS_VERSION' ) ? [
			[ 'Header', 'theme', 'tha_header_before', 'tha_header_after' ],
			[ 'Sidebars', 'theme', 'tha_sidebars_before', 'tha_sidebars_after' ],
		] : [];
	}
}
