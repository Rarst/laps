<?php

namespace Rarst\Laps\Tests\Record;

use Rarst\Laps\Record\Recursive_Record_Iterator;
use Rarst\Laps\Record\Record;
use Rarst\Laps\Record\Timeline_Iterator;
use Rarst\Laps\Tests\LapsTestCase;

class IteratorTest extends LapsTestCase {

	/**
	 * @covers \Rarst\Laps\Record\Recursive_Record_Iterator
	 *
	 * @return Recursive_Record_Iterator
	 */
	public function testRecord() {

		$record_a = new Record( 'Parent', 0, 100 );
		$record_b = new Record( 'Child', 50, 100 );

		$records = [ $record_b, $record_a ];

		$iterator = new Recursive_Record_Iterator( $records );

		$this->assertCount( 1, $iterator->getArrayCopy() );

		foreach ( $iterator as $record ) {
			$this->assertEquals( 'Parent', $record->get_name() );
		}

		$this->assertTrue( $iterator->hasChildren() );
		$children = $iterator->getChildren();
		$this->assertCount( 1, $children );

		foreach ( $children as $record ) {
			$this->assertEquals( 'Child', $record->get_name() );
		}

		return $iterator;
	}

	/**
	 * @covers \Rarst\Laps\Record\Timeline_Iterator
	 *
	 * @depends testRecord
	 */
	public function testTimeline( Recursive_Record_Iterator $iterator ) {

		$iterator = new Timeline_Iterator( $iterator );

		$iterator->rewind();
		$this->assertTrue( $iterator->valid() );

		$records = $iterator->current();
		$this->assertInternalType( 'array', $records );

		$this->assertArrayHasKey( 'description', $records[0] );
		$this->assertArrayHasKey( 'category', $records[0] );
		$this->assertArrayHasKey( 'offset', $records[0] );
		$this->assertArrayHasKey( 'width', $records[0] );

		$iterator->next();
		$this->assertTrue( $iterator->valid() );

		$records = $iterator->current();
		$this->assertInternalType( 'array', $records );

		$iterator->next();
		$this->assertFalse( $iterator->valid() );
	}
}
