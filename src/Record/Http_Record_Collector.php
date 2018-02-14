<?php

namespace Rarst\Laps\Record;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Captures time of network requests made with HTTP API.
 */
class Http_Record_Collector implements Record_Collector_Interface {

	/** @var Stopwatch $stopwatch */
	protected $stopwatch;

	/**
	 * @param Stopwatch $stopwatch Stopwatch instance.
	 */
	public function __construct( Stopwatch $stopwatch ) {

		$this->stopwatch = $stopwatch;

		add_action( 'pre_http_request', [ $this, 'pre_http_request' ], 10, 3 );
		add_action( 'http_api_debug', [ $this, 'http_api_debug' ], 10, 5 );
	}

	/**
	 * Capture start time of HTTP request
	 *
	 * @param boolean $false Whether to preempt an HTTP request's return value. Default false.
	 * @param array   $args  HTTP request arguments.
	 * @param string  $url   The request URL.
	 *
	 * @return boolean
	 */
	public function pre_http_request( $false, $args, $url ) {

		$this->stopwatch->start( $url, 'http' );

		return $false;
	}

	/**
	 * Capture end time of HTTP request
	 *
	 * @param array|\WP_Error $response HTTP response or WP_Error object.
	 * @param string          $type     Context under which the hook is fired.
	 * @param object          $class    HTTP transport used.
	 * @param array           $args     HTTP request arguments.
	 * @param string          $url      The request URL.
	 *
	 * @return mixed
	 *
	 * @noinspection MoreThanThreeArgumentsInspection
	 */
	public function http_api_debug( $response, $type, $class, $args, $url ) {

		$this->stopwatch->stop( $url );

		return $response;
	}

	/**
	 * @return Stopwatch_Record[]
	 */
	public function get_records() {

		$events = $this->stopwatch->getSectionEvents( '__root__' );

		return array_map( [ $this, 'transform' ], array_keys( $events ), $events );
	}

	/**
	 * @param string         $name  Event name.
	 * @param StopwatchEvent $event Stopwatch event instance.
	 *
	 * @return Stopwatch_Record
	 */
	protected function transform( $name, StopwatchEvent $event ) {

		return new Stopwatch_Record( $name, $event );
	}
}
