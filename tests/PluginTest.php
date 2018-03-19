<?php

namespace Rarst\Laps\Tests;

use Brain\Monkey\Functions;
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

		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', '/wp' );
			define( 'WP_CONTENT_DIR', '/wp-content/' );
		}
		Functions\expect( 'wp_normalize_path' )->zeroOrMoreTimes()->andReturnFirstArg();

		$container->run();
	}
}
