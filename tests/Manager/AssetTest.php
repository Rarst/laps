<?php

namespace Rarst\Laps\Tests\Manager;

use Brain\Monkey\Functions;
use Rarst\Laps\Manager\Asset_Manager;
use Rarst\Laps\Tests\LapsTestCase;

/**
 * @coversDefaultClass Rarst\Laps\Manager\Asset_Manager
 */
class AssetTest extends LapsTestCase {

	/**
	 * @covers ::__construct
	 *
	 * @return Asset_Manager
	 */
	public function test__construct() {

		$manager = new Asset_Manager();

		$this->assertTrue( has_action( 'wp_enqueue_scripts', [ $manager, 'enqueue_scripts' ] ) );
		$this->assertTrue( has_action( 'admin_enqueue_scripts', [ $manager, 'enqueue_scripts' ] ) );

		return $manager;
	}

	/**
	 * @covers ::enqueue_scripts
	 * @depends test__construct
	 */
	public function testEnqueue_scripts( Asset_Manager $manager ) {

		Functions\expect( 'wp_register_script' )->once();
		Functions\expect( 'wp_register_style' )->once();
		Functions\expect( 'plugins_url' )->zeroOrMoreTimes();
		Functions\expect( 'is_admin_bar_showing' )->once()->andReturn( true );
		Functions\expect( 'wp_enqueue_script' )->once();
		Functions\expect( 'wp_enqueue_style' )->once();

		$manager->enqueue_scripts();
	}
}
