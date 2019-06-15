<?php
declare( strict_types=1 );

namespace Rarst\Laps\Record\Collector;

use Rarst\Laps\Event\Hook_Event_Config_Interface;
use Rarst\Laps\Formatter\Hook_Formatter;
use Rarst\Laps\Record\Stopwatch_Record;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Processes events based on hooked starts and stops.
 */
class Hook_Collector extends Stopwatch_Collector {

	/** @var Hook_Event_Config_Interface[] $event_configs */
	protected $event_configs = [];

	/** @var array $events */
	protected $events = [];

	/**
	 * @var array $callbacks
	 * @psalm-var array<string, \WP_Hook> $callbacks
	 */
	protected $callbacks = [];

	/** @var Hook_Formatter */
	protected $formatter;

	/**
	 * @var array $calls Tracks number of calls for recurrent events.
	 * @psalm-var array<string, int> $calls
	 */
	private $calls = [];

	/**
	 * @param Stopwatch                     $stopwatch     Stopwatch instance.
	 * @param Hook_Event_Config_Interface[] $event_configs Starts and stops configuration.
	 */
	public function __construct( Stopwatch $stopwatch, array $event_configs ) {

		parent::__construct( $stopwatch );

		if ( did_action( 'muplugins_loaded' ) ) {
			$this->start( 'Plugins Load', 'plugin' );
		} else {
			$this->start( 'Network & MU Plugins Load', 'plugin' );
			add_action( 'muplugins_loaded', [ $this, 'muplugins_loaded' ], PHP_INT_MAX );
		}
		$this->add_events( $event_configs['core']->get_events() );
		unset( $event_configs['core'] );
		$this->event_configs = $event_configs;

		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 15 );

		$this->formatter = new Hook_Formatter();
	}

	/**
	 * Time the point between network/MU plugins and regular if network activated.
	 */
	public function muplugins_loaded(): void {
		$this->stop( 'Network & MU Plugins Load' );
		$this->start( 'Plugins Load', 'plugin' );
	}

	/**
	 * Hook events by name and priority from array.
	 *
	 * @param array $stops Starts and stops to hook.
	 */
	public function add_events( array $stops ): void {

		$this->events = array_merge( $this->events, $stops );

		/**
		 * @var int|string $key
		 * @var array      $data
		 * @psalm-var array{0:string} $data
		 */
		foreach ( $stops as $key => $data ) {

			if ( is_int( $key ) ) {
				$this->add_event( ...$data );
				continue;
			}

			/** @var int $priority */
			foreach ( array_keys( $data ) as $priority ) {
				add_action( $key, [ $this, 'tick' ], $priority );
			}
		}
	}

	/**
	 * Add a start/stop pair of hook event.
	 *
	 * @param string      $event          Hook event name.
	 * @param string      $category       Hook event category.
	 * @param string      $start          Start hook name. Pass empty string to ignore.
	 * @param string|null $stop           Stop hook name (defaults to start name). Pass empty string to ignore.
	 * @param int         $start_priority Start hook priority (defaults to -1).
	 * @param int         $stop_priority  Stop hook priority (defaults to max int).
	 *
	 * @psalm-suppress MissingClosureReturnType
	 * @psalm-suppress MissingClosureParamType
	 */
	private function add_event(
		string $event,
		string $category,
		string $start,
		?string $stop = null,
		int $start_priority = - 1,
		int $stop_priority = PHP_INT_MAX
	): void {
		if ( null === $stop ) {
			$stop = $start;
		}

		if ( '' !== $start ) {
			add_action( $start, function ( $input = null ) use ( $event, $category, $start, $stop ) {
				global $wp_filter;

				$event .= $this->get_count_suffix( $event, true );

				if ( 'Sidebar' === $event ) {
					/** @var string $input */
					$event = $input;
				}

				if ( $start === $stop ) {
					/** @psalm-var array<string, \WP_Hook> $wp_filter */
					$this->callbacks[ $event ] = $wp_filter[ $start ];
				}

				$this->start( $event, $category );

				return $input;
			}, $start_priority );
		}

		if ( '' !== $stop ) {
			add_action( $stop, function ( $input = null ) use ( $event ) {

				$event .= $this->get_count_suffix( $event );

				if ( 'Sidebar' === $event ) {
					/** @var string $input */
					$event = $input;
				}

				$this->stop( $event );

				return $input;
			}, $stop_priority );
		}
	}

	/**
	 * Get the suffix to use if event occurs multiple times.
	 *
	 * @param string  $event     Event name.
	 * @param boolean $increment Flag to increment count.
	 *
	 * @return string
	 */
	private function get_count_suffix( string $event, bool $increment = false ): string {

		if ( ! isset( $this->calls[ $event ] ) ) {
			$this->calls[ $event ] = 0;
		}

		if ( $increment ) {
			$this->calls[ $event ] ++;
		}

		$count = (int) $this->calls[ $event ];

		return $count > 1 ? " ({$count})" : '';
	}

	/**
	 * When theme is done possibly add vendor-specific events.
	 */
	public function after_setup_theme(): void {

		foreach ( $this->event_configs as $config ) {
			$this->add_events( $config->get_events() );
		}
	}

	/**
	 * Mark action for the event on Stopwatch.
	 *
	 * @deprecated 3.0:4.0 Deprecated in favor of the new format.
	 * @codeCoverageIgnore
	 *
	 * @param mixed $input Pass through if added to filter.
	 *
	 * @return mixed
	 */
	public function tick( $input = null ) {

		global $wp_filter;

		/** @var string $filter_name */
		$filter_name     = current_filter();
		/**
		 * @var \WP_Hook|array $filter_instance
		 * @var array<string,\WP_hook|array> $wp_filter
		 */
		$filter_instance = $wp_filter[ $filter_name ];
		/** @var int $priority */
		$priority        = $filter_instance instanceof \WP_Hook ? $filter_instance->current_priority() : key( $filter_instance );

		// See https://core.trac.wordpress.org/ticket/41185 on broken priority, but more general sanity check.
		if ( empty( $this->events[ $filter_name ][ $priority ] ) ) {
			return $input;
		}

		$event = \wp_parse_args( $this->events[ $filter_name ][ $priority ], [
			'action'   => 'start',
			'category' => null,
		] );

		if ( 'start' === $event['action'] ) {
			$this->start( $event['event'], $event['category'] );
		} else {
			$this->stop( $event['event'] );
		}

		return $input;
	}

	/**
	 * @return Stopwatch_Record[]
	 */
	public function get_records(): array {

		$this->stop( 'Toolbar' );
		$this->stop( 'Footer Hook' );

		return parent::get_records();
	}

	/**
	 * @param string         $name  Event name.
	 * @param StopwatchEvent $event Stopwatch event instance.
	 *
	 * @return Stopwatch_Record
	 */
	protected function transform( string $name, StopwatchEvent $event ): Stopwatch_Record {

		$description = '';

		if ( isset( $this->callbacks[ $name ] ) ) {
			$description = implode( '<br />', $this->formatter->format( $this->callbacks[ $name ] ) );
		}

		return new Stopwatch_Record( $name, $event, $description );
	}
}
