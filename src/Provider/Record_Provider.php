<?php
declare( strict_types=1 );

namespace Rarst\Laps\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Plugin;
use Rarst\Laps\Record\Collector\Core_Load_Collector;
use Rarst\Laps\Record\Collector\Hook_Collector;
use Rarst\Laps\Record\Collector\Http_Collector;
use Rarst\Laps\Record\Collector\Record_Collector_Interface;
use Rarst\Laps\Record\Collector\Sql_Collector;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Registers record collectors (responsible to gather and compile event data) and their dependencies.
 */
class Record_Provider implements ServiceProviderInterface, Bootable_Provider_Interface {

	/**
	 * @param Container $pimple Container instance.
	 */
	public function register( Container $pimple ): void {

		$pimple['stopwatch'] = $pimple->factory( function (): Stopwatch {
			return new Stopwatch( true );
		} );

		$pimple['collectors'] = function ( Plugin $laps ): array {
			return [
				'core' => new Core_Load_Collector(),
				'hook' => new Hook_Collector( $laps['stopwatch'], $laps['hook.events'] ),
				'http' => new Http_Collector( $laps['stopwatch'] ),
				'sql'  => new Sql_Collector(),
			];
		};

		$pimple['records'] = function ( Plugin $laps ): array {

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
	public function boot( Plugin $laps ): void {
		$laps['collectors'];
	}
}
