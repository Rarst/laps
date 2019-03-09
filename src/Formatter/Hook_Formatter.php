<?php
declare( strict_types=1 );

namespace Rarst\Laps\Formatter;

/**
 * Formatter to process callbacks from a hook instance.
 */
class Hook_Formatter {

	/** @var array $truncate_paths Paths to truncate from includes. */
	protected $truncate_paths = [];

	/**
	 * Set up object properties.
	 */
	public function __construct() {

		$this->truncate_paths = [
			wp_normalize_path( WP_CONTENT_DIR ),
			wp_normalize_path( ABSPATH ),
			'wp-admin/',
		];
	}

	/**
	 * @param \WP_Hook|array $hook Hook instance.
	 *
	 * @return array
	 */
	public function format( $hook ): array {

		$callbacks = [];

		if ( $hook instanceof \WP_Hook ) {
			$hook = $hook->callbacks;
		}
		ksort( $hook );

		if ( $hook ) {
			$functions = array_merge( ...$hook );

			foreach ( $functions as $function ) {
				$callback = $this->get_callback_name( $function['function'], $function['accepted_args'] );

				if ( false !== strpos( $callback, 'Hook_Collector' ) ) {
					continue;
				}

				$callbacks[] = $callback;
			}
		}

		return $callbacks;
	}

	/**
	 * @param string|object|array $callback Hook callback.
	 * @param int                 $args     Number of accepted arguments.
	 *
	 * @return string
	 */
	protected function get_callback_name( $callback, int $args ): string {

		switch ( gettype( $callback ) ) {
			case 'object':
				$callback = $this->get_class_name( $callback );
				break;

			case 'array':
				$class    = is_string( $callback[0] )
					? $callback[0] . '::'
					: $this->get_class_name( $callback[0] ) . '->';
				$callback = $class . $callback[1];
				break;
		}

		$callback .= ( 1 === $args ) ? '' : "({$args})";

		return $callback;
	}

	/**
	 * @param object $object Object to retrieve name for.
	 *
	 * @return string
	 */
	protected function get_class_name( $object ): string {

		if ( is_a( $object, 'CLosure' ) ) {
			$class = new \ReflectionFunction( $object );

			return 'closure from ' . $this->shorten_path( $class->getFileName() ) . ':' . $class->getStartLine();
		}

		$class = new \ReflectionClass( $object );
		$name  = $class->getName();

		if ( 0 === strpos( $name, 'class@anonymous' ) ) {
			return 'anonymous class from ' . $this->shorten_path( $class->getFileName() ) . ':' . $class->getStartLine();
		}

		return $name;
	}

	/**
	 * @param string $path Path to shorten.
	 *
	 * @return string
	 */
	protected function shorten_path( string $path ): string {
		$path = wp_normalize_path( $path );
		$path = str_replace( $this->truncate_paths, '', $path );
		if ( ':' === $path[1] ) {
			$path = substr( $path, 2 );
		}
		$path = ltrim( $path, '/' );

		return $path;
	}
}

