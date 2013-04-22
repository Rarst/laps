<?php

namespace Rarst\Laps;

abstract class Laps_Events {

	/**
	 * @return bool
	 */
	protected function is_applicable() {

		return true;
	}

	/**
	 * @return array
	 */
	public function get() {

		if( $this->is_applicable() )
			return $this->get_events();

		return array();
	}

	/**
	 * @return array()
	 */
	abstract protected function get_events();
}