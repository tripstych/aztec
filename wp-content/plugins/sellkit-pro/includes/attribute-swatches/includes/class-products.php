<?php

defined( 'ABSPATH' ) || die();
/**
 * Prodcuts Atribute Swatches
 *
 * @package Sellkit\Artbees_WC_Attribute_Swatches\Products
 * @since 1.1.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Artbees_WC_Attribute_Swatches_Products extends Artbees_WC_Attribute_Swatches_Products_Fields {
	/**
	 * Swatch data for current product
	 *
	 * @var array $swatch_data
	 */
	public $swatch_data = [];

	/**
	 * Attribute data.
	 *
	 * @var array
	 */
	public $attribute_data = [];


	/**
	 * Artbees_WC_Attribute_Swatches_Products constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		if ( ! sellkit_pro()->is_active_sellkit_pro ) {
			return;
		}

		add_action( 'woocommerce_product_write_panel_tabs', [ $this, 'product_tab' ] );
		add_action( 'woocommerce_product_data_panels', [ $this, 'product_tab_fields' ] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_product_fields' ] );
		add_action( 'wp_ajax_artbees_product_swatches_generate_product_options', [ $this, 'generate_product_options' ] );
		add_action( 'wp_ajax_sellkit_get_swatches', [ $this, 'ajax_product_tab_fields' ] );
	}

	/**
	 * Products: Return Tab Item.
	 *
	 * @since 1.1.0
	 */
	public function product_tab() {
		printf(
			'<li class="%1$s-options-tab show_if_variable"><a href="#%1$s-options" data-product="%2$s"><span>%3$s</span></a></li>',
			'artbees-was',
			esc_attr( get_the_ID() ),
			esc_html__( 'Swatches', 'sellkit-pro' )
		);
	}

	/**
	 * Products: Handle swatch tab content.
	 *
	 * @since 1.6.4
	 */
	public function ajax_product_tab_fields() {
		$product_id = sellkit_htmlspecialchars( INPUT_POST, 'product_id' );
		check_ajax_referer( 'was-admin', 'nonce' );

		if ( empty( $product_id ) ) {
			return;
		}

		ob_start();
		$this->product_tab_fields( $product_id );

		$content = ob_get_clean();

		wp_send_json_success( $content );
	}

	/**
	 * Products: Return Tab Fiels.
	 *
	 * @param int|null $ajax_product_id Product id on ajax call.
	 * @return string
	 */
	public function product_tab_fields( $ajax_product_id = null ) {
		$product_id = sellkit_htmlspecialchars( INPUT_GET, 'post' );

		if ( empty( $product_id ) ) {
			$product_id = $ajax_product_id;
		}

		if ( empty( $product_id ) ) {
			return;
		}

		$fields = '<div id="artbees-was-options" class="panel wc-metaboxes-wrapper">';

		$attributes = $this->get_attributes_for_product( $product_id );

		if ( empty( $attributes ) ) {
			return;
		}

		$fields .= '<div class="wc-metaboxes">';

		foreach ( $attributes as $attribute ) {
			// phpcs:disable
			$attributes_fields[ $attribute['slug'] ] = $this->swatch_add_field( $attribute['slug'] );
			// phpcs:enable

			$get_attribute = get_post_meta( $product_id, '_artbees-was' );
			$get_attribute = ! empty( $get_attribute[0][ $attribute['slug'] ]['swatch_type'] ) ? ' ' . $get_attribute[0][ $attribute['slug'] ]['swatch_type'] . __( ' Swatch', 'sellkit-pro' ) : '';

			$fields .= sprintf(
				'<div data-taxonomy="%1$s" data-product-id="%2$s" class="%1$s wc-metabox closed taxonomy artbees-was-attribute-wrapper postbox">',
				esc_attr( $attribute['slug'] ),
				esc_attr( $product_id )
			);

			$fields .= sprintf(
				'<h3 class="attribute-name artbees-was-attribute-name"><div class="handlediv" title="%1$s" aria-expanded="true"></div><strong>%2$s</strong><span class="artbees-was-swatch-type">%3$s</span></h3>',
				esc_html__( 'Click to toggle', 'sellkit-pro' ),
				$attribute['label'] . ':',
				! empty( $get_attribute ) ? $get_attribute : esc_html__( ' Default', 'sellkit-pro' )
			);

			$fields .= '<div class="wc-metabox-content" style="display: none;"><table cellpadding="0" cellspacing="0" class="artbees-was-attributes"><tbody>';

			foreach ( $attributes_fields as $key => $field ) {
				$fields_value = sprintf(
					'<tr class="%1$s"><td>%2$s</td><td>%3$s</td></tr>',
					'artbees-was-attribute-row artbees-was-attributes_' . str_replace( '_', '-', $key ) . '',
					$field['label'],
					$field['field']
				);
			}

			$fields .= $fields_value;
			$fields .= $this->swatch_add_types_field( $attribute['slug'] );
			$fields .= sprintf(
				'<tr id="artbees-was-terms-options" class="%1$s">%2$s</tr>',
				'artbees-was-attribute-row artbees-was-attributes_' . $attribute['slug'] . '',
				$this->create_product_options( $attribute, $ajax_product_id )
			);

			$fields .= '</tbody></table></div>';
			$fields .= '</div>';
		}

		$fields .= '</div>';
		$fields .= '</div>';

		echo $fields; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Products: Products Custom Fields
	 *
	 * @param int $product_id Product id.
	 * @since 1.1.0
	 */
	public function save_product_fields( $product_id ) {
		$product_settings = [];

		// phpcs:disable
		if ( isset( $_POST['artbees-was'] ) ) {
			if ( empty( $_POST['artbees-was'] ) ) {
				return;
			}

			$product = wc_get_product( $product_id );

			foreach ( wp_unslash( $_POST['artbees-was'] ) as $key => $value ) {
				$product_settings[ $key ] = [ 'swatch_type' => '' ];

				if ( ! empty( $value['swatch_type'] ) ) {
					$product_settings[ $key ] = $value;
				}
			}

			$product->update_meta_data( '_artbees-was', $product_settings );
			$product->save();
		}
		// phpcs:enable
	}

	/**
	 * Products: Get variation attributes for product
	 *
	 * @param int $product_id Product id.
	 * @return bool|array
	 */
	public function get_attributes_for_product( $product_id ) {
		if ( ! $product_id ) {
			return false;
		}

		$product              = wc_get_product( $product_id );
		$attributes           = $product->get_attributes();
		$variation_attributes = [];

		if ( ! $attributes ) {
			return false;
		}

		foreach ( $attributes as $attribute ) {
			if ( ! $attribute->get_variation() ) {
				continue;
			}

			$variation_attribute = [
				'options' => [],
			];

			$options          = [];
			$attribute_object = [];

			if ( $attribute->is_taxonomy() ) {
				$variation_attribute['slug'] = $attribute->get_name();

				$options          = wp_get_post_terms( $product_id, $attribute->get_name() );
				$attribute_object = get_taxonomy( $attribute->get_name() );

				$variation_attribute['label'] = $attribute_object->label;

				$this->attribute_data[ $attribute->get_name() ] = $attribute->get_id();

				if ( $options ) {
					foreach ( $options as $option ) {
						$variation_attribute['options'][] = [
							'id'   => $option->term_id,
							'slug' => $option->slug,
							'name' => $option->name,
							'term' => $option,
						];
					}
				}
			}

			if ( isset( $variation_attribute['slug'] ) ) {
				$variation_attributes[ $variation_attribute['slug'] ] = $variation_attribute;
			}
		}

		return $variation_attributes;
	}

	/**
	 * Products: Add Swatch Type Form.
	 *
	 * @since 1.1.0
	 * @param string $attribute attribute slug.
	 * @return array
	 */
	public function swatch_add_field( $attribute ) {
		$artbees_attributes = new Artbees_WC_Attribute();

		$fields['label'] = sprintf(
			'<label for="attribute_type">%s</label>',
			__( 'Swatch type', 'sellkit-pro' )
		);

		$fields['field'] = '<div class="form-field">';

		$fields['field'] .= sprintf(
			'<select name="%s" id="product_attribute_type" class="postform">',
			'artbees-was[' . $attribute . '][swatch_type]'
		);

		foreach ( $artbees_attributes->product_swatches_types() as $key => $types ) {
			// phpcs:disable
			$parameter = ! empty( $_GET['post'] ) ? get_post_meta( wp_unslash( $_GET['post'] ), '_artbees-was' ) : [];

			if ( empty( $parameter ) ) {
				$product_id = sellkit_htmlspecialchars( INPUT_POST, 'product_id' );

				$parameter = get_post_meta( $product_id, '_artbees-was' );
			}

			$parameter = ! empty( $parameter[0][ $attribute ]['swatch_type'] ) ? $parameter[0][ $attribute ]['swatch_type'] : '';
			// phpcs:enable

			$selected = $key === $parameter ? 'selected' : '';

			$fields['field'] .= sprintf(
				'<option value="%1$s" %2$s>%3$s</option>',
				$key,
				$selected,
				$types
			);
		}

		$fields['field'] .= '</select>';
		$fields['field'] .= '</div>';

		return $fields;
	}

	/**
	 * Products: Add Swatch Types Fields.
	 *
	 * @since 1.1.0
	 * @param string $attribute attribute slug.
	 * @return string
	 */
	public function swatch_add_types_field( $attribute ) {
		$artbees_attributes = new Artbees_WC_Attribute();

		$fields = '';

		foreach ( $artbees_attributes->product_swatches_types() as $key => $types ) {
			if ( ! empty( $key ) ) {
				$type_classes = 'Artbees_WC_Attribute_Swatches_Type_' . ucwords( $key );

				if ( ! class_exists( $type_classes ) ) {
					continue;
				}

				$attribute_type = new $type_classes();

				$fields .= $attribute_type->add_products_fields( $attribute );
			}
		}

		return $fields;
	}

	/**
	 * Products: Get Product Swatch Data for Attribute.
	 *
	 * @since 1.1.0
	 * @param int    $product_id     Product id.
	 * @param string $attribute_slug Attribute slug.
	 * @return array
	 */
	public function get_product_swatch_data( $product_id, $attribute_slug ) {
		if ( ! isset( $this->swatch_data[ $product_id ] ) ) {
			$product                          = wc_get_product( $product_id );
			$this->swatch_data[ $product_id ] = $product->get_meta( '_artbees-was', true );
		}

		if ( isset( $this->swatch_data[ $product_id ][ $attribute_slug ] ) ) {
			return $this->swatch_data[ $product_id ][ $attribute_slug ];
		}

		return $this->swatch_data;
	}

	/**
	 * Products: Generate Attributes Terms Fields For Products.
	 *
	 * @since 1.1.0
	 * @param array    $attribute       Attribute data.
	 * @param int|null $ajax_product_id Product id on ajax call.
	 * @return string
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function create_product_options( $attribute, $ajax_product_id = null ) {
		if ( empty( $attribute ) ) {
			return '';
		}

		$product_id = sellkit_htmlspecialchars( INPUT_GET, 'post' );

		if ( empty( $product_id ) ) {
			$product_id = $ajax_product_id;
		}

		if ( empty( $product_id ) ) {
			return '';
		}

		$saved_values = $this->get_product_swatch_data( $product_id, $attribute['slug'] );

		$get_attribute = get_post_meta( $product_id, '_artbees-was' );
		$attribue_type = ! empty( $get_attribute[0][ $attribute['slug'] ]['swatch_type'] ) ? $get_attribute[0][ $attribute['slug'] ]['swatch_type'] : '';

		$field = '';

		if ( in_array( $attribue_type, [ 'image', 'color' ], true ) ) {
			$attribute_term_page = add_query_arg(
				[
					'taxonomy' => $attribute['slug'],
					'post_type' => 'product',
				],
				admin_url( 'edit-tags.php' )
			);

			$selected_attribute_type = 'color' === $attribue_type ? esc_html__( 'colors', 'sellkit-pro' ) : esc_html__( 'images', 'sellkit-pro' );

			$heading     = esc_html( $attribue_type ) . ' ' . esc_html__( 'variations', 'sellkit-pro' );
			$description = sprintf(
				/* translators: %s: swatch type name, */
				esc_html__( 'Please define %s for your swatches.', 'sellkit-pro' ),
				esc_html( $selected_attribute_type ),
			);

			$current_attribute = $this->get_current_attribute( $saved_values, $attribute['slug'] );

			if ( ! empty( $current_attribute ) && $current_attribute === $attribue_type ) {
				$heading = sprintf(
					/* translators: %s: swatch type */
					esc_html__( 'custom %s variations', 'sellkit-pro' ),
					$attribue_type
				);

				$description = sprintf(
					/* translators: %1$s: swatch type name, %2$s: product attribute anchor tag */
					esc_html__( 'The global %1$s you choose in %2$s will be used instead if left blank.', 'sellkit-pro' ),
					esc_html( $selected_attribute_type ),
					'<a href=' . esc_url( $attribute_term_page ) . ' target="_blank">' . esc_html__( 'Product Attributes', 'sellkit-pro' ) . '</a>'
				);
			}

			$field .= sprintf(
				/* translators: %1$s: heading text, %2$s: swatch type name */
				'<td><h4>%1$s</h4><small>%2$s</small>',
				esc_html( $heading ),
				$description
			);
		}

		$field .= '<td class="artbees-was-swatch-options-wrapper">';

		$field .= sprintf(
			'<div class="%1$s">',
			'artbees-was-swatch-options'
		);

		$field_group = '';

		foreach ( $attribute['options'] as $option ) {
			$field_option = '<div class="artbees-was-swatch-options-items">';

			$field_option .= $this->output_attribute_product_term_fields( $attribue_type, $option['slug'], $option['term']->taxonomy, $saved_values, $option['name'] );

			$field_option .= '</div>';
			$field_group  .= $field_option;
		}

		$field .= $field_group;
		$field .= '</div>';

		if ( ! in_array( $attribue_type, [ 'image', 'color' ], true ) ) {
			$field .= '</td>';
		}

		return $field;
	}


	/**
	 * Products: Generate Attributes Terms Fields For Products.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function generate_product_options() {
		$product_id = sellkit_htmlspecialchars( INPUT_POST, 'product_id' );

		if ( ! isset( $product_id ) ) {
			return;
		}

		$attributes = $this->get_attributes_for_product( sanitize_text_field( $product_id ) );

		if ( empty( $attributes ) ) {
			return;
		}

		$terms_taxonomy = sellkit_htmlspecialchars( INPUT_POST, 'terms_taxonomy' );

		$attributes   = $attributes [ sanitize_text_field( $terms_taxonomy ) ];
		$saved_values = $this->get_product_swatch_data( sanitize_text_field( $product_id ), $attributes['slug'] );

		$attribue_type = sellkit_htmlspecialchars( INPUT_POST, 'swatch_type' );

		$field = '';

		if ( in_array( $attribue_type, [ 'image', 'color' ], true ) ) {
			$attribute_term_page = add_query_arg(
				[
					'taxonomy' => $attributes['slug'],
					'post_type' => 'product',
				],
				admin_url( 'edit-tags.php' )
			);

			$selected_attribute_type = 'color' === $attribue_type ? esc_html__( 'colors', 'sellkit-pro' ) : esc_html__( 'images', 'sellkit-pro' );

			$heading     = esc_html( $attribue_type ) . ' ' . esc_html__( 'variations', 'sellkit-pro' );
			$description = sprintf(
				/* translators: %s: swatch type name, */
				esc_html__( 'Please define %s for your swatches.', 'sellkit-pro' ),
				esc_html( $selected_attribute_type ),
			);

			$current_attribute = $this->get_current_attribute( $saved_values, $terms_taxonomy );

			if ( ! empty( $current_attribute ) && $current_attribute === $attribue_type ) {
				$heading = sprintf(
					/* translators: %s: swatch type */
					esc_html__( 'custom %s variations', 'sellkit-pro' ),
					$attribue_type
				);

				$description = sprintf(
					/* translators: %1$s: swatch type name, %2$s: product attribute anchor tag */
					esc_html__( 'The global %1$s you choose in %2$s will be used instead if left blank.', 'sellkit-pro' ),
					esc_html( $selected_attribute_type ),
					'<a href=' . esc_url( $attribute_term_page ) . ' target="_blank">' . esc_html__( 'Product Attributes', 'sellkit-pro' ) . '</a>'
				);
			}

			$field .= sprintf(
				/* translators: %1$s: heading text, %2$s: swatch type name */
				'<td><h4>%1$s</h4><small>%2$s</small>',
				esc_html( $heading ),
				$description
			);
		}

		$field .= '<td class="artbees-was-swatch-options-wrapper">';

		$field .= sprintf(
			'<div class="%1$s">',
			'artbees-was-swatch-options'
		);

		$field_group = '';

		foreach ( $attributes['options'] as $option ) {
			$field_option = '<div class="artbees-was-swatch-options-items">';

			$field_option .= $this->output_attribute_product_term_fields( $attribue_type, $option['slug'], $option['term']->taxonomy, $saved_values, $option['name'] );

			$field_option .= '</div>';
			$field_group  .= $field_option;
		}

		$field .= $field_group;
		$field .= '</div>';

		if ( ! in_array( $attribue_type, [ 'image', 'color' ], true ) ) {
			$field .= '</td>';
		}

		wp_send_json_success( $field );

	}

	/**
	 * Get global attribute type.
	 *
	 * @since 1.6.0
	 * @param array  $saved_values Saved data.
	 * @param string $taxonomy     Attribute slug.
	 * @return string
	 */
	private function get_current_attribute( $saved_values, $taxonomy ) {
		if ( empty( $this->attribute_data[ $taxonomy ] ) ) {
			return $saved_values['swatch_type'];
		}

		$settings = get_option( 'artbees_wc_attributes-' . $this->attribute_data[ $taxonomy ] );
		$settings = json_decode( $settings, true );

		if ( ! is_array( $settings ) || ! array_key_exists( 'attribute_type', $settings ) ) {
			return '';
		}

		return $settings['attribute_type'];
	}
}
new Artbees_WC_Attribute_Swatches_Products();

