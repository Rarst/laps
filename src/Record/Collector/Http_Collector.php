<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Collector;

use Rarst\Laps\Formatter\Backtrace_Formatter;
use Rarst\Laps\Record\Stopwatch_Record;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Captures time of network requests made with HTTP API.
 */
class Http_Collector extends Stopwatch_Collector {

	/** @var Backtrace_Formatter */
	private $formatter;

	/**
	 * @var array $callers
	 * @psalm-var array<string, string> $callers
	 */
	private $callers = [];

	/**
	 * @param Stopwatch $stopwatch Stopwatch instance.
	 */
	public function __construct( Stopwatch $stopwatch ) {

		parent::__construct( $stopwatch );

		$this->formatter = new Backtrace_Formatter();

		add_action( 'pre_http_request', [ $this, 'pre_http_request' ], 10, 3 );
		add_action( 'http_api_debug', [ $this, 'http_api_debug' ], 10, 5 );
	}

	/**
	 * Capture start time of HTTP request
	 *
	 * @param false|array|\WP_Error $false Whether to preempt an HTTP request's return value. Default false.
	 * @param array                 $args  HTTP request arguments.
	 * @param string                $url   The request URL.
	 *
	 * @return false|array|\WP_Error
	 */
	public function pre_http_request( $false, array $args, string $url ) {

		$this->start( $url, 'http' );
		$this->callers[ $url ] = wp_debug_backtrace_summary( __CLASS__, 5 );

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
	public function http_api_debug( $response, string $type, $class, array $args, string $url ) {

		$this->stop( $url );

		return $response;
	}

	/**
	 * @param string         $name  Event name.
	 * @param StopwatchEvent $event Stopwatch event instance.
	 *
	 * @return Stopwatch_Record
	 */
	public function transform( string $name, StopwatchEvent $event ): Stopwatch_Record {

		$backtrace = $this->formatter->format( $this->callers[ $name ] );

		return new Stopwatch_Record( $name, $event, implode( '<br />', $backtrace ) );
	}
}
