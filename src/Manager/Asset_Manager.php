<?php

namespace Rarst\Laps\Manager;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Bootable_Provider_Interface;
use Rarst\Laps\Laps;

class Asset_Manager implements ServiceProviderInterface, Bootable_Provider_Interface {

	public function register( Container $pimple ) {

	}

	public function boot( Laps $laps ) {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_register_script( 'laps', plugins_url( "js/laps{$suffix}.js", dirname( __DIR__ ) ), [ 'jquery' ], '3.3.1', true );
		wp_register_style( 'laps', plugins_url( "css/laps{$suffix}.css", dirname( __DIR__ ) ) );

		if ( is_admin_bar_showing() ) {
			wp_enqueue_script( 'laps' );
			wp_enqueue_style( 'laps' );
		}
	}

}
