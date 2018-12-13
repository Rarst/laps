<?php
declare( strict_types=1 );

namespace Rarst\Laps\Event;

/**
 * Interface to retrieve configuration array of hook events to track.
 */
interface Hook_Event_Config_Interface {

	/**
	 * @see Hook_Record_Collector::add_event()
	 *
	 * @return array[]
	 */
	public function get_events(): array;
}
