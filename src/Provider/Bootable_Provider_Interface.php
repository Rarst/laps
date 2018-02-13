<?php

namespace Rarst\Laps\Provider;

use Rarst\Laps\Plugin;

interface Bootable_Provider_Interface {

	public function boot( Plugin $laps );
}
