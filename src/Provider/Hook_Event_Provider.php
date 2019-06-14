<?php
declare( strict_types=1 );

namespace Rarst\Laps\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Event\Core_Events;
use Rarst\Laps\Event\Extension_Events;
use Rarst\Laps\Event\Genesis_Events;
use Rarst\Laps\Event\Hybrid_Events;
use Rarst\Laps\Event\THA_Events;
use Rarst\Laps\Event\WooCommerce_Events;
use Rarst\Laps\Event\Yoast_Events;

/**
 * Registers hook event configuration for bundled vendor events.
 */
class Hook_Event_Provider implements ServiceProviderInterface {

	/**
	 * @param Container $pimple Container instance.
	 *
	 * @psalm-suppress DeprecatedClass
	 */
	public function register( Container $pimple ): void {

		$pimple['hook.events'] = function (): array {
			return [
				'core'        => new Core_Events(),
				'extension'   => new Extension_Events(),
				'genesis'     => new Genesis_Events(),
				'hybrid'      => new Hybrid_Events(),
				'tha'         => new THA_Events(),
				'woocommerce' => new WooCommerce_Events(),
				'yoast'       => new Yoast_Events(),
			];
		};
	}
}
