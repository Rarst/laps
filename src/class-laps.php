<?php

namespace Rarst\Laps;

use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Main plugin's class.
 */
class Laps {

	/**  @var Stopwatch $stopwatch */
	public static $stopwatch;
	public static $events = array();
	public static $query_starts = array();

	/**
	 * Start Stopwatch and timing plugin load immediately, then set up core events and needed hooks.
	 */
	static function on_load() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		self::$stopwatch = new Stopwatch();
		self::$stopwatch->start( 'Plugins Load', 'plugin' );
		$events = new Core_Events();
		self::add_events( $events->get() );

		add_action( 'pre_update_option_active_plugins', array( __CLASS__, 'pre_update_option_active_plugins' ) );
		add_action( 'pre_update_site_option_active_sitewide_plugins', array( __CLASS__, 'pre_update_option_active_plugins' ) );
		add_action( 'pre_http_request', array( __CLASS__, 'pre_http_request' ), 10, 3 );
		add_action( 'http_api_debug', array( __CLASS__, 'http_api_debug' ), 10, 5 );
		add_action( 'after_setup_theme', array( __CLASS__, 'after_setup_theme' ), 15 );
		add_action( 'init', array( __CLASS__, 'init' ) );

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
			add_filter( 'query', array( __CLASS__, 'query' ), 20 );
		}
	}

	/**
	 * Hook events by name and priority from array.
	 *
	 * @param array $stops
	 */
	static function add_events( $stops ) {

		self::$events = array_merge( self::$events, $stops );

		foreach ( $stops as $hook_name => $data ) {

			foreach ( array_keys( $data ) as $priority ) {

				add_action( $hook_name, array( __CLASS__, 'tick' ), $priority );
			}
		}
	}

	/**
	 * Reorder active plugins so Laps is first and starts timing load early.
	 *
	 * @param array $plugins
	 *
	 * @return array
	 */
	static function pre_update_option_active_plugins( $plugins ) {

		$plugin = plugin_basename( dirname( __DIR__ ) . '/laps.php' );
		$key    = array_search( $plugin, $plugins );

		if ( false !== $key && $key > 0 ) {

			unset( $plugins[$key] );
			array_unshift( $plugins, $plugin );
			$plugins = array_values( $plugins );
		}

		return $plugins;
	}

	/**
	 * Mark action for the event on Stopwatch.
	 *
	 * @param mixed $input pass through if added to filter
	 *
	 * @return mixed
	 */
	static function tick( $input = null ) {

		global $wp_filter;

		$filter   = current_filter();
		$priority = key( $wp_filter[$filter] );

		$event = wp_parse_args(
			self::$events[$filter][$priority],
			array(
				'action'   => 'start',
				'category' => null,
			)
		);

		self::$stopwatch->$event['action']( $event['event'], $event['category'] );

		return $input;
	}

	/**
	 * Capture SQL queries start times
	 *
	 * @param string $query
	 *
	 * @return string
	 */
	static function query( $query ) {

		global $wpdb;

		if ( empty( self::$query_starts ) && ! empty( $wpdb->queries ) ) {
			self::$query_starts[count( $wpdb->queries )] = microtime( true ) * 1000;
		} else {
			self::$query_starts[] = microtime( true ) * 1000;
		}

		return $query;
	}

	static function pre_http_request( $false, $args, $url ) {

		self::$stopwatch->start( $url, 'http' );

		return $false;
	}

	static function http_api_debug( $response, $type, $class, $args, $url ) {

		self::$stopwatch->stop( $url );

		return $response;
	}

	/**
	 * When theme is done possibly add theme-specific events.
	 */
	static function after_setup_theme() {

		foreach ( array( 'THA', 'Hybrid', 'Genesis', 'Thematic' ) as $theme ) {

			$class = "Rarst\\Laps\\{$theme}_Events";
			/** @var Laps_Events $events */
			$events = new $class;
			self::add_events( $events->get() );
		}
	}

	static function init() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'admin_bar_menu', array( __CLASS__, 'admin_bar_menu' ), 100 );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'print_footer_scripts' ), 11 );
		add_action( 'admin_print_footer_scripts', array( __CLASS__, 'print_footer_scripts' ), 11 );
	}

	static function enqueue_scripts() {

		wp_register_script( 'laps-tooltip', plugins_url( 'js/tooltip.js', __DIR__ ), array( 'jquery' ), '3.0.2', true );
		wp_register_style( 'laps', plugins_url( 'css/laps.css', __DIR__ ) );

		if ( is_admin_bar_showing() ) {
			wp_enqueue_script( 'laps-tooltip' );
			wp_enqueue_style( 'laps' );
		}
	}

	static function print_footer_scripts() {

		if ( wp_script_is( 'laps-tooltip', 'done' ) ) {
			?>
			<script type="text/javascript">
				jQuery(document).ready(function () {
					jQuery(".laps-timeline .event").lapstooltip({
						container: '#wpadminbar', placement: 'bottom', html: true
//						, trigger: 'click'
					});
				});
			</script><?php
		}
	}

	/**
	 * Render interface and add to the toolbar.
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar
	 */
	static function admin_bar_menu( $wp_admin_bar ) {

		if ( ! apply_filters( 'laps_can_see', current_user_can( 'manage_options' ) ) ) {
			return;
		}

		global $timestart, $wpdb;

		$mustache = new \Mustache_Engine(
			array(
				'loader' => new \Mustache_Loader_FilesystemLoader( dirname( __DIR__ ) . '/views' ),
			)
		);

		$events     = self::$stopwatch->getSectionEvents( '__root__' );
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

			if ( 'http' == $category ) {
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
		$category       = 'query';

		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {

			foreach ( $wpdb->queries as $key => $query ) {
				$query_start = isset( self::$query_starts[$key] ) ? self::$query_starts[$key] : $last_query_end;
				list( $sql, $duration, $trace ) = $query;
				$duration *= 1000;
				$last_query_end = $query_start + $duration;
				$offset         = round( ( $query_start - $start ) / $total * 100, 2 );

				// if query is indistinguishably close to previous then stack it
				if ( $offset == $last_offset ) {
					$key = count( $query_data ) - 1;
					$query_data[$key]['sql'] .= '<br />' . $sql;
					$last_duration += $duration;
					$width = round( $last_duration / $total * 100, 2 );

					if ( 0 == $width ) {
						$width = '2px';
					} else {
						$width .= '%';
					}

					$query_data[$key]['width'] = $width;

					continue;
				}

				$width         = round( $duration / $total * 100, 2 );
				$last_offset   = $offset;
				$last_duration = $duration;

				if ( 0 == $width ) {
					$width = '2px';
				} else {
					$width .= '%';
				}

				$query_data[] = compact( 'sql', 'duration', 'offset', 'width', 'category' );
			}
		}

//		!d( self::$query_starts, $wpdb->queries, $query_data );

		$html = $mustache->render(
			'laps',
			array(
				'events'      => $event_data,
				'queries'     => $query_data,
				'savequeries' => defined( 'SAVEQUERIES' ) && SAVEQUERIES,
				'http'        => $http_data,
				'savehttp'    => ! empty( $http_data ),
			)
		);

		$wp_admin_bar->add_node(
			array(
				'id'    => 'laps',
				'title' => sprintf( 'Lap: %ss', round( $total / 1000, 3 ) ),
			)
		);

		$wp_admin_bar->add_node(
			array(
				'id'     => 'laps_output',
				'parent' => 'laps',
				'meta'   => array( 'html' => $html ),
			)
		);
	}
}