<?php

namespace Rarst\Laps\Events;

class Recursive_Event_Iterator extends \ArrayIterator implements \RecursiveIterator {

	protected $children = [];

	public function __construct( array $events, $flags = 0 ) {

		foreach ( $events as $key => $event ) {

			foreach ( $events as $check_key => $check_event ) {

				if ( $check_key <= $key ) {
					continue;
				}

				if ( $this->overlaps( $event, $check_event ) ) {

					unset( $events[ $check_key ] );
					$this->children[] = $check_event;
				}

			}
		}

		parent::__construct( $events, $flags );
	}

	/**
	 * @param array $event_a
	 * @param array $event_b
	 *
	 * @return bool True if event B starts after A and before it finishes.
	 */
	protected function overlaps( $event_a, $event_b ) {

		if ( $event_a['origin'] > $event_b['origin'] ) {
			return false;
		}

		return $event_b['origin'] < ( $event_a['origin'] + $event_a['duration'] );
	}

	public function hasChildren() {

		return ! empty( $this->children );
	}

	public function getChildren() {

		return new static( $this->children );
	}
}