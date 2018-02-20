<?php

namespace Rarst\Laps\Record;

use Rarst\Laps\Event\Hook_Event_Config_Interface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Processes events based on hooked starts and stops.
 */
class Hook_Record_Collector extends Stopwatch_Record_Collector {

	/** @var Hook_Event_Config_Interface[] $event_configs */
	protected $event_configs = [];

	/** @var array $events */
	protected $events = [];

	/**
	 * @param Stopwatch $stopwatch     Stopwatch instance.
	 * @param array     $event_configs Starts and stops configuration.
	 */
	public function __construct( Stopwatch $stopwatch, array $event_configs ) {

		parent::__construct( $stopwatch );

		$this->start( 'Plugins Load', 'plugin' );
		$this->add_events( $event_configs['core']->get_events() );
		unset( $event_configs['core'] );
		$this->event_configs = $event_configs;

		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 15 );
	}

	/**
	 * Hook events by name and priority from array.
	 *
	 * @param array $stops Starts and stops to hook.
	 */
	public function add_events( $stops ) {

		$this->events = array_merge( $this->events, $stops );

		foreach ( $stops as $hook_name => $data ) {
			foreach ( array_keys( $data ) as $priority ) {
				add_action( $hook_name, [ $this, 'tick' ], $priority );
			}
		}
	}

	/**
	 * When theme is done possibly add vendor-specific events.
	 */
	public function after_setup_theme() {

		foreach ( $this->event_configs as $config ) {
			$this->add_events( $config->get_events() );
		}
	}

	/**
	 * Mark action for the event on Stopwatch.
	 *
	 * @param mixed $input Pass through if added to filter.
	 *
	 * @return mixed
	 */
	public function tick( $input = null ) {

		global $wp_filter;

		$filter_name     = current_filter();
		$filter_instance = $wp_filter[ $filter_name ];
		$priority        = $filter_instance instanceof \WP_Hook ? $filter_instance->current_priority() : key( $filter_instance );

		// See https://core.trac.wordpress.org/ticket/41185 on broken priority, but more general sanity check.
		if ( empty( $this->events[ $filter_name ][ $priority ] ) ) {
			return $input;
		}

		$event = wp_parse_args( $this->events[ $filter_name ][ $priority ], [
			'action'   => 'start',
			'category' => null,
		] );

		if ( 'start' === $event['action'] ) {
			$this->start( $event['event'], $event['category'] );
		} else {
			$this->stop( $event['event'] );
		}

		return $input;
	}

	/**
	 * @return Stopwatch_Record[]
	 */
	public function get_records() {

		$this->stopwatch->stop( 'Toolbar' );

		return parent::get_records();
	}
}
