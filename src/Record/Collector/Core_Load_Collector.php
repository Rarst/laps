<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Collector;

use Rarst\Laps\Record\Record;
use Rarst\Laps\Record\Record_Interface;

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
	public function get_records(): array {

		global $timestart;

		$request_time = filter_var( $_SERVER['REQUEST_TIME_FLOAT'], FILTER_VALIDATE_FLOAT );

		$php = 'PHP Load – ' . PHP_VERSION;

		if ( function_exists( 'opcache_get_status' ) ) {
			$zend_status = opcache_get_status();
			$php         .= empty( $zend_status['opcache_enabled'] ) ? '' : ' – OPcache';
		}

		/**
		 * @var float $request_time
		 * @var float $timestart
		 */
		return [
			new Record( $php, $request_time, $timestart - $request_time, '', 'php' ),
			// TODO This includes network plugins on multisite, need conditional label if Laps is network–activated.
			new Record( 'Core and MU Plugins Load', $timestart, $this->timeload - $timestart, '', 'core' ),
		];
	}
}
