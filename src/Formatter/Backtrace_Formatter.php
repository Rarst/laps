<?php

namespace Rarst\Laps\Formatter;

/**
 * Formatter to adjust WP backtrace output to be more short and concise.
 */
class Backtrace_Formatter {

	/** @var array $skip String matches to omit. */
	protected static $skip = [
		'wp-blog-header.php',
		'wp-load.php',
		'wp-config.php',
		'admin.php',
		'locate_template',
		'load_template',
		'_wp_get_current_user',
		'WP_Hook',
	];

	/**
	 * @param array|string $backtrace Backtrace input as produced by WP.
	 *
	 * @return array
	 */
	public function format( $backtrace ) {

		if ( is_string( $backtrace ) ) {
			$backtrace = explode( ', ', $backtrace );
		}

		// TODO shorten includes.

		$backtrace = array_filter( $backtrace, [ $this, 'filter' ] );

		return $backtrace;
	}

	/**
	 * @param string $item Backtrace item.
	 *
	 * @return bool Keep or drop.
	 */
	protected function filter( $item ) {

		foreach ( self::$skip as $match ) {
			if ( false !== strpos( $item, $match ) ) {
				return false;
			}
		}

		return true;
	}
}
