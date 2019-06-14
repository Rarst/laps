<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Events for Genesis Framework based themes
 *
 * @link http://my.studiopress.com/themes/genesis/
 *
 * @deprecated 3.2:4.0 Events going to be moved to Extension_Events class.
 */
class Genesis_Events implements Hook_Event_Config_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_events(): array {

		return \function_exists( 'genesis' ) ? [
			[ 'Header', 'theme', 'genesis_before_header', 'genesis_after_header' ],
			[ 'Sidebar', 'theme', 'genesis_before_sidebar_widget_area', 'genesis_after_sidebar_widget_area' ],
			[
				'Sidebar (alternate)',
				'theme',
				'genesis_before_sidebar_alt_widget_area',
				'genesis_after_sidebar_alt_widget_area',
			],
		] : [];
	}
}
