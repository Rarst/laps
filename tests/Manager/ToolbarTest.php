<?php
declare( strict_types=1 );

namespace Rarst\Laps\Tests\Manager;

use Brain\Monkey\Functions;
use Rarst\Laps\Manager\Toolbar_Manager;
use Rarst\Laps\Record\Collector\Lazy_Proxy_Collector;
use Rarst\Laps\Tests\LapsTestCase;

/**
 * @coversDefaultClass \Rarst\Laps\Manager\Toolbar_Manager
 */
class ToolbarTest extends LapsTestCase {

	/**
	 * @covers ::__construct
	 *
	 * @return Toolbar_Manager
	 */
	public function test__construct() {

		$collector = new Lazy_Proxy_Collector( [] );
		$manager   = new Toolbar_Manager( $collector, new \Mustache_Engine() );

		$this->assertTrue( has_action( 'admin_bar_menu', [ $manager, 'admin_bar_menu' ] ) );

		return $manager;
	}


	/**
	 * @covers ::admin_bar_menu
	 *
	 * @depends test__construct
	 */
	public function testAdmin_bar_menu( Toolbar_Manager $manager ) {

		$wp_admin_bar = $this->getMockBuilder( 'WP_Admin_Bar' )
		                     ->allowMockingUnknownTypes()
		                     ->setMethods( [ 'add_node' ] )
		                     ->getMock();

		$wp_admin_bar->method( 'add_node' )->willReturn( null );
		$wp_admin_bar->expects( $this->exactly( 2 ) )
		             ->method( 'add_node' )
		             ->withConsecutive(
			             [ $this->isType( 'array' ) ],
			             [ $this->isType( 'array' ) ]
		             );

		Functions\expect( 'current_user_can' )
			->once()
			->with( 'manage_options' )
			->andReturn( true );

		$manager->admin_bar_menu( $wp_admin_bar );
	}
}
