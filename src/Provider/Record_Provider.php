<?php

namespace Rarst\Laps\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Laps;
use Rarst\Laps\Record\Hook_Record_Collector;
use Rarst\Laps\Record\Http_Record_Collector;
use Rarst\Laps\Record\Record_Collector_Interface;
use Rarst\Laps\Record\Sql_Record_Collector;
use Symfony\Component\Stopwatch\Stopwatch;

class Record_Provider implements ServiceProviderInterface, Bootable_Provider_Interface {

	public function register( Container $pimple ) {

		$pimple['stopwatch'] = $pimple->factory( function () {
			return new Stopwatch();
		} );

		$pimple['collectors'] = function ( Laps $laps ) {
			return [
				new Hook_Record_Collector( $laps['stopwatch'] ),
				new Http_Record_Collector( $laps['stopwatch'] ),
				new Sql_Record_Collector(),
			];
		};

		$pimple['records'] = function ( Laps $laps ) {

			$records = [];

			/** @var Record_Collector_Interface $collector */
			foreach ( $laps['collectors'] as $collector ) {
					$records[] = $collector->get_records();
			}

			return array_merge( ...$records );
		};
	}

	public function boot( Laps $laps ) {
		$laps['collectors'];
	}
}
