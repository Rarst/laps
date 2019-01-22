<?php
declare( strict_types=1 );

namespace Rarst\Laps\Manager;

use Rarst\Laps\Plugin;
use Rarst\Laps\Record\Collector\Record_Collector_Interface;
use Rarst\Laps\Record\Record_Interface;

/**
 * Implements Server Timing API headers.
 */
class Server_Timing_Manager {

	/** @var Record_Collector_Interface */
	private $collector;

	/**
	 * @param Record_Collector_Interface $collector Collector of all records.
	 */
	public function __construct( Record_Collector_Interface $collector ) {

		$this->collector = $collector;

		add_action( 'admin_init', [ $this, 'send_timing_header' ], PHP_INT_MAX );
		add_action( 'rest_pre_serve_request', [ $this, 'send_timing_header' ] );
	}

	/**
	 * Sends Server Timing API header with records data.
	 *
	 * @param mixed $input Pass-through input for attaching to a filter hook.
	 *
	 * @return mixed
	 */
	public function send_timing_header( $input ) {

		if ( headers_sent() ) {
			return $input;
		}

		$ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
		$rest = defined( 'REST_REQUEST' ) && REST_REQUEST;

		if ( ! $ajax && ! $rest ) {
			return $input;
		}

		// This will be false in REST API w/o nonce, even with valid cookie. Might make sense to filter to true in dev.
		if ( ! apply_filters( 'laps_can_see', current_user_can( 'manage_options' ) ) ) {
			return $input;
		}

		$header  = '';

		/** @var Record_Interface $record */
		foreach ( $this->collector->get_records() as $record ) {
			$duration = $record->get_duration() * 1000;

			if ( $duration < 1 ) {
				continue;
			}

			$header .= sprintf( '%s;dur=%.2f;desc="%s", ', $record->get_category(), $duration, $record->get_name() );
		}

		header( 'Server-Timing: ' . preg_replace( '/\R/', '', $header ) );

		return $input;
	}
}
