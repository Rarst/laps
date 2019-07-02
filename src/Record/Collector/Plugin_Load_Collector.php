<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Collector;

use Rarst\Laps\Record\Record;
use Rarst\Laps\Record\Record_Interface;

/**
 * Tracks load time of individual plugins.
 */
class Plugin_Load_Collector implements Record_Collector_Interface {

	/** @var float */
	private $last;

	/** @var array */
	private $entries = [];

	/**
	 * Sets up start time and tracking callbacks.
	 */
	public function __construct() {
		$this->last = microtime( true );

		add_action( 'network_plugin_loaded', [ $this, 'plugin_loaded' ] );
		add_action( 'plugin_loaded', [ $this, 'plugin_loaded' ] );
	}

	/**
	 * Records time for a plugin that just finished loading.
	 *
	 * @param string $plugin Plugin file path.
	 */
	public function plugin_loaded( $plugin ): void {

		$time = microtime( true );

		if ( ! is_string( $plugin ) ) { // Broken hook input from global.
			$this->last = $time;

			return;
		}

		$this->entries[] = [
			'name'     => $plugin,
			'origin'   => $this->last,
			'duration' => $time - $this->last,
		];
		$this->last = $time;
	}

	/**
	 * @return Record_Interface[]
	 */
	public function get_records(): array {
		return array_map( [ $this, 'create_record' ], $this->entries );
	}

	/**
	 * Converts recorded information into a Record instance.
	 *
	 * @param array $entry Data entry for the plugin.
	 */
	private function create_record( array $entry ): Record {
		return new Record(
			plugin_basename( $entry['name'] ),
			$entry['origin'],
			$entry['duration'],
			'',
			'plugin'
		);
	}
}
