<?php

namespace Rarst\Laps;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Events\Events_Provider_Interface;
use Rarst\Laps\Events\Hook_Events_Provider;
use Rarst\Laps\Events\Http_Events_Provider;
use Rarst\Laps\Events\Sql_Events_Provider;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Main plugin's class.
 */
class Laps extends Container {

	public $query_starts = array();

	protected $providers = [];

	public function __construct( array $values = [] ) {

		parent::__construct();

		$laps = $this;

		$laps['mustache'] = function () {
			return new \Mustache_Engine( [
				'loader' => new \Mustache_Loader_FilesystemLoader( dirname( __DIR__ ) . '/views' ),
				'cache'  => new Mustache_Cache_FrozenCache( dirname( __DIR__ ) . '/views/cache' ),
			] );
		};

		$laps['stopwatch'] = $laps->factory( function () {
			return new Stopwatch();
		} );

		$laps->register( new Hook_Events_Provider() );
		$laps->register( new Http_Events_Provider() );
		$laps->register( new Sql_Events_Provider() );

		foreach ( $values as $key => $value ) {
			$this->offsetSet( $key, $value );
		}
	}

	public function register( ServiceProviderInterface $provider, array $values = [] ) {

		$this->providers[] = $provider;

		parent::register( $provider, $values );

		return $this;
	}

	/**
	 * Start Stopwatch and timing plugin load immediately, then set up core events and needed hooks.
	 */
	public function on_load() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		foreach ( $this->providers as $provider ) {

			if ( $provider instanceof Bootable_Provider_Interface ) {
				$provider->boot( $this );
			}
		}

		add_action( 'pre_update_option_active_plugins', array( $this, 'pre_update_option_active_plugins' ) );
		add_action( 'pre_update_site_option_active_sitewide_plugins', array( $this, 'pre_update_option_active_plugins' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Reorder active plugins so Laps is first and starts timing load early.
	 *
	 * @param array $plugins
	 *
	 * @return array
	 */
	public function pre_update_option_active_plugins( $plugins ) {

		$plugin = plugin_basename( dirname( __DIR__ ) . '/laps.php' );
		$key    = array_search( $plugin, $plugins );

		if ( false !== $key && $key > 0 ) {

			unset( $plugins[ $key ] );
			array_unshift( $plugins, $plugin );
			$plugins = array_values( $plugins );
		}

		return $plugins;
	}

	public function init() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );
	}

	public function enqueue_scripts() {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_register_script( 'laps', plugins_url( "js/laps{$suffix}.js", __DIR__ ), array( 'jquery' ), '3.3.1', true );
		wp_register_style( 'laps', plugins_url( "css/laps{$suffix}.css", __DIR__ ) );

		if ( is_admin_bar_showing() ) {
			wp_enqueue_script( 'laps' );
			wp_enqueue_style( 'laps' );
		}
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

		$events = [];

		foreach ( $this->providers as $provider ) {

			if ( $provider instanceof Events_Provider_Interface ) {
				$events[] = $provider->get_events();
			}
		}

		$events     = array_merge( ...$events );
		$start      = $timestart * 1000;
		$end        = microtime( true ) * 1000;
		$total      = $end - $start;
		$event_data = [];
		$http_data  = [];
		$query_data = [];

		foreach ( $events as $event ) {

			$event['offset']      = round( ( $event['origin'] - $start ) / $total * 100, 2 );
			$event['width']       = round( $event['duration'] / $total * 100, 2 );

			switch ($event['category']) {
				case 'http':
					$http_data[] = $event;
					continue 2;

				case 'query-read':
				case 'query-write':
					$query_data[] = $event;
					continue 2;
			}

			$event_data[] = $event;
		}

		$timelines = array_filter( [
			[ 'events' => $event_data ],
			[ 'events' => $query_data ],
			[ 'events' => $http_data ],
		], function ( $data ) {
			return ! empty( $data['events'] );
		} );

		$wp_admin_bar->add_node( [
			'id'    => 'laps',
			'title' => sprintf( 'Lap: %ss', round( $total / 1000, 3 ) ),
		] );

		$wp_admin_bar->add_node( [
			'id'     => 'laps_output',
			'parent' => 'laps',
			'meta'   => [ 'html' => $this['mustache']->render( 'laps', [ 'timelines' => $timelines ] ) ],
		] );
	}
}
