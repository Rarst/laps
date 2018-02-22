<?php

namespace Rarst\Laps\Tests\Provider;

use Pimple\Exception\FrozenServiceException;
use Rarst\Laps\Plugin;
use Rarst\Laps\Provider\Record_Provider;
use Rarst\Laps\Tests\LapsTestCase;

class RecordTest extends LapsTestCase {

	/**
	 * @covers \Rarst\Laps\Provider\Record_Provider
	 */
	public function testProvider() {

		$container = new Plugin();
		$provider = new Record_Provider();

		$provider->register( $container );

		$stopwatch_a = $container['stopwatch'];
		$stopwatch_b = $container['stopwatch'];

		$this->assertNotSame( $stopwatch_a, $stopwatch_b );

		$this->assertArrayHasKey( 'records', $container );

		$container->extend( 'collectors', function () {} );
		$provider->boot( $container );
		$this->expectException( FrozenServiceException::class );
		$container->extend( 'collectors', function () {} );

	}
}
