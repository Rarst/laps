<?php

namespace Rarst\Laps\Tests\Provider;

use Pimple\Exception\FrozenServiceException;
use Rarst\Laps\Plugin;
use Rarst\Laps\Provider\Manager_Provider;
use Rarst\Laps\Tests\LapsTestCase;

class ManagerTest extends LapsTestCase {

	/**
	 * @covers \Rarst\Laps\Provider\Manager_Provider
	 */
	public function testProvider() {

		$container = new Plugin();
		$provider  = new Manager_Provider();

		$provider->register( $container );

		$this->assertArrayHasKey( 'managers', $container );

		$container->extend( 'managers', function () {} );
		$provider->boot( $container );
		$this->expectException( FrozenServiceException::class );
		$container->extend( 'managers', function () {} );
	}
}
