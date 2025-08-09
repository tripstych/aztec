<?php

namespace Sellkit_Pro\Compatibility\Wpml\Modules;

defined( 'ABSPATH' ) || die();

/**
 * Class Checkout_Billing
 *
 * This class handles the compatibility of the fields in Sellkit Pro
 * with WPML for translation within Elementor modules.
 *
 * @since 1.9.2
 */
class Product_Filter extends \WPML_Elementor_Module_With_Items {
	/**
	 * Retrieves the field name that holds the items.
	 *
	 * @since 1.9.2
	 *
	 * @return string The name of the field that contains the items.
	 */
	public function get_items_field() {
		return 'filters';
	}

	/**
	 * Retrieves the fields that are translatable.
	 *
	 * @since 1.9.2
	 *
	 * @return array List of fields that support translations.
	 */
	public function get_fields() {
		return [
			'search_text_label',
			'search_text_placeholder',
			'on_sale_switch',
		];
	}

	/**
	 * Retrieves the translation title for each field.
	 *
	 * @since 1.9.2
	 *
	 * @param string $field The field name.
	 *
	 * @return string The title for the translation of the field.
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'search_text_label':
				return esc_html__( 'Sellkit Pro Product Filter: Search Text Label', 'sellkit-pro' );

			case 'search_text_placeholder':
				return esc_html__( 'Sellkit Pro Product Filter: Search Text Placeholder', 'sellkit-pro' );

			case 'on_sale_switch':
				return esc_html__( 'Sellkit Pro Product Filter: On Sale Label', 'sellkit-pro' );

			default:
				return '';
		}
	}

	/**
	 * Retrieves the editor type for each field.
	 *
	 * @since 1.9.2
	 *
	 * @param string $field The field name.
	 *
	 * @return string The editor type for the translation.
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'search_text_label':
				return 'LINE';

			case 'search_text_placeholder':
				return 'LINE';

			case 'on_sale_switch':
				return 'LINE';

			default:
				return '';
		}
	}
}
