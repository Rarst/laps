<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Events for third party themes and plugins.
 */
class Extension_Events implements Hook_Event_Config_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_events(): array {

		return $this->beaver_builder();
	}

	/**
	 * Beaver Builder events.
	 *
	 * @return array[]
	 */
	private function beaver_builder(): array {

		$plugin = class_exists( 'FLBuilderLoader' ) ? [
			[
				'Beaver Builder Post Content',
				'plugin',
				'fl_builder_before_render_content',
				'fl_builder_after_render_content',
			],
			[
				'Beaver Builder Module',
				'plugin',
				'fl_builder_before_render_module',
				'fl_builder_after_render_module',
			],
		] : [];

		$theme = class_exists( 'FLTheme' ) ? [
			[ 'Beaver Builder Top Bar', 'theme', 'fl_before_top_bar', 'fl_after_top_bar' ],
			[ 'Beaver Builder Header', 'theme', 'fl_before_header', 'fl_after_header' ],
			[ 'Beaver Builder Page Content', 'theme', 'fl_before_content', 'fl_after_content' ],
			[ 'Beaver Builder Post', 'theme', 'fl_before_post', 'fl_after_post' ],
			[ 'Beaver Builder Footer Widgets', 'theme', 'fl_before_footer_widgets', 'fl_after_footer_widgets' ],
			[ 'Beaver Builder Footer', 'theme', 'fl_before_footer', 'fl_after_footer' ],
		] : [];

		return array_merge( $plugin, $theme );
	}
}
