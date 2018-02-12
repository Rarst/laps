<?php

namespace Rarst\Laps\Record;

use Symfony\Component\Stopwatch\StopwatchEvent;

class Stopwatch_Record implements Record_Interface {

	/** @var string $name */
	protected $name;

	/** @var StopwatchEvent */
	protected $stopwatch_event;

	/**
	 * @param string         $name            Event name.
	 * @param StopwatchEvent $stopwatch_event Stopwatch event instance.
	 */
	public function __construct( $name, StopwatchEvent $stopwatch_event ) {
		$this->name            = $name;
		$this->stopwatch_event = $stopwatch_event;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_description() {

		$duration = $this->stopwatch_event->getDuration();
		$memory   = $this->stopwatch_event->getMemory() / 1024 / 1024;

		return "{$this->name} – {$duration} ms – {$memory} MB";
	}

	/**
	 * @return float Timestamp of record start.
	 */
	public function get_origin() {
		return $this->stopwatch_event->getOrigin();
	}

	/**
	 * @return int Record duration in milliseconds.
	 */
	public function get_duration() {
		return $this->stopwatch_event->getDuration();
	}

	/**
	 * @return string
	 */
	public function get_category() {
		return $this->stopwatch_event->getCategory();
	}
}
