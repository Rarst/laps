<?php
declare( strict_types=1 );

namespace Rarst\Laps\Provider;

use Rarst\Laps\Plugin;

/**
 * Interface for providers that need to boot up once plugin is ready to start.
 */
interface Bootable_Provider_Interface {

	/**
	 * @param Plugin $laps Container instance.
	 *
	 * @return void
	 */
	public function boot( Plugin $laps ): void;
}
