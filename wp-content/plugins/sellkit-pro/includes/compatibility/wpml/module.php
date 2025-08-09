<?php

namespace Sellkit_Pro\Compatibility\Wpml;

defined( 'ABSPATH' ) || die();

/**
 * Sellkit Pro WPML compatibility module with WPML.
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
		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'register_widgets_fields' ] );
	}

	/**
	 * Load external classes for repeater fields.
	 *
	 * @since 1.9.2
	 */
	public function load_integration_files() {
		sellkit()->load_files( [
			'compatibility/wpml/modules/product-filter',
		] );
	}

	/**
	 * Register widgets fields for translation.
	 *
	 * @since 1.9.2
	 *
	 * @param array $fields Fields to translate.
	 *
	 * @return array
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public function register_widgets_fields( $fields ) {
		$this->load_integration_files();

		// Smart Coupon.
		$fields['sellkit-personalised-coupons'] = [
			'conditions' => [ 'widgetType' => 'sellkit-personalised-coupons' ],
			'fields'     => [
				[
					'field'       => 'heading',
					'type'        => esc_html__( 'Sellkit Pro Smart Coupon: Heading', 'sellkit-pro' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Sellkit Pro Smart Coupon: Description', 'sellkit-pro' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'expiration_date_text',
					'type'        => esc_html__( 'Sellkit Pro Smart Coupon: Prefix text', 'sellkit-pro' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'action_button_text',
					'type'        => esc_html__( 'Sellkit Pro Smart Coupon: Button Text', 'sellkit-pro' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'action_button_url',
					'type'        => esc_html__( 'Sellkit Pro Smart Coupon: Button URL', 'sellkit-pro' ),
					'editor_type' => 'LINE',
				],
			],
		];

		// Product Filter.
		$fields['sellkit-product-filter'] = [
			'conditions' => [ 'widgetType' => 'sellkit-product-filter' ],
			'fields'     => [
				[
					'field'       => 'reset_text',
					'type'        => esc_html__( 'Sellkit Pro Product Filter: Reset Text', 'sellkit-pro' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => [
				__NAMESPACE__ . '\Modules\Product_Filter',
			],
		];

		return $fields;
	}
}
