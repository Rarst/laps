<?php

namespace Rarst\Laps\Record;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Bootable_Provider_Interface;
use Rarst\Laps\Laps;

class Sql_Record_Collector implements ServiceProviderInterface, Bootable_Provider_Interface, Record_Collector_Interface {

	protected $query_starts = [];

	public function register( Container $pimple ) {

	}

	public function boot( Laps $laps ) {

		if ( $this->is_savequeries() ) {
			add_filter( 'query', [ $this, 'query' ], 20 );
		}
	}

	/**
	 * Capture SQL queries start times
	 *
	 * @param string $query
	 *
	 * @return string
	 */
	public function query( $query ) {

		global $wpdb;

		if ( empty( $this->query_starts ) && ! empty( $wpdb->queries ) ) {
			$this->query_starts[ count( $wpdb->queries ) ] = microtime( true ) * 1000;
		} else {
			$this->query_starts[] = microtime( true ) * 1000;
		}

		return $query;
	}

	public function get_records() {

		if ( ! $this->is_savequeries() ) {
			return [];
		}

		global $wpdb;

		$query_data     = [];
		$last_query_end = 0;

		foreach ( $wpdb->queries as $key => list( $sql, $duration ) ) {
			$query_start = isset( $this->query_starts[ $key ] ) ? $this->query_starts[ $key ] : $last_query_end;
			$sql         = trim( $sql );
			$category    = 'query-read';

			if ( 0 === stripos( $sql, 'INSERT' ) || 0 === stripos( $sql, 'UPDATE' ) ) {
				$category = 'query-write';
			}

			$duration       *= 1000;
			$last_query_end = $query_start + $duration;

			$name        = $sql;
			$description = $name;
			$origin      = $query_start;

			$query_data[] = compact( 'name', 'description', 'origin', 'duration', 'category' );
		}

		return $query_data;
	}

	protected function is_savequeries() {

		return defined( 'SAVEQUERIES' ) && SAVEQUERIES;
	}
}
