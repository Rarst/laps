<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record;

/**
 * Generic record with all properties set by constructor.
 */
class Record implements Record_Interface {

	/** @var string $name */
	protected $name;

	/** @var float $origin */
	protected $origin;

	/** @var float $duration */
	protected $duration;

	/** @var string $description */
	protected $description;

	/** @var string $category */
	protected $category;

	/**
	 * @param string $name        Name.
	 * @param float  $origin      Start time.
	 * @param float  $duration    Duration.
	 * @param string $description Optional description.
	 * @param string $category    Optional category.
	 */
	public function __construct( string $name, float $origin, float $duration, string $description = '', string $category = '' ) {
		$this->name        = $name;
		$this->origin      = $origin;
		$this->duration    = $duration;
		$this->description = $description;
		$this->category    = $category;
	}

	/**
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_description(): string {

		if ( empty( $this->description ) ) {
			$duration = round( $this->get_duration() * 1000 );

			return "{$this->name} – {$duration}&nbsp;ms";
		}

		return $this->description;
	}

	/**
	 * @return float
	 */
	public function get_origin(): float {
		return $this->origin;
	}

	/**
	 * @return float
	 */
	public function get_duration(): float {
		return $this->duration;
	}

	/**
	 * @return string
	 */
	public function get_category(): string {
		return $this->category;
	}
}
