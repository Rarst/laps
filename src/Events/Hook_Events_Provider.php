<?php

namespace Rarst\Laps\Events;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Bootable_Provider_Interface;
use Rarst\Laps\Laps;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

class Hook_Events_Provider implements ServiceProviderInterface, Bootable_Provider_Interface, Events_Provider_Interface {

	/** @var Stopwatch $stopwatch */
	protected $stopwatch;

	protected $events = [];

	public function register( Container $pimple ) {

	}

	public function boot( Laps $laps ) {

		$this->stopwatch = $laps['stopwatch'];

		$this->stopwatch->start( 'Plugins Load', 'plugin' );

		$events = new Core_Events();
		$this->add_events( $events->get() );

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

		foreach ( [ 'THA', 'Hybrid', 'Genesis', 'Yoast' ] as $vendor ) {

			$class = "Rarst\\Laps\\Events\\{$vendor}_Events";
			/** @var Laps_Events $events */
			$events = new $class;
			$this->add_events( $events->get() );
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

	public function get_events() {

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

		$duration = $event->getDuration();
		$memory   = $event->getMemory() / 1024 / 1024;

		return [
			'name'        => $name,
			'description' => "{$name} - {$duration} ms - {$memory} MB",
			'origin'      => $event->getOrigin(),
			'duration'    => $duration,
			'memory'      => $memory,
			'category'    => $event->getCategory(),
		];
	}
}