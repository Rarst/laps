<?php
namespace Rarst\Laps;

/**
 * Implements read-only filesystem cache
 */
class Mustache_Cache_FrozenCache extends \Mustache_Cache_FilesystemCache {

	/**
	 * Cache and load a compiled Mustache_Template class.
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return void
	 */
	public function cache( $key, $value ) {

		$this->log(
			\Mustache_Logger::WARNING,
			'Read only cache file missing, evaluating "{className}" class at runtime',
			array( 'className' => $key )
		);

		eval( '?>' . $value );
	}
}
