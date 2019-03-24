<?php

namespace Rarst\Laps\Tests\Record;

use Brain\Monkey\Functions;
use Rarst\Laps\Record\Record;
use Rarst\Laps\Record\Collector\Sql_Collector;
use Rarst\Laps\Tests\LapsTestCase;

class SqlTest extends LapsTestCase {

	/**
	 * @covers \Rarst\Laps\Record\Collector\Sql_Collector
	 */
	public function testCollector() {

		define( 'SAVEQUERIES', true );

		if ( ! defined( 'ABSPATH' ) ) {
			define( 'ABSPATH', '/wp' );
			define( 'WP_CONTENT_DIR', '/wp-content/' );
		}
		Functions\expect( 'wp_normalize_path' )->zeroOrMoreTimes()->andReturnFirstArg();

		$collector = new Sql_Collector();

		$this->assertTrue( has_filter( 'query', [ $collector, 'query' ] ) );

		$query = 'SELECT * FROM wp_posts';

		$collector->query( $query );

		global $wpdb;

		$wpdb            = new \stdClass();
		$duration        = 100;
		$wpdb->queries[] = [ $query, $duration, 'foo(), bar()' ];

		$records = $collector->get_records();

		$this->assertIsArray( $records );
		$this->assertCount( 1, $records );

		$record = $records[0];

		$this->assertInstanceOf( Record::class, $record );
		$this->assertEquals( $query, $record->get_name() );
		$this->assertStringContainsString( $query, $record->get_description() );
		$this->assertIsFloat( $record->get_origin() );
		$this->assertEquals( $duration, $record->get_duration() );
		$this->assertEquals( 'sql-read', $record->get_category() );
	}
}
