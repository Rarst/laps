<?php

namespace Rarst\Laps\Manager;

use Rarst\Laps\Record\Recursive_Record_Iterator;
use Rarst\Laps\Laps;
use Rarst\Laps\Record\Timeline_Iterator;

class Toolbar_Manager {

	/** @var Laps $laps */
	protected $laps;

	public function __construct( Laps $laps ) {

		$this->laps = $laps;

		add_action( 'admin_bar_menu', [ $this, 'admin_bar_menu' ], 100 );
	}

	/**
	 * Render interface and add to the toolbar.
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 */
	public function admin_bar_menu( $wp_admin_bar ) {

		if ( ! apply_filters( 'laps_can_see', current_user_can( 'manage_options' ) ) ) {
			return;
		}

		global $timestart;

		$start = $timestart * 1000;
		$end   = microtime( true ) * 1000;
		$total = $end - $start;

		$wp_admin_bar->add_node( [
			'id'    => 'laps',
			'title' => sprintf( 'Lap: %ss', round( $total / 1000, 3 ) ),
		] );

		$wp_admin_bar->add_node( [
			'id'     => 'laps_output',
			'parent' => 'laps',
			'meta'   => [
				'html' => $this->laps['mustache']->render( 'laps', [
					'timelines' => new Timeline_Iterator( new Recursive_Record_Iterator( $this->laps['records'] ) ),
				] ),
			],
		] );
	}
}
