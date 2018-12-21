<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Iterator;

use Rarst\Laps\Record\Record_Interface;

/**
 * Processes records, recursively bumping overlapping ones to children.
 */
class Recursive_Record_Iterator extends \ArrayIterator implements \RecursiveIterator {

	/** @var Record_Interface[] $children */
	protected $children = [];

	/**
	 * @param Record_Interface[] $records Records to process.
	 * @param int                $flags   Configuration flags.
	 */
	public function __construct( array $records, int $flags = 0 ) {

		usort( $records, [ $this, 'sort_origin' ] );
		$end = 0;

		foreach ( $records as $key => $record ) {

			$origin   = $record->get_origin();
			$overlaps = $origin < $end; // Float comparison alarm.
			$borders  = abs( $origin - $end ) < 0.0001; // If effectively equal.

			if ( $overlaps && ! $borders ) {
				unset( $records[ $key ] );
				$this->children[] = $record;
				continue;
			}

			$end = $origin + $record->get_duration();
		}

		parent::__construct( $records, $flags );
	}

	/**
	 * @param Record_Interface $record_a Record to compare.
	 * @param Record_Interface $record_b Record to compare.
	 *
	 * @return int
	 */
	protected function sort_origin( Record_Interface $record_a, Record_Interface $record_b ): int {

		$origin_a = $record_a->get_origin();
		$origin_b = $record_b->get_origin();

		if ( abs( $origin_a - $origin_b ) < 0.0001 ) { // Float math! Makes nesting results more sane.
			return 0;
		}

		return ( $origin_a < $origin_b ) ? - 1 : 1;
	}

	/**
	 * @return bool
	 */
	public function hasChildren(): bool {

		return ! empty( $this->children );
	}

	/**
	 * @return static
	 */
	public function getChildren() {

		return new static( $this->children );
	}
}
