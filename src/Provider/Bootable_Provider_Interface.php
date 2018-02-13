<?php

namespace Rarst\Laps\Provider;

use Rarst\Laps\Laps;

interface Bootable_Provider_Interface {

	public function boot( Laps $laps );
}
