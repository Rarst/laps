<?php

namespace Rarst\Laps\Tests\Manager;

use Brain\Monkey\Functions;
use Rarst\Laps\Manager\Load_Order_Manager;
use Rarst\Laps\Tests\LapsTestCase;

/**
 * @coversDefaultClass Rarst\Laps\Manager\Load_Order_Manager
 */
class LoadOrderTest extends LapsTestCase {

	/**
	 * @covers ::__construct
	 *
	 * @return Load_Order_Manager
	 */
	public function test__construct() {

		$manager = new Load_Order_Manager();

		$this->assertTrue( has_action( 'pre_update_option_active_plugins', [ $manager, 'pre_update_option_active_plugins' ] ) );
		$this->assertTrue( has_action( 'pre_update_site_option_active_sitewide_plugins', [ $manager, 'pre_update_option_active_plugins' ] ) );

		return $manager;

	}

	/**
	 * @covers ::pre_update_option_active_plugins
	 * @depends test__construct
	 */
	public function testPre_update_option_active_plugins( Load_Order_Manager $manager ) {

		$plugins = [
			'foo/bar.php',
			'laps/laps.php',
			'baz/qux.php',
		];

		Functions\expect( 'plugin_basename' )->once()->andReturn( 'laps/laps.php' );

		$plugins = $manager->pre_update_option_active_plugins( $plugins );

		$this->assertIsArray( $plugins );
		$this->assertCount( 3, $plugins );
		$this->assertEquals( 'laps/laps.php', $plugins[0] );
	}
}
