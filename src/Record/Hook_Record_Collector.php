<?php

namespace Rarst\Laps\Record;

use Rarst\Laps\Event\Hook_Event_Config_Interface;
use Rarst\Laps\Events\Laps_Events;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

class Hook_Record_Collector implements Record_Collector_Interface {

	/** @var Hook_Event_Config_Interface[] $event_configs  */
	protected $event_configs = [];

	/** @var Stopwatch $stopwatch */
	protected $stopwatch;

	protected $events = [];

	public function __construct( array $event_configs, Stopwatch $stopwatch ) {

		$this->stopwatch = $stopwatch;

		$this->stopwatch->start( 'Plugins Load', 'plugin' );

		$this->add_events( $event_configs['core']->get_events() );

		unset( $event_configs['core'] );
		$this->event_configs = $event_configs;

		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 15 );
	}

	/**
	 * Hook events by name and priority from array.
	 *
	 * @param array $stops
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
	 * @param mixed $input pass through if added to filter
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

		$event = wp_parse_args(
			$this->events[ $filter_name ][ $priority ],
			[
				'action'   => 'start',
				'category' => null,
			]
		);

		if ( 'stop' === $event['action'] && ! $this->stopwatch->isStarted( $event['event'] ) ) {
			return $input;
		}

		$this->stopwatch->{$event['action']}( $event['event'], $event['category'] );

		return $input;
	}

	public function get_records() {

		if ( $this->stopwatch->isStarted( 'Toolbar' ) ) {
			$this->stopwatch->stop( 'Toolbar' );
		}

		$events = $this->stopwatch->getSectionEvents( '__root__' );

		foreach ( array_keys( $events ) as $name ) {
			if ( $this->stopwatch->isStarted( $name ) ) {
				unset( $events[ $name ] );
			}
		}

		return array_map( [ $this, 'transform' ], array_keys( $events ), $events );
	}

	protected function transform( $name, StopwatchEvent $event ) {

		return new Stopwatch_Record($name, $event );
	}
}