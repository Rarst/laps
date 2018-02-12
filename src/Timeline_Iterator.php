<?php

namespace Rarst\Laps;

use Rarst\Laps\Record\Record_Interface;
use Rarst\Laps\Record\Recursive_Record_Iterator;

class Timeline_Iterator implements \Iterator {

	/** @var float $origin Start point for the timeline. */
	protected $origin;

	/** @var float $end Timeline duration. */
	protected $total;

	/** @var Recursive_Record_Iterator */
	protected $iterator;

	/** @var Recursive_Record_Iterator */
	protected $current;

	public function __construct( Recursive_Record_Iterator $iterator ) {

		global $timestart;

		$this->origin   = $timestart * 1000;
		$this->iterator = $iterator;
	}

	/**
	 * @return array
	 */
	public function current() {

		$data = [];

		foreach ( $this->current as $record ) {
			$data[] = $this->prepare( $record );
		}

		return $data;
	}

	public function next() {
		$this->current = $this->current->getChildren();
	}

	public function key() {

	}

	public function valid() {
		return (bool) count( $this->current );
	}

	public function rewind() {
		$this->total   = microtime( true ) * 1000 - $this->origin;
		$this->current = $this->iterator;
	}

	protected function prepare( Record_Interface $record ) {

		$data = [
			'description' => $record->get_description(),
			'category'    => $record->get_category(),
			'offset'      => round( ( $record->get_origin() - $this->origin ) / $this->total * 100, 2 ),
			'width'       => round( $record->get_duration() / $this->total * 100, 2 ),
		];

		return $data;
	}
}
