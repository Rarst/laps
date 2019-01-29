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

	/** @var bool Flag if Laps is network activated. */
	private $network_activated;

	/**
	 * Store plugin load start mark.
	 */
	public function __construct() {
		$this->timeload          = microtime( true );
		$this->network_activated = ! did_action( 'muplugins_loaded' );
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

		$load = $this->network_activated ? 'Core Load' : 'Core & MU Plugins Load';

		/**
		 * @var float $request_time
		 * @var float $timestart
		 */
		return [
			new Record( $php, $request_time, $timestart - $request_time, '', 'php' ),
			new Record( $load, $timestart, $this->timeload - $timestart, '', 'core' ),
		];
	}
}
