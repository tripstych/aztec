<?php
namespace JupiterX_Core\Popup\Triggers\Operators;

use JupiterX_Core\Popup\Triggers\Operator_Base;

defined( 'ABSPATH' ) || die();

/**
 * Class Contains for triggers operator.
 *
 * @since 3.7.0
 */
class Contains extends Operator_Base {
	/**
	 * Operator name.
	 *
	 * @since 3.7.0
	 */
	public function get_name() {
		return 'contains';
	}

	/**
	 * Operator title.
	 *
	 * @since 3.7.0
	 */
	public function get_title() {
		return esc_html__( 'Contains', 'jupiterx-core' );
	}

	/**
	 * Operator validation.
	 *
	 * @since 3.7.0
	 * @param mixed $value            mixed The value of current value.
	 * @param mixed $condition_value  The value of condition input.
	 * @todo this method will be migrated to js codes.
	 */
	public function is_valid( $value, $condition_value ) {
		if ( false !== strpos( $value, $condition_value ) ) {
			return true;
		}

		return false;
	}
}
