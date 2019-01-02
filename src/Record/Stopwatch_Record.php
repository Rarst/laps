<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record;

use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Record wrapper for an event recorded with Stopwatch.
 */
class Stopwatch_Record implements Record_Interface {

	/** @var string $name */
	protected $name;

	/** @var StopwatchEvent */
	protected $stopwatch_event;

	/** @var string */
	protected $description = '';

	/**
	 * @param string         $name            Event name.
	 * @param StopwatchEvent $stopwatch_event Stopwatch event instance.
	 * @param string         $description     Optional description for the event.
	 */
	public function __construct( string $name, StopwatchEvent $stopwatch_event, string $description = '' ) {
		$this->name            = $name;
		$this->stopwatch_event = $stopwatch_event;
		$this->description     = $description;
	}

	/**
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_description(): string {

		$duration    = round( $this->stopwatch_event->getDuration() );
		$memory      = round( $this->stopwatch_event->getMemory() / 1024 / 1024, 2 );
		$description = '' !== $this->description ? "<hr />{$this->description}" : '';

		return "{$this->name} – {$duration} ms – {$memory} MB{$description}";
	}

	/**
	 * @return float Timestamp of record start.
	 */
	public function get_origin(): float {
		return $this->stopwatch_event->getOrigin() / 1000; // ms to s.
	}

	/**
	 * @return float Record duration in seconds.
	 */
	public function get_duration(): float {
		return $this->stopwatch_event->getDuration() / 1000; // ms to s.
	}

	/**
	 * @return string
	 */
	public function get_category(): string {
		return $this->stopwatch_event->getCategory();
	}
}
