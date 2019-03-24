<?php

namespace Rarst\Laps\Tests\Event;

use Brain\Monkey\Functions;
use Rarst\Laps\Event\Core_Events;
use Rarst\Laps\Event\Genesis_Events;
use Rarst\Laps\Event\Hybrid_Events;
use Rarst\Laps\Event\THA_Events;
use Rarst\Laps\Event\WooCommerce_Events;
use Rarst\Laps\Event\Yoast_Events;
use Rarst\Laps\Tests\LapsTestCase;

class EventTest extends LapsTestCase {

	public function testCore() {

		$events = ( new Core_Events() )->get_events();

		$this->assertIsArray( $events );
		$this->assertNotEmpty( $events );
	}

	public function testGenesis() {

		$genesis = new Genesis_Events();
		$this->assertEmpty( $genesis->get_events() );

		Functions\expect( 'genesis' )->zeroOrMoreTimes();
		$events = $genesis->get_events();

		$this->assertIsArray( $events );
		$this->assertNotEmpty( $events );
	}

	public function testHybrid() {

		$hybrid = new Hybrid_Events();
		$this->assertEmpty( $hybrid->get_events() );

		Functions\expect( 'hybrid_get_prefix' )->once()->andReturn( 'hybrid' );
		Functions\expect( 'get_theme_support' )
			->once()
			->with( 'hybrid-core-sidebars' )
			->andReturn( [ [ 'primary' ] ] );

		$events = $hybrid->get_events();

		$this->assertIsArray( $events );
		$this->assertNotEmpty( $events );
	}

	public function testTha() {

		$tha = new THA_Events();
		$this->assertEmpty( $tha->get_events() );

		define( 'THA_HOOKS_VERSION', '1.0' );

		$events = $tha->get_events();

		$this->assertIsArray( $events );
		$this->assertNotEmpty( $events );
	}

	public function testWooCommerce() {

		$woocommerce = new WooCommerce_Events();
		$this->assertEmpty( $woocommerce->get_events() );

		$this->getMockBuilder( 'WooCommerce' )->allowMockingUnknownTypes()->getMock();

		$events = $woocommerce->get_events();

		$this->assertIsArray( $events );
		$this->assertNotEmpty( $events );
	}

	public function testYoast() {

		$yoast = new Yoast_Events();
		$this->assertEmpty( $yoast->get_events() );

		$this->getMockBuilder( 'WPSEO_Frontend' )->allowMockingUnknownTypes()->getMock();

		$events = $yoast->get_events();

		$this->assertIsArray( $events );
		$this->assertNotEmpty( $events );
	}
}
