<?php

namespace Rarst\Laps\Events;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Bootable_Provider_Interface;
use Rarst\Laps\Laps;

class Sql_Events_Provider implements ServiceProviderInterface, Bootable_Provider_Interface, Events_Provider_Interface {

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

	public function get_events() {

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

// TODO revisit query stacking if necessary after timeline logic upgrade.
//		$query_data     = array();
//		$last_query_end = 0;
//		$last_offset    = 0;
//		$last_duration  = 0;

//		if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
//
//			foreach ( $wpdb->queries as $key => $query ) {
//				$query_start = isset( $this->query_starts[ $key ] ) ? $this->query_starts[ $key ] : $last_query_end;
//				list( $sql, $duration, $trace ) = $query;
//				$sql      = trim( $sql );
//				$category = 'query-read';
//
//				if ( 0 === stripos( $sql, 'INSERT' ) || 0 === stripos( $sql, 'UPDATE' ) ) {
//					$category = 'query-write';
//				}
//
//				$duration *= 1000;
//				$last_query_end = $query_start + $duration;
//				$offset         = round( ( $query_start - $start ) / $total * 100, 2 );
//
//				// if query is indistinguishably close to previous then stack it
//				if ( $offset === $last_offset ) {
//					$key = count( $query_data ) - 1;
//					$query_data[ $key ]['sql'] .= '<br />' . $sql;
//
//					$last_duration += $duration;
//					$width                       = round( $last_duration / $total * 100, 2 );
//					$query_data[ $key ]['width'] = $width;
//
//					continue;
//				}
//
//				$width         = round( $duration / $total * 100, 2 );
//				$last_offset   = $offset;
//				$last_duration = $duration;
//
//				$query_data[] = compact( 'sql', 'duration', 'offset', 'width', 'category' );
//			}
//		}