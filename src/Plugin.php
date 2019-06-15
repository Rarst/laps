<?php
declare( strict_types=1 );

namespace Rarst\Laps;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Provider\Bootable_Provider_Interface;
use Rarst\Laps\Provider\Hook_Event_Provider;
use Rarst\Laps\Provider\Manager_Provider;
use Rarst\Laps\Provider\Record_Provider;

/**
 * Main plugin's class.
 */
class Plugin extends Container {

	/** @var array $providers */
	protected $providers = [];

	/**
	 * @param array $values Configuration values to apply.
	 * @psalm-param array<string, mixed> $values
	 */
	public function __construct( array $values = [] ) {

		parent::__construct();

		$laps = $this;

		$laps['mustache'] = function (): \Mustache_Engine {
			return new \Mustache_Engine( [
				'loader' => new \Mustache_Loader_FilesystemLoader( dirname( __DIR__ ) . '/src/mustache' ),
				'cache'  => new Mustache_Cache_FrozenCache( dirname( __DIR__ ) . '/src/mustache/cache' ),
			] );
		};

		$laps->register( new Manager_Provider() );
		$laps->register( new Hook_Event_Provider() );
		$laps->register( new Record_Provider() );

		foreach ( $values as $key => $value ) {
			$this->offsetSet( $key, $value );
		}
	}

	/**
	 * @param ServiceProviderInterface $provider Provider.
	 * @param array                    $values   Optional configuration.
	 *
	 * @return $this|static
	 */
	public function register( ServiceProviderInterface $provider, array $values = [] ): self {

		$this->providers[] = $provider;

		parent::register( $provider, $values );

		return $this;
	}

	/**
	 * Start Stopwatch and timing plugin load immediately, then set up core events and needed hooks.
	 */
	public function run(): void {

		foreach ( $this->providers as $provider ) {

			if ( $provider instanceof Bootable_Provider_Interface ) {
				$provider->boot( $this );
			}
		}
	}
}
