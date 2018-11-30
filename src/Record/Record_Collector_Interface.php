<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record;

/**
 * Interface to retrieve a set of records form collector.
 */
interface Record_Collector_Interface {

	/**
	 * @return Record_Interface[]
	 */
	public function get_records(): array;
}
