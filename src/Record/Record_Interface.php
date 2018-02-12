<?php

namespace Rarst\Laps\Record;

/**
 * Interface for recorded results of individual event.
 */
interface Record_Interface {

	/**
	 * @return string
	 */
	public function get_name();

	/**
	 * @return string
	 */
	public function get_description();

	/**
	 * @return float Timestamp of record start.
	 */
	public function get_origin();

	/**
	 * @return int Record duration in milliseconds.
	 */
	public function get_duration();

	/**
	 * @return string
	 */
	public function get_category();
}
