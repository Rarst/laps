<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record;

/**
 * Interface for recorded results of individual event.
 */
interface Record_Interface {

	/**
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * @return string
	 */
	public function get_description(): string;

	/**
	 * @return float Timestamp of record start.
	 */
	public function get_origin(): float;

	/**
	 * @return float Record duration in seconds.
	 */
	public function get_duration(): float;

	/**
	 * @return string
	 */
	public function get_category(): string;
}
