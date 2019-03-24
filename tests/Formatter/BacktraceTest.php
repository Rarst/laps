<?php

namespace Rarst\Laps\Tests\Formatter;

use Brain\Monkey\Functions;
use Rarst\Laps\Formatter\Backtrace_Formatter;
use Rarst\Laps\Tests\LapsTestCase;

/**
 * @coversDefaultClass Rarst\Laps\Formatter\Backtrace_Formatter
 */
class BacktraceTest extends LapsTestCase {

	/**
	 * @covers ::__construct
	 *
	 * @return Backtrace_Formatter
	 */
	public function test__construct() {

		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', '/wp' );
			define( 'WP_CONTENT_DIR', '/wp-content/' );
		}

		Functions\expect( 'wp_normalize_path' )->zeroOrMoreTimes()->andReturnFirstArg();

		return new Backtrace_Formatter();
	}

	/**
	 * @depends test__construct
	 */
	public function testFormat( Backtrace_Formatter $formatter ) {

		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', '/wp' );
			define( 'WP_CONTENT_DIR', '/wp-content/' );
		}

		Functions\expect( 'wp_normalize_path' )->zeroOrMoreTimes()->andReturnFirstArg();

		$backtrace = [
			'template-loader.php',
			'WP_Hook->do_action',
			'foo',
			"include('C:/wp/path.php')",
		];

		$backtrace = implode( ', ', $backtrace );

		$result = $formatter->format( $backtrace );

		$this->assertIsArray( $result );
		$result = array_values( $result );
		$this->assertEquals( 'foo', $result[0] );
		$this->assertEquals( 'path.php', $result[1] );
	}
}

