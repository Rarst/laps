<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Iterator;

use Rarst\Laps\Record\Record;
use Rarst\Laps\Record\Record_Interface;
use Rarst\Laps\Record\Iterator\Recursive_Record_Iterator;

/**
 * Processes records into sets for display by template.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class Timeline_Iterator implements \Iterator {

	/** @var float $origin Start point for the timeline. */
	protected $origin;

	/** @var float $end Timeline duration. */
	protected $total;

	/** @var Recursive_Record_Iterator */
	protected $iterator;

	/** @var Recursive_Record_Iterator */
	protected $current;

	/**
	 * @param Recursive_Record_Iterator $iterator Record iterator.
	 *
	 * @psalm-suppress PossiblyFalsePropertyAssignmentValue
	 */
	public function __construct( Recursive_Record_Iterator $iterator ) {

		$this->origin   = filter_var( $_SERVER['REQUEST_TIME_FLOAT'], FILTER_VALIDATE_FLOAT );
		$this->iterator = $iterator;
	}

	/**
	 * @return array
	 */
	public function current(): array {

		$data = [];

		/** @var Record $record */
		foreach ( $this->current as $record ) {
			$data[] = $this->prepare( $record );
		}

		return $data;
	}

	/**
	 * Sets current context to the next set of nested records.
	 */
	public function next(): void {
		$this->current = $this->current->getChildren();
	}

	/**
	 * @codeCoverageIgnore
	 *
	 * @return void
	 */
	public function key(): void {

	}

	/**
	 * @return bool If current set contains records.
	 */
	public function valid(): bool {
		return (bool) count( $this->current );
	}

	/**
	 * Rewind to a topmost record set.
	 */
	public function rewind(): void {
		$this->total   = microtime( true ) - $this->origin;
		$this->current = $this->iterator;
	}

	/**
	 * @param Record_Interface $record Record instance.
	 *
	 * @return array Record data for display by the template.
	 */
	protected function prepare( Record_Interface $record ): array {

		$data = [
			'description' => $record->get_description(),
			'category'    => $record->get_category(),
			'offset'      => round( ( $record->get_origin() - $this->origin ) / $this->total * 100, 2 ),
			'width'       => round( $record->get_duration() / $this->total * 100, 2 ),
		];

		return $data;
	}
}
