<?php

namespace Rarst\Laps;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Record\Record_Collector_Interface;
use Rarst\Laps\Record\Hook_Record_Collector;
use Rarst\Laps\Record\Http_Record_Collector;
use Rarst\Laps\Record\Sql_Record_Collector;
use Rarst\Laps\Manager\Asset_Manager;
use Rarst\Laps\Manager\Load_Order_Manager;
use Rarst\Laps\Manager\Toolbar_Manager;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Main plugin's class.
 */
class Laps extends Container {

	protected $providers = [];

	public function __construct( array $values = [] ) {

		parent::__construct();

		$laps = $this;

		$laps['mustache'] = function () {
			return new \Mustache_Engine( [
				'loader' => new \Mustache_Loader_FilesystemLoader( dirname( __DIR__ ) . '/views' ),
				'cache'  => new Mustache_Cache_FrozenCache( dirname( __DIR__ ) . '/views/cache' ),
			] );
		};

		$laps['stopwatch'] = $laps->factory( function () {
			return new Stopwatch();
		} );

		$laps['records'] = function() {

			$records = [];

			foreach ( $this->providers as $provider ) {
				if ( $provider instanceof Record_Collector_Interface ) {
					$records[] = $provider->get_records();
				}
			}

			return array_merge( ...$records );
		};

		$laps->register( new Load_Order_Manager() );
		$laps->register( new Asset_Manager() );
		$laps->register( new Toolbar_Manager() );

		$laps->register( new Hook_Record_Collector() );
		$laps->register( new Http_Record_Collector() );
		$laps->register( new Sql_Record_Collector() );

		foreach ( $values as $key => $value ) {
			$this->offsetSet( $key, $value );
		}
	}

	public function register( ServiceProviderInterface $provider, array $values = [] ) {

		$this->providers[] = $provider;

		parent::register( $provider, $values );

		return $this;
	}

	/**
	 * Start Stopwatch and timing plugin load immediately, then set up core events and needed hooks.
	 */
	public function on_load() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		foreach ( $this->providers as $provider ) {

			if ( $provider instanceof Bootable_Provider_Interface ) {
				$provider->boot( $this );
			}
		}
	}
}
