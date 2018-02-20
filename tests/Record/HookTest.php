<?php

namespace Rarst\Laps\Tests\Record;

use Brain\Monkey\Functions;
use Rarst\Laps\Event\Core_Events;
use Rarst\Laps\Record\Hook_Record_Collector;
use Rarst\Laps\Tests\LapsTestCase;
use Symfony\Component\Stopwatch\Stopwatch;

class HookTest extends LapsTestCase {

	public function testCollector() {

		$stopwatch = new Stopwatch();
		$collector = new Hook_Record_Collector( $stopwatch, [ 'core' => new Core_Events() ] );

		$this->assertTrue( $stopwatch->isStarted( 'Plugins Load' ) );
		$this->assertTrue( has_action( 'after_setup_theme', [ $collector, 'after_setup_theme' ] ) );

		$collector->after_setup_theme();

		Functions\expect( 'current_filter' )->once()->andReturn( 'plugins_loaded' );
		Functions\expect( 'wp_parse_args' )->once()->andReturnFirstArg();

		global $wp_filter;

		$wp_hook = $this->getMockBuilder( 'WP_Hook' )
		                ->allowMockingUnknownTypes()
		                ->setMethods( [ 'current_priority' ] )
		                ->getMock();

		$wp_hook->method( 'current_priority' )->willReturn( - 2 );

		$wp_filter['plugins_loaded'] = $wp_hook;

		$collector->tick();

		$this->assertFalse( $stopwatch->isStarted( 'Plugins Load' ) );

		$stopwatch->start( 'Toolbar' );
		$collector->get_records();

		$this->assertFalse( $stopwatch->isStarted( 'Toolbar' ) );
	}
}
