<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Collector;

use Rarst\Laps\Formatter\Backtrace_Formatter;
use Rarst\Laps\Record\Record;

/**
 * Processes SQL events from data logged by wpdb.
 *
 * @template QueryData as array{0: string, 1: float, 2: string, 3: float}
 */
class Sql_Collector implements Record_Collector_Interface {

	/** @var array $query_starts Log of query start times. */
	protected $query_starts = [];

	/** @var Backtrace_Formatter $formatter */
	protected $formatter;

	/**
	 * Sets up the query start time log.
	 */
	public function __construct() {

		$this->formatter = new Backtrace_Formatter();

		if ( $this->is_savequeries() ) {
			add_filter( 'query', [ $this, 'query' ], 20 ); // TODO Drop recording time if dropping support for WP <5.1.
		}
	}

	/**
	 * Capture SQL queries start times
	 *
	 * @param string $query SQL query.
	 *
	 * @return string
	 */
	public function query( $query ): string {

		global $wpdb;

		if ( empty( $this->query_starts ) && ! empty( $wpdb->queries ) ) {
			/** @var array $wpdb->queries */
			$this->query_starts[ count( $wpdb->queries ) ] = microtime( true );
		} else {
			$this->query_starts[] = microtime( true );
		}

		return $query;
	}

	/**
	 * @return Record[]
	 */
	public function get_records(): array {

		global $wpdb;

		if ( empty( $wpdb->queries ) ) {
			return [];
		}

		/** @var array $wpdb->queries */
		/** @psalm-var array<int, QueryData> $wpdb->queries */
		$records = array_filter( array_map( [ $this, 'transform' ], array_keys( $wpdb->queries ), $wpdb->queries ) );

		return $records;
	}

	/**
	 * @return bool
	 */
	protected function is_savequeries(): bool {

		return \defined( 'SAVEQUERIES' ) && SAVEQUERIES;
	}

	/**
	 * Transform query data, captured by core, into a Record.
	 *
	 * @param int   $key        Query key in captured data.
	 * @param array $query_data Array of captured query data.
	 * @psalm-param array{0: string, 1: float, 2: string, 3: float} $query_data
	 *
	 * @return Record
	 */
	protected function transform( int $key, array $query_data ): ?Record {

		[ $sql, $duration, $caller ] = $query_data;

		/** @var float $query_start */
		$query_start = $query_data[3] ?? $this->query_starts[ $key ] ?? 0;
		if ( empty( $query_start ) ) {
			return null;
		}
		$sql         = trim( $sql );
		$category    = 'sql-read';
		if ( 0 === stripos( $sql, 'INSERT' ) || 0 === stripos( $sql, 'UPDATE' ) ) {
			$category = 'sql-write';
		}
		$desc_duration = round( $duration * 1000 );
		$backtrace     = $this->formatter->format( $caller );
		$description   = $sql . ' – ' . $desc_duration . 'ms<hr />' . implode( '<br />', $backtrace );

		return new Record( $sql, $query_start, $duration, $description, $category );
	}
}
