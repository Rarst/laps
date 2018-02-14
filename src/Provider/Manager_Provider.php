<?php

namespace Rarst\Laps\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Rarst\Laps\Plugin;
use Rarst\Laps\Manager\Asset_Manager;
use Rarst\Laps\Manager\Load_Order_Manager;
use Rarst\Laps\Manager\Toolbar_Manager;

/**
 * Registers manager classes that implement functionality.
 */
class Manager_Provider implements ServiceProviderInterface, Bootable_Provider_Interface {

	/**
	 * @param Container $pimple Container instance.
	 */
	public function register( Container $pimple ) {

		$pimple['managers'] = function ( Plugin $laps ) {
			return [
				new Load_Order_Manager(),
				new Asset_Manager(),
				new Toolbar_Manager( $laps ),
			];
		};
	}

	/**
	 * @param Plugin $laps Container instance.
	 */
	public function boot( Plugin $laps ) {
		$laps['managers'];
	}
}
