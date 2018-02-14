<?php

namespace Rarst\Laps\Record;

/**
 * Processes records, recursively bumping overlapping ones to children.
 */
class Recursive_Record_Iterator extends \ArrayIterator implements \RecursiveIterator {

	/** @var array $children */
	protected $children = [];

	/**
	 * @param Record_Interface[] $records Records to process.
	 * @param int                $flags   Configuration flags.
	 */
	public function __construct( array $records, $flags = 0 ) {

		usort( $records, [ $this, 'sort_origin' ] );
		$end = 0;

		foreach ( $records as $key => $record ) {

			if ( $record->get_origin() < $end ) {
				unset( $records[ $key ] );
				$this->children[] = $record;
				continue;
			}

			$end = $record->get_origin() + $record->get_duration();
		}

		parent::__construct( $records, $flags );
	}

	/**
	 * @param Record_Interface $record_a Record to compare.
	 * @param Record_Interface $record_b Record to compare.
	 *
	 * @return int
	 */
	protected function sort_origin( Record_Interface $record_a, Record_Interface $record_b ) {

		$origin_a = $record_a->get_origin();
		$origin_b = $record_b->get_origin();

		if ( $origin_a === $origin_b ) {
			return 0;
		}

		return ( $origin_a < $origin_b ) ? - 1 : 1;
	}

	/**
	 * @return bool
	 */
	public function hasChildren() {

		return ! empty( $this->children );
	}

	/**
	 * @return static
	 */
	public function getChildren() {

		return new static( $this->children );
	}
}
