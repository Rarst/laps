<?php

namespace Rarst\Laps\Provider;

use Rarst\Laps\Plugin;

interface Bootable_Provider_Interface {

	/**
	 * @param Plugin $laps Container instance.
	 *
	 * @return void
	 */
	public function boot( Plugin $laps );
}
