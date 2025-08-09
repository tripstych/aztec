<?php

namespace Sellkit_Pro\Contact_Segmentation\Conditions;

use Sellkit_Pro\Contact_Segmentation\Conditions\Condition_Base;

defined( 'ABSPATH' ) || die();

/**
 * Class Cart_Item_Quantity
 *
 * @package Sellkit_Pro\Contact_Segmentation\Conditions
 * @since 1.7.8
 */
class Cart_Item_Quantity extends Condition_Base {

	/**
	 * Condition name.
	 *
	 * @since 1.7.8
	 */
	public function get_name() {
		return 'cart-item-quantity';
	}

	/**
	 * Condition title.
	 *
	 * @since 1.7.8
	 */
	public function get_title() {
		return esc_html__( 'Cart Item Count', 'sellkit-pro' );
	}

	/**
	 * Condition type.
	 *
	 * @since 1.7.8
	 */
	public function get_type() {
		return self::SELLKIT_NUMBER_DROP_DOWN_CONDITION_VALUE;
	}

	/**
	 * Get the options
	 *
	 * @since 1.7.8
	 * @return array
	 */
	public function get_options() {
		$input_value = sellkit_htmlspecialchars( INPUT_GET, 'input_value' );

		return sellkit_get_products( $input_value );
	}

	/**
	 * It is pro feature or not.
	 *
	 * @since 1.7.8
	 */
	public function is_pro() {
		return true;
	}

	/**
	 * All the conditions are not searchable by default.
	 *
	 * @return false
	 * @since 1.7.8
	 */
	public function is_searchable() {
		return true;
	}

	/**
	 * Get actual product id from object.
	 *
	 * @param string $product product object with value and label.
	 * @return integer
	 * @since 1.7.8
	 */
	public function find_actual_product_id( $product ) {
		if ( isset( $product['value'] ) && isset( $product['label'] ) ) {
			return $product['value'];
		}

		foreach ( $product as $key => $value ) {
			if ( is_array( $value ) ) {
				$actual_value = $this->find_actual_product_id( $value );
				if ( null !== $actual_value ) {
					return (int) $actual_value;
				}
			}
		}

		return 0;
	}

	/**
	 * Checks if the values are valid or not.
	 *
	 * @since 1.7.8
	 * @param mixed  $condition_value  The value of condition input.
	 * @param string $operator  Operator name.
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function is_valid( $condition_value, $operator ) {
		if ( empty( $condition_value['item_product'] ) ) {
			return false;
		}

		$product_id    = $this->find_actual_product_id( $condition_value['item_product'] );
		$quantity      = (int) $condition_value['item_quantity'];
		$product_count = 0;
		$cart          = WC()->cart->get_cart();

		foreach ( $cart as $cart_item_key => $cart_item ) {
			if ( (int) $cart_item['product_id'] === (int) $product_id ) {
				$product_count += $cart_item['quantity'];
			}
		}

		if ( 'greater-than' === $operator && $quantity < $product_count ) {
			return true;
		}

		if ( 'less-than' === $operator && $quantity > $product_count && 0 !== $product_count ) {
			return true;
		}

		if ( 'is' === $operator && $quantity === $product_count ) {
			return true;
		}

		return false;
	}
}
