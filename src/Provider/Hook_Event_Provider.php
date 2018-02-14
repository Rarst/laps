<?php

namespace Rarst\Laps\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Event\Core_Events;
use Rarst\Laps\Event\Genesis_Events;
use Rarst\Laps\Event\Hybrid_Events;
use Rarst\Laps\Event\THA_Events;
use Rarst\Laps\Event\Yoast_Events;

/**
 * Registers hook event configuration for bundled vendor events.
 */
class Hook_Event_Provider implements ServiceProviderInterface {

	/**
	 * @param Container $pimple Container instance.
	 */
	public function register( Container $pimple ) {

		$pimple['hook.events'] = function () {
			return [
				'core'    => new Core_Events(),
				'genesis' => new Genesis_Events(),
				'hybrid'  => new Hybrid_Events(),
				'tha'     => new THA_Events(),
				'yoast'   => new Yoast_Events(),
			];
		};
	}
}
