<?php
declare( strict_types=1 );

namespace Rarst\Laps\Tests\Record;

use function Brain\Monkey\Functions\expect;
use Rarst\Laps\Event\Core_Events;
use Rarst\Laps\Record\Collector\Hook_Collector;
use Rarst\Laps\Tests\LapsTestCase;
use Symfony\Component\Stopwatch\Stopwatch;

class HookTest extends LapsTestCase {

	public function testCollector() {

		expect( 'wp_normalize_path' )->zeroOrMoreTimes()->andReturnFirstArg();

		$stopwatch = new Stopwatch();
		$collector = new Hook_Collector( $stopwatch, [ 'core' => new Core_Events() ] );

		$this->assertTrue( has_action( 'after_setup_theme', [ $collector, 'after_setup_theme' ] ) );

		$this->assertTrue( has_action( 'plugins_loaded', 'function ($input)' ) );

		$collector->after_setup_theme();
		$stopwatch->start( 'Toolbar' );
		$collector->get_records();

		$this->assertFalse( $stopwatch->isStarted( 'Toolbar' ) );
	}
}
