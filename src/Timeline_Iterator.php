<?php

namespace Rarst\Laps;

use Rarst\Laps\Events\Recursive_Event_Iterator;

class Timeline_Iterator implements \Iterator {

	/** @var Recursive_Event_Iterator */
	protected $iterator;

	/** @var Recursive_Event_Iterator */
	protected $current;

	public function __construct( Recursive_Event_Iterator $iterator ) {
		$this->iterator = $iterator;
	}

	public function current() {
		return $this->current;
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
		$this->current = $this->iterator;
	}
}