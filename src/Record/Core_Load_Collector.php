<?php

namespace Rarst\Laps\Record;

/**
 * Covers pre–plugin load time from available data.
 */
class Core_Load_Collector implements Record_Collector_Interface {

	/** @var float $timeload */
	protected $timeload;

	/**
	 * Store core load end, plugin load start mark.
	 */
	public function __construct() {
		$this->timeload = microtime( true );
	}

	/**
	 * @return Record_Interface[]
	 */
	public function get_records() {

		global $timestart;

		$request_time = $_SERVER['REQUEST_TIME_FLOAT'];

		return [
			new Record( 'PHP Load', $request_time, $timestart - $request_time, '', 'php' ),
			// TODO This includes network plugins on multisite, need conditional label if Laps is network–activated.
			new Record( 'Core and MU Plugins Load', $timestart, $this->timeload - $timestart, '', 'core' ),
		];
	}
}
