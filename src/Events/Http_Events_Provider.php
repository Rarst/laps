<?php

namespace Rarst\Laps\Events;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Bootable_Provider_Interface;
use Rarst\Laps\Laps;
use Symfony\Component\Stopwatch\Stopwatch;

class Http_Events_Provider implements ServiceProviderInterface, Bootable_Provider_Interface {

	/** @var Stopwatch $stopwatch */
	protected $stopwatch;

	public function register( Container $pimple ) {

	}

	public function boot( Laps $laps ) {

		$this->stopwatch = $laps['stopwatch'];

		add_action( 'pre_http_request', [ $this, 'pre_http_request' ], 10, 3 );
		add_action( 'http_api_debug', [ $this, 'http_api_debug' ], 10, 5 );
	}

	/**
	 * Capture start time of HTTP request
	 *
	 * @param boolean $false
	 * @param array   $args
	 * @param string  $url
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
	 * @param array|\WP_Error $response
	 * @param string          $type
	 * @param object          $class
	 * @param array           $args
	 * @param string          $url
	 *
	 * @return mixed
	 */
	public function http_api_debug( $response, $type, $class, $args, $url ) {

		$this->stopwatch->stop( $url );

		return $response;
	}
}