<?php

namespace Rarst\Laps\Tests\Record;

use Rarst\Laps\Record\Record;
use Rarst\Laps\Record\Sql_Record_Collector;
use Rarst\Laps\Tests\LapsTestCase;

class SqlTest extends LapsTestCase {

	/**
	 * @covers \Rarst\Laps\Record\Sql_Record_Collector
	 */
	public function testCollector() {

		define( 'SAVEQUERIES', true );

		$collector = new Sql_Record_Collector();

		$this->assertTrue( has_filter( 'query', [ $collector, 'query' ] ) );

		$query = 'SELECT * FROM wp_posts';

		$collector->query( $query );

		global $wpdb;

		$wpdb            = new \stdClass();
		$duration        = 100;
		$wpdb->queries[] = [ $query, $duration ];

		$records = $collector->get_records();

		$this->assertInternalType( 'array', $records );
		$this->assertCount( 1, $records );

		$record = $records[0];

		$this->assertInstanceOf( Record::class, $record );
		$this->assertEquals( $query, $record->get_name() );
		$this->assertContains( $query, $record->get_description() );
		$this->assertInternalType( 'float', $record->get_origin() );
		$this->assertEquals( $duration, $record->get_duration() );
		$this->assertEquals( 'sql-read', $record->get_category() );
	}
}
