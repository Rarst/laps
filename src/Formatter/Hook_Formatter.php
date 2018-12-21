<?php
declare( strict_types=1 );

namespace Rarst\Laps\Formatter;

/**
 * Formatter to process callbacks from a hook instance.
 */
class Hook_Formatter {

	/**
	 * @param \WP_Hook|array $hook
	 *
	 * @return array
	 */
	public function format($hook): array {

		$callbacks = [];

		if ( $hook instanceof \WP_Hook ) {
			$hook = $hook->callbacks;
		}
		ksort( $hook );

		foreach ( $hook as $priority => $functions ) {
			foreach ( $functions as $function ) {
				$callback = $function['function'];

				if ( is_string( $callback ) ) {

				} elseif ( is_a( $callback, 'Closure' ) ) {
					$closure  = new \ReflectionFunction( $callback );
					$callback = 'closure from ' . $closure->getFileName() . '::' . $closure->getStartLine();

					if ( false !== strpos( $callback, 'Hook_Collector' ) ) {
						continue;
					}
				} elseif ( is_object( $callback ) ) {
					$class = new \ReflectionClass( $callback );
					$name  = $class->getName();
					if ( 0 === strpos( $name, 'class@anonymous' ) ) {
						$callback = 'anonymous class from ' . $class->getFileName() . '::' . $class->getStartLine();
					} else {
						$callback = $name;
					}
				} elseif ( is_string( $callback[0] ) ) { // Static method call.
					$callback = $callback[0] . '::' . $callback[1];
				} elseif ( is_object( $callback[0] ) ) {
					$callback = get_class( $callback[0] ) . '->' . $callback[1];
				}

				$callback .= ( 1 === (int) $function['accepted_args'] ) ? '' : "({$function['accepted_args']})";

				$callbacks[] = $callback;
			}
		}

		return $callbacks;
	}
}

