<?php

namespace Rarst\Laps\Manager;

use Rarst\Laps\Record\Recursive_Record_Iterator;
use Rarst\Laps\Plugin;
use Rarst\Laps\Record\Timeline_Iterator;

/**
 * Implements toolbar menu and visualization.
 */
class Toolbar_Manager {

	/** @var Plugin $laps */
	protected $laps;

	/**
	 * @param Plugin $laps Container instance.
	 */
	public function __construct( Plugin $laps ) {

		$this->laps = $laps;

		add_action( 'admin_bar_menu', [ $this, 'admin_bar_menu' ], 100 );
	}

	/**
	 * Render interface and add to the toolbar.
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar WordPress core toolbar object.
	 */
	public function admin_bar_menu( $wp_admin_bar ) {

		if ( ! apply_filters( 'laps_can_see', current_user_can( 'manage_options' ) ) ) {
			return;
		}

		global $timestart;

		$wp_admin_bar->add_node( [
			'id'    => 'laps',
			'title' => sprintf( 'Lap: %ss', round( microtime( true ) - $timestart, 3 ) ),
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
