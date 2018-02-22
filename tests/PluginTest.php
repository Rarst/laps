<?php

namespace Rarst\Laps\Tests;

use Pimple\ServiceProviderInterface;
use Rarst\Laps\Provider\Bootable_Provider_Interface;

class PluginTest extends LapsTestCase {

	public function testContainer() {

		$container = new \Rarst\Laps\Plugin();

		$service = $this->getMockBuilder( [ ServiceProviderInterface::class, Bootable_Provider_Interface::class ] )
                        ->getMock();

		$service->expects( $this->once() )
		        ->method( 'register' )
		        ->with( $container );

		$container->register( $service );

		$service->expects( $this->once() )
		        ->method( 'boot' )
		        ->with( $container );

		$container->run();
	}
}
