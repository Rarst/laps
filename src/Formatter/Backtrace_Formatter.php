<?php
declare( strict_types=1 );

namespace Rarst\Laps\Formatter;

/**
 * Formatter to adjust WP backtrace output to be more short and concise.
 */
class Backtrace_Formatter {

	/** @var string[] $skip String matches to omit. */
	protected static $skip = [
		'wp-blog-header.php',
		'wp-load.php',
		'wp-config.php',
		'admin.php',
		'template-loader.php',
		'locate_template',
		'load_template',
		'_wp_get_current_user',
		'WP_Hook',
	];

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
			'themes/',
			'plugins/',
		];
	}

	/**
	 * @param array|string $backtrace Backtrace input as produced by WP.
	 *
	 * @return array
	 */
	public function format( $backtrace ): array {

		if ( \is_string( $backtrace ) ) {
			$backtrace = explode( ', ', $backtrace );
		} else {
			$backtrace = array_reverse( $backtrace );
		}

		$backtrace = array_filter( $backtrace, [ $this, 'filter' ] );
		$backtrace = array_map( [ $this, 'shorten_include' ], $backtrace );

		// TODO compact redunant function sequences, such as transient into option calls.

		return $backtrace;
	}

	/**
	 * @param string $item Backtrace item.
	 *
	 * @return bool Keep or drop.
	 */
	protected function filter( string $item ): bool {

		foreach ( self::$skip as $match ) {
			if ( false !== strpos( $item, $match ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 *
	 * @param string $item Backtrace item.
	 *
	 * @return string
	 *
	 * @psalm-suppress PossiblyFalseOperand
	 */
	protected function shorten_include( string $item ): string {

		if ( 0 === strpos( $item, 'include' ) || 0 === strpos( $item, 'require' ) ) {

			$path = substr( $item, strpos( $item, '(\'' ) + 2, - 2 );
			$path = wp_normalize_path( $path );
			$path = str_replace( $this->truncate_paths, '', $path );
			if ( ':' === $path[1] ) {
				$path = substr( $path, 2 );
			}
			$path = ltrim( $path, '/' );

			return $path;
		}

		return $item;
	}
}
