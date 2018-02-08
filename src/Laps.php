<?php

namespace Rarst\Laps;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Events\Events_Provider_Interface;
use Rarst\Laps\Events\Hook_Events_Provider;
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

		$laps['stopwatch'] = function () {
			return new Stopwatch();
		};

		$laps->register( new Hook_Events_Provider() );

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

		$this['stopwatch']->start( 'Plugins Load', 'plugin' );

		foreach ( $this->providers as $provider ) {

			if ( $provider instanceof Bootable_Provider_Interface ) {
				$provider->boot( $this );
			}
		}

		add_action( 'pre_update_option_active_plugins', array( $this, 'pre_update_option_active_plugins' ) );
		add_action( 'pre_update_site_option_active_sitewide_plugins', array( $this, 'pre_update_option_active_plugins' ) );
		add_action( 'pre_http_request', array( $this, 'pre_http_request' ), 10, 3 );
		add_action( 'http_api_debug', array( $this, 'http_api_debug' ), 10, 5 );
		add_action( 'init', array( $this, 'init' ) );

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
			add_filter( 'query', array( $this, 'query' ), 20 );
		}
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

	/**
	 * Capture SQL queries start times
	 *
	 * @param string $query
	 *
	 * @return string
	 */
	public function query( $query ) {

		global $wpdb;

		if ( empty( $this->query_starts ) && ! empty( $wpdb->queries ) ) {
			$this->query_starts[ count( $wpdb->queries ) ] = microtime( true ) * 1000;
		} else {
			$this->query_starts[] = microtime( true ) * 1000;
		}

		return $query;
	}

	/**
	 * Capture start time of HTTP request
	 *
	 * @param boolean $false
	 * @param array   $args
	 * @param string  $url
	 *
	 * @return boolean
	 */
	public function pre_http_request( $false, $args, $url ) {

		$this['stopwatch']->start( $url, 'http' );

		return $false;
	}

	/**
	 * Capture end time of HTTP request
	 *
	 * @param array|\WP_Error $response
	 * @param string          $type
	 * @param object          $class
	 * @param array           $args
	 * @param string          $url
	 *
	 * @return mixed
	 */
	public function http_api_debug( $response, $type, $class, $args, $url ) {

		$this['stopwatch']->stop( $url );

		return $response;
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

		global $timestart, $wpdb;

		/** @var Stopwatch $stopwatch */
		$stopwatch = $this['stopwatch'];

		if ( $stopwatch->isStarted( 'Toolbar' ) ) {
			$stopwatch->stop( 'Toolbar' );
		}

		$events = [];

		foreach ( $this->providers as $provider ) {

			if ( $provider instanceof Events_Provider_Interface ) {
				$events[] = $provider->get_events();
			}
		}

		$events     = array_merge(...$events);
		$start      = $timestart * 1000;
		$end        = microtime( true ) * 1000;
		$total      = $end - $start;
		$event_data = array();
		$http_data  = array();

		foreach ( $events as $name => $event ) {

			$offset   = round( ( $event->getOrigin() - $start ) / $total * 100, 2 );
			$duration = $event->getDuration();
			$width    = round( $duration / $total * 100, 2 );
			$category = $event->getCategory();

			if ( 'http' === $category ) {
				$http_data[] = compact( 'name', 'offset', 'duration', 'width', 'category' );
				continue;
			}

			$memory = $event->getMemory() / 1024 / 1024;

			$event_data[] = compact( 'name', 'offset', 'duration', 'width', 'category', 'memory' );
		}

		$query_data     = array();
		$last_query_end = 0;
		$last_offset    = 0;
		$last_duration  = 0;

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {

			foreach ( $wpdb->queries as $key => $query ) {
				$query_start = isset( $this->query_starts[ $key ] ) ? $this->query_starts[ $key ] : $last_query_end;
				list( $sql, $duration, $trace ) = $query;
				$sql      = trim( $sql );
				$category = 'query-read';

				if ( 0 === stripos( $sql, 'INSERT' ) || 0 === stripos( $sql, 'UPDATE' ) ) {
					$category = 'query-write';
				}

				$duration *= 1000;
				$last_query_end = $query_start + $duration;
				$offset         = round( ( $query_start - $start ) / $total * 100, 2 );

				// if query is indistinguishably close to previous then stack it
				if ( $offset === $last_offset ) {
					$key = count( $query_data ) - 1;
					$query_data[ $key ]['sql'] .= '<br />' . $sql;

					$last_duration += $duration;
					$width                       = round( $last_duration / $total * 100, 2 );
					$query_data[ $key ]['width'] = $width;

					continue;
				}

				$width         = round( $duration / $total * 100, 2 );
				$last_offset   = $offset;
				$last_duration = $duration;

				$query_data[] = compact( 'sql', 'duration', 'offset', 'width', 'category' );
			}
		}

		$html = $this['mustache']->render( 'laps', [
			'events'      => $event_data,
			'queries'     => $query_data,
			'savequeries' => defined( 'SAVEQUERIES' ) && SAVEQUERIES,
			'http'        => $http_data,
			'savehttp'    => ! empty( $http_data ),
		] );

		$wp_admin_bar->add_node( array(
			'id'    => 'laps',
			'title' => sprintf( 'Lap: %ss', round( $total / 1000, 3 ) ),
		) );

		$wp_admin_bar->add_node( array(
			'id'     => 'laps_output',
			'parent' => 'laps',
			'meta'   => array( 'html' => $html ),
		) );
	}
}
