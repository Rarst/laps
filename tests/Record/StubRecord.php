<?php

namespace Rarst\Laps\Tests\Record;

use Rarst\Laps\Record\Record_Interface;

class StubRecord implements Record_Interface {

	protected $name;
	protected $origin;
	protected $duration;

	public function __construct( $name, $origin, $duration ) {
		$this->name     = $name;
		$this->origin   = $origin;
		$this->duration = $duration;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_description() {

	}

	public function get_origin() {
		return $this->origin;
	}

	public function get_duration() {
		return $this->duration;
	}

	public function get_category() {

	}
}
