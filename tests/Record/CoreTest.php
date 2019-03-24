<?php

namespace Rarst\Laps\Tests\Record;

use Rarst\Laps\Record\Collector\Core_Load_Collector;
use Rarst\Laps\Record\Record_Interface;
use Rarst\Laps\Tests\LapsTestCase;

class CoreTest extends LapsTestCase {

	public function testCollector() {

		global $timestart;
		$timestart = microtime( true );;

		$collector = new Core_Load_Collector();

		$records = $collector->get_records();

		$this->assertIsArray( $records );
		$this->assertCount( 2, $records );
		$this->assertInstanceOf( Record_Interface::class, $records[0] );
	}
}
