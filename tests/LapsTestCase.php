<?php

namespace Rarst\Laps\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class LapsTestCase extends TestCase {

	use MockeryPHPUnitIntegration;

	protected function setUp():void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}
