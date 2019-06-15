<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Collector;

use Rarst\Laps\Record\Stopwatch_Record;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Generic Stopwatch–based collector. Can be used by itself or extended.
 */
class Stopwatch_Collector implements Record_Collector_Interface {

	/** @var Stopwatch $stopwatch */
	protected $stopwatch;

	/**
	 * @param Stopwatch $stopwatch Stopwatch instance.
	 */
	public function __construct( Stopwatch $stopwatch ) {
		$this->stopwatch = $stopwatch;
	}

	/**
	 * @param string $name     The event name.
	 * @param string $category The event category.
	 *
	 * @return StopwatchEvent
	 */
	public function start( $name, $category = null ): StopwatchEvent {
		return $this->stopwatch->start( $name, $category );
	}

	/**
	 * @param string $name The event name.
	 *
	 * @return bool|StopwatchEvent
	 */
	public function stop( $name ) {

		if ( $this->stopwatch->isStarted( $name ) ) {
			return $this->stopwatch->stop( $name );
		}

		return false;
	}

	/**
	 * @return Stopwatch_Record[]
	 */
	public function get_records(): array {

		/** @psalm-var array<string, StopwatchEvent> $events */
		$events = $this->stopwatch->getSectionEvents( '__root__' );

		/** @var string $name */
		foreach ( array_keys( $events ) as $name ) {
			if ( $this->stopwatch->isStarted( $name ) ) {
				unset( $events[ $name ] );
			}
		}

		return array_map( [ $this, 'transform' ], array_keys( $events ), $events );
	}

	/**
	 * @param string         $name  Event name.
	 * @param StopwatchEvent $event Stopwatch event instance.
	 *
	 * @return Stopwatch_Record
	 */
	protected function transform( string $name, StopwatchEvent $event ): Stopwatch_Record {

		return new Stopwatch_Record( $name, $event );
	}
}
