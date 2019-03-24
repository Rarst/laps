<?php

namespace Rarst\Laps\Tests\Record;

use Rarst\Laps\Record\Record;
use Rarst\Laps\Tests\LapsTestCase;

class RecordTest extends LapsTestCase {

	/**
	 * @covers \Rarst\Laps\Record\Record
	 */
	public function testRecord() {

		$name     = 'Name';
		$origin   = 0;
		$duration = 1;

		$description = 'description';
		$category    = 'info';

		$record = new Record( $name, $origin, $duration );

		$this->assertEquals( $name, $record->get_name() );
		$this->assertEquals( $origin, $record->get_origin() );
		$this->assertEquals( $duration, $record->get_duration() );
		$this->assertStringContainsString( $name, $record->get_description() );
		$this->assertStringContainsString( (string) ( $duration * 1000 ), $record->get_description() );
		$this->assertEmpty( $record->get_category() );

		$record = new Record( $name, $origin, $duration, $description, $category );

		$this->assertEquals( $description, $record->get_description() );
		$this->assertEquals( $category, $record->get_category() );
	}
}
