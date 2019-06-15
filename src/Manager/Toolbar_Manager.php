<?php
declare( strict_types=1 );

namespace Rarst\Laps\Manager;

use Mustache_Engine;
use Rarst\Laps\Record\Collector\Record_Collector_Interface;
use Rarst\Laps\Record\Iterator\Recursive_Record_Iterator;
use Rarst\Laps\Plugin;
use Rarst\Laps\Record\Iterator\Timeline_Iterator;

/**
 * Implements toolbar menu and visualization.
 */
class Toolbar_Manager {

	/** @var Record_Collector_Interface $collector */
	protected $collector;

	/** @var Mustache_Engine */
	private $mustache;

	/**
	 * @param Record_Collector_Interface $collector Collector of all records..
	 * @param Mustache_Engine            $mustache  Mustache instance.
	 */
	public function __construct( Record_Collector_Interface $collector, Mustache_Engine $mustache ) {

		$this->collector = $collector;
		$this->mustache  = $mustache;

		add_action( 'admin_bar_menu', [ $this, 'admin_bar_menu' ], 1000 );
	}

	/**
	 * Render interface and add to the toolbar.
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar WordPress core toolbar object.
	 */
	public function admin_bar_menu( \WP_Admin_Bar $wp_admin_bar ): void {

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
			'meta'   => [ // TODO consider doing render lazily, but might not be worth the effort.
				'html' => $this->mustache->render( 'laps', [
					'timelines' => new Timeline_Iterator( new Recursive_Record_Iterator( $this->collector->get_records() ) ),
				] ),
			],
		] );
	}
}
