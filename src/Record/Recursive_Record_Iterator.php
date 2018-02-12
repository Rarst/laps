<?php

namespace Rarst\Laps\Record;

class Recursive_Record_Iterator extends \ArrayIterator implements \RecursiveIterator {

	protected $children = [];

	public function __construct( array $records, $flags = 0 ) {

		usort( $records, [ $this, 'sort_origin' ] );
		$end = 0;

		foreach ( $records as $key => $event ) {

			if ( $event['origin'] < $end ) {
				unset( $records[ $key ] );
				$this->children[] = $event;
				continue;
			}

			$end = $event['origin'] + $event['duration'];
		}

		parent::__construct( $records, $flags );
	}

	protected function sort_origin( $record_a, $record_b ) {

		if ( $record_a['origin'] === $record_b['origin'] ) {
			return 0;
		}

		return ( $record_a['origin'] < $record_b['origin'] ) ? - 1 : 1;
	}

	public function hasChildren() {

		return ! empty( $this->children );
	}

	public function getChildren() {

		return new static( $this->children );
	}
}