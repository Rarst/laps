<?php

namespace Rarst\Laps\Manager;

/**
 * Implements load of necessary front–end assets.
 */
class Asset_Manager {

	/**
	 * Sets up the hooks.
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Registers assets and queues as necessary.
	 */
	public function enqueue_scripts() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_register_style( 'laps', plugins_url( "public/css/laps{$suffix}.css", dirname( __DIR__ ) ) );

		if ( is_admin_bar_showing() ) {
			wp_enqueue_style( 'laps' );
		}
	}
}
