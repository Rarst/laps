<?php

namespace Rarst\Laps\Record;

/**
 * SQL record of a query event.
 */
class Sql_Record implements Record_Interface {

	/** @var string $sql */
	protected $sql;

	/** @var float $origin */
	protected $origin;

	/** @var int $duration */
	protected $duration;

	/** @var string $category */
	protected $category;

	/**
	 * @param string $sql      SQL query.
	 * @param float  $origin   Start time.
	 * @param int    $duration Query duration.
	 * @param string $category Query type.
	 */
	public function __construct( $sql, $origin, $duration, $category = 'query-read' ) {
		$this->sql      = $sql;
		$this->origin   = $origin;
		$this->duration = $duration;
		$this->category = $category;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->sql;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		$duration = round( $this->duration );

		return "{$this->sql} – {$duration} ms";
	}

	/**
	 * @return float Timestamp of record start.
	 */
	public function get_origin() {
		return $this->origin;
	}

	/**
	 * @return int Record duration in milliseconds.
	 */
	public function get_duration() {
		return $this->duration;
	}

	/**
	 * @return string
	 */
	public function get_category() {
		return $this->category;
	}
}
