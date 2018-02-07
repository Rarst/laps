<?php

namespace Rarst\Laps;

/**
 * Base class for bulk event providers
 */
abstract class Laps_Events {

	/**
	 * Retrieve array of event data
	 *
	 * @return array
	 */
	public function get() {

		if ( $this->is_applicable() ) {
			return $this->get_events();
		}

		return array();
	}

	/**
	 * Is event pack relevant to current runtime
	 *
	 * @return bool
	 */
	protected function is_applicable() {

		return true;
	}

	/**
	 * Inherit with internal events compiling and return
	 *
	 * @return array
	 */
	abstract protected function get_events();
}
