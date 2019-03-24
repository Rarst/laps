<?php
declare( strict_types=1 );

namespace Rarst\Laps\Tests\Formatter;

use Brain\Monkey\Functions;
use Rarst\Laps\Formatter\Hook_Formatter;
use Rarst\Laps\Tests\LapsTestCase;

/**
 * @coversDefaultClass \Rarst\Laps\Formatter\Hook_Formatter
 */
class HookTest extends LapsTestCase {

	/**
	 * @covers ::__construct
	 *
	 * @return Hook_Formatter
	 */
	public function test__construct() {

		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', '/wp' );
			define( 'WP_CONTENT_DIR', '/wp-content/' );
		}

		Functions\expect( 'wp_normalize_path' )->zeroOrMoreTimes()->andReturnFirstArg();

		return new Hook_Formatter();
	}

	/**
	 * @depends test__construct
	 */
	public function testFormat( Hook_Formatter $formatter ) {

		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', '/wp' );
			define( 'WP_CONTENT_DIR', '/wp-content/' );
		}

		Functions\expect( 'wp_normalize_path' )->zeroOrMoreTimes()->andReturnFirstArg();

		$hook = [
			[ [ 'function' => 'function', 'accepted_args' => 2 ] ],
			[ [ 'function' => [ 'class', 'method' ], 'accepted_args' => 1 ] ],
			[ [ 'function' => function() {}, 'accepted_args' => 1 ] ],
			[ [ 'function' => new class(){}, 'accepted_args' => 1 ] ],
		];

		$result = $formatter->format( $hook );

		$this->assertIsArray( $result );
		$this->assertEquals( 'function(2)', $result[0] );
		$this->assertEquals( 'class::method', $result[1] );
		$this->assertStringStartsWith( 'closure from', $result[2] );
		$this->assertStringStartsWith( 'anonymous class from', $result[3] );

		$this->assertEquals( [], $formatter->format( [] ) );
	}
}

