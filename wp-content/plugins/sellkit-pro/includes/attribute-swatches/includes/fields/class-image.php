<?php
defined( 'ABSPATH' ) || die();

/**
 * Atribute Swatch Type Color
 *
 * @package Sellkit\Artbees_WC_Attribute_Swatches\Fields
 * @since 1.1.0
 */
class Artbees_WC_Attribute_Swatches_Type_Image extends Artbees_WC_Attribute_Swatches_Field_Base {
	/**
	 * Fields IDs.
	 *
	 * @since 1.1.0
	 *
	 * @access private
	 */
	private const FIELD = [
		'image_sizes',
		'image_size',
		'image_shape',
		'image_catalog',
		'image_catalog_sub_field',
		'image_catalog_sub_field_size',
		'image_layout',
		'image_column_count',
	];

	/**
	 * Toggle box IDs.
	 *
	 * @since 1.1.0
	 *
	 * @access private
	 */
	private const TOGGLE_BOX = [
		'image_column_count',
		'image_catalog_sub_field',
		'image_catalog_sub_field_size',
	];

	private const TYPE = 'image';

	/**
	 * Artbees_WC_Attribute_Swatches_Type_Image constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		add_action( 'woocommerce_after_add_attribute_fields', [ $this, 'get_add_fields' ], 10 );
		add_action( 'woocommerce_after_edit_attribute_fields', [ $this, 'get_edit_fields' ] );
	}

	/**
	 * Generate All Fields.
	 *
	 * @since 1.1.0
	 */
	public function get_add_fields() {
		foreach ( self::FIELD as $field_id ) {
			$output = sprintf(
				'<div class="form-field artbees-form-field artbees-form-field-add-new" data-conditional="%1$s" %2$s>%3$s %4$s %5$s</div>',
				self::TYPE,
				in_array( $field_id, self::TOGGLE_BOX, true ) ? 'data-parent=' . $this->create_field_parent_id( $field_id ) : '',
				$this->create_field_label( $field_id ),
				'image_size' === $field_id ? $this->create_field_number( $field_id ) : $this->create_field_select( $field_id ),
				$this->create_field_description( $field_id )
			);

			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Generate All Fields for Edit.
	 *
	 * @since 1.1.0
	 */
	public function get_edit_fields() {
		foreach ( self::FIELD as $field_id ) {
			$output = sprintf(
				'<tr class="form-field artbees-form-field artbees-form-field-edit" data-conditional="%1$s" %2$s><th scope="row" valign="top">%3$s</th><td>%4$s %5$s</td></tr>',
				esc_attr( self::TYPE ),
				in_array( $field_id, self::TOGGLE_BOX, true ) ? 'data-parent=' . $this->create_field_parent_id( $field_id ) : '',
				$this->create_field_label( $field_id ),
				'image_size' === $field_id ? $this->create_field_number( $field_id ) : $this->create_field_select( $field_id ),
				$this->create_field_description( $field_id )
			);

			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Generate All Fields for Products.
	 *
	 * @param string $attribute  Attribute slug.
	 * @since 1.1.0
	 * @return string
	 */
	public function add_products_fields( $attribute ) {
		$output = '';

		foreach ( self::FIELD as $field_id ) {
			$output .= sprintf(
				'<tr class="form-field artbees-form-field" data-conditional="%1$s" data-taxonomy="%2$s" %3$s><td>%4$s</td><td>%5$s %6$s</td><tr>',
				esc_attr( self::TYPE ),
				esc_attr( $attribute ),
				in_array( $field_id, self::TOGGLE_BOX, true ) ? 'data-parent=' . $this->create_field_parent_id( $field_id ) : '',
				$this->create_field_label( $field_id ),
				'image_size' === $field_id ? $this->create_product_field_number( $field_id, $attribute ) : $this->create_product_field_select( $field_id, $attribute ),
				$this->create_field_description( $field_id )
			);

		}

		return $output;
	}

	/**
	 * List of All Fields.
	 *
	 * @param string $field_id  Attribute slug as field id.
	 * @since 1.1.0
	 * @return array
	 */
	public function swatch_get_fields( $field_id ) {
		$field = [
			'image_sizes' => [
				'title' => __( 'Image Size', 'sellkit-pro' ),
				'type'  => 'select',
				'options' => $this->get_image_sizes(),
				'description' => esc_html__( 'Image size in the single product page', 'sellkit-pro' ),
			],
			'image_size' => [
				'title' => __( 'Image Max-Width', 'sellkit-pro' ),
				'type'  => 'number',
				'default' => 30,
			],
			'image_shape' => [
				'title' => __( 'Swatch shape', 'sellkit-pro' ),
				'type'  => 'select',
				'options' => [
					'circle' => __( 'Circle', 'sellkit-pro' ),
					'square' => __( 'Square', 'sellkit-pro' ),
				],
			],
			'image_catalog' => [
				'title' => __( 'Show in catalog', 'sellkit-pro' ),
				'type'  => 'select',
				'is_parent' => true,
				'options' => [
					'' => __( 'No', 'sellkit-pro' ),
					'1' => __( 'Yes', 'sellkit-pro' ),
				],
			],
			'image_catalog_sub_field' => [
				'title' => __( 'Click behaviour in catalog', 'sellkit-pro' ),
				'type'  => 'select',
				'parent' => 'image_catalog',
				'options' => [
					'link' => __( 'Link to the variable product', 'sellkit-pro' ),
					'image' => __( 'Switch product image', 'sellkit-pro' ),
				],
				'description' => __( 'will be shown when "Show in catalog" is enabled', 'sellkit-pro' ),
			],
			'image_catalog_sub_field_size' => [
				'title' => esc_html__( 'Image Size', 'sellkit-pro' ),
				'type'  => 'select',
				'parent' => 'image_catalog',
				'options' => $this->get_image_sizes(),
				'description' => esc_html__( 'Image size in the product catalog page', 'sellkit-pro' ),
			],
			'image_layout' => [
				'title' => __( 'Layout', 'sellkit-pro' ),
				'type'  => 'select',
				'is_parent' => true,
				'options' => [
					'horizontal' => __( 'Horizontal', 'sellkit-pro' ),
					'vertical' => __( 'Vertical', 'sellkit-pro' ),
					'column' => __( 'Column', 'sellkit-pro' ),
				],
			],
			'image_column_count' => [
				'title' => __( 'Column count', 'sellkit-pro' ),
				'type'  => 'select',
				'parent' => 'image_layout',
				'options' => [
					'2' => __( '2', 'sellkit-pro' ),
					'3' => __( '3', 'sellkit-pro' ),
					'4' => __( '4', 'sellkit-pro' ),
					'5' => __( '5', 'sellkit-pro' ),
					'6' => __( '6', 'sellkit-pro' ),
				],
			],
		];

		return $field[ $field_id ];
	}
}
new Artbees_WC_Attribute_Swatches_Type_Image();
