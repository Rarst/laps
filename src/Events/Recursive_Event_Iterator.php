<?php

namespace Rarst\Laps\Events;

class Recursive_Event_Iterator extends \ArrayIterator implements \RecursiveIterator {

	protected $children = [];

	public function __construct( array $events, $flags = 0 ) {

		usort( $events, [ $this, 'sort_origin' ] );
		$end = 0;

		foreach ( $events as $key => $event ) {

			if ( $event['origin'] < $end ) {
				unset( $events[ $key ] );
				$this->children[] = $event;
				continue;
			}

			$end = $event['origin'] + $event['duration'];
		}

		parent::__construct( $events, $flags );
	}

	protected function sort_origin( $event_a, $event_b ) {

		if ( $event_a['origin'] === $event_b['origin'] ) {
			return 0;
		}

		return ( $event_a['origin'] < $event_b['origin'] ) ? - 1 : 1;
	}

	public function hasChildren() {

		return ! empty( $this->children );
	}

	public function getChildren() {

		return new static( $this->children );
	}
}