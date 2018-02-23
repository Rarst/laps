<?php

namespace Rarst\Laps\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Plugin;
use Rarst\Laps\Record\Core_Load_Collector;
use Rarst\Laps\Record\Hook_Record_Collector;
use Rarst\Laps\Record\Http_Record_Collector;
use Rarst\Laps\Record\Record_Collector_Interface;
use Rarst\Laps\Record\Sql_Record_Collector;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Registers record collectors (responsible to gather and compile event data) and their dependencies.
 */
class Record_Provider implements ServiceProviderInterface, Bootable_Provider_Interface {

	/**
	 * @param Container $pimple Container instance.
	 */
	public function register( Container $pimple ) {

		$pimple['stopwatch'] = $pimple->factory( function () {
			return new Stopwatch( true );
		} );

		$pimple['collectors'] = function ( Plugin $laps ) {
			return [
				'core' => new Core_Load_Collector(),
				'hook' => new Hook_Record_Collector( $laps['stopwatch'], $laps['hook.events'] ),
				'http' => new Http_Record_Collector( $laps['stopwatch'] ),
				'sql'  => new Sql_Record_Collector(),
			];
		};

		$pimple['records'] = function ( Plugin $laps ) {

			$records = [];

			/** @var Record_Collector_Interface $collector */
			foreach ( $laps['collectors'] as $collector ) {
				$records[] = $collector->get_records();
			}

			return array_merge( ...$records );
		};
	}

	/**
	 * @param Plugin $laps Container instance.
	 */
	public function boot( Plugin $laps ) {
		$laps['collectors'];
	}
}
