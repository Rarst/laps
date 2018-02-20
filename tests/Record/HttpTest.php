<?php

namespace Rarst\Laps\Tests\Record;

use Rarst\Laps\Record\Http_Record_Collector;
use Rarst\Laps\Tests\LapsTestCase;
use Symfony\Component\Stopwatch\Stopwatch;

class HttpTest extends LapsTestCase {

	public function testCollector() {

		$stopwatch = new Stopwatch();
		$url       = 'https://example.com/';
		$collector = new Http_Record_Collector( $stopwatch );

		$this->assertTrue( has_action( 'pre_http_request', [ $collector, 'pre_http_request' ] ) );
		$this->assertTrue( has_action( 'http_api_debug', [ $collector, 'http_api_debug' ] ) );

		$collector->pre_http_request( false, [], $url );

		$this->assertTrue( $stopwatch->isStarted( $url ) );

		$collector->http_api_debug( [], '', new \stdClass(), [], $url );

		$this->assertFalse( $stopwatch->isStarted( $url ) );
	}
}
