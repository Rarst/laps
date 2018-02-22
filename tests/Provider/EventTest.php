<?php

namespace Rarst\Laps\Tests\Provider;

use Pimple\Container;
use Rarst\Laps\Event\Hook_Event_Config_Interface;
use Rarst\Laps\Provider\Hook_Event_Provider;
use Rarst\Laps\Tests\LapsTestCase;

class EventTest extends LapsTestCase {

	public function testProvider() {

		$container = new Container();
		$provider  = new Hook_Event_Provider();

		$provider->register( $container );

		$this->assertArrayHasKey( 'hook.events', $container );

		foreach ( $container['hook.events'] as $config ) {
			$this->assertInstanceOf( Hook_Event_Config_Interface::class, $config );
		}
	}
}
