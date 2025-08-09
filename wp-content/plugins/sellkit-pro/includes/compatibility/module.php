<?php
namespace Sellkit_Pro\Compatibility;

use Sellkit_Pro\Compatibility\Wpml;

defined( 'ABSPATH' ) || die();

/**
 * Sellkit Pro compatibility module.
 *
 * Sellkit Pro compatibility module handler class is responsible for registering and
 * managing 3rd-party compatibility with Sellkit Pro.
 *
 * @since 1.9.2
 */
class Module {

	/**
	 * Constructor.
	 *
	 * @since 1.9.2
	 */
	public function __construct() {
		// Instantiate compatibility modules.
		new Wpml\Module();
	}
}

new Module();
