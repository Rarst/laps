<?php

namespace Rarst\Laps\Tests\Record;

use Rarst\Laps\Record\Stopwatch_Record;
use Rarst\Laps\Record\Collector\Stopwatch_Collector;
use Rarst\Laps\Tests\LapsTestCase;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

class StopwatchTest extends LapsTestCase {

	/**
	 * @covers \Rarst\Laps\Record\Collector\Stopwatch_Collector
	 */
	public function testCollector() {

		$collector = new Stopwatch_Collector( new Stopwatch() );

		$collector->start( 'Event', 'info' );
		$collector->start( 'Not stopped', 'info' );
		usleep( 1000 );
		$event = $collector->stop( 'Event' );

		$this->assertFalse( $collector->stop( 'Not event' ) );

		$records = $collector->get_records();

		$this->assertIsArray( $records );
		$this->assertCount( 1, $records );
		$this->assertInstanceOf( Stopwatch_Record::class, $records[0] );

		return $event;
	}

	/**
	 * @depends testCollector
	 * @covers  \Rarst\Laps\Record\Stopwatch_Record
	 *
	 * @param StopwatchEvent $event Event instance.
	 */
	public function testRecord( StopwatchEvent $event ) {

		$record = new Stopwatch_Record( 'Event', $event );

		$this->assertEquals( 'Event', $record->get_name() );
		$this->assertEquals( 'info', $record->get_category() );
		$this->assertEquals( $event->getOrigin() / 1000, $record->get_origin() );
		$this->assertEquals( $event->getDuration() / 1000, $record->get_duration() );
		$this->assertStringContainsString( 'Event', $record->get_description() );
	}
}
