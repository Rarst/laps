<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Collector;

use Rarst\Laps\Record\Record_Interface;

/**
 * Collects records from concrete collectors set on access.
 */
class Lazy_Proxy_Collector implements Record_Collector_Interface {

	/** @var array|Record_Collector_Interface[] */
	private $collectors;

	/**
	 * Lazy_Proxy_Collector constructor.
	 *
	 * @param Record_Collector_Interface[] $collectors Array of collectors to store for processing.
	 */
	public function __construct( array $collectors ) {
		$this->collectors = $collectors;
	}

	/**
	 * @return Record_Interface[]
	 */
	public function get_records(): array {
		$records = [];

		/** @var Record_Collector_Interface $collector */
		foreach ( $this->collectors as $collector ) {
			$records[] = $collector->get_records();
		}

		return count( $records ) ? array_merge( ...$records ) : [];
	}
}
