<?php
defined( 'ABSPATH' ) || die();

/**
 * Field Base Structure
 *
 * @package Sellkit\Artbees_WC_Attribute_Swatches\Fields
 * @since 1.1.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Artbees_WC_Attribute_Swatches_Field_Base {
	/**
	 * List of image sizes.
	 *
	 * @since 1.6.0
	 *
	 * @access private
	 * @var array
	 */
	private static $image_size = [];

	/**
	 * Image sizes in catalog.å
	 *
	 * @since 1.6.0
	 *
	 * @access private
	 */
	private const IMAGE_SIZES = [
		'text_catalog_sub_field_size',
		'color_catalog_sub_field_size',
		'radio_catalog_sub_field_size',
		'image_catalog_sub_field_size',
		'image_sizes',
	];


	/**
	 * Generate Select Fields.
	 *
	 * @param string $field_id  Attribute option id as field id.
	 * @since 1.1.0
	 * @return string
	 */
	public function create_field_select( $field_id ) {
		$is_parent = '';

		foreach ( $this->swatch_get_fields( $field_id ) as $key => $arg ) {
			if ( 'is_parent' === $key ) {
				$is_parent = 'data-is-parent=' . $field_id;
			}
		}

		$field = sprintf(
			'<select id="%1$s" class="artbees-was-select" name="%2$s" %3$s disabled="disabled">',
			$field_id,
			$field_id,
			$is_parent
		);

		// phpcs:disable
		if ( ! empty( $_GET['edit'] ) ) {
			$get_attr = get_option( 'artbees_wc_attributes-' . wp_unslash( $_GET['edit'] ) . '' );

			if ( ! is_string( $get_attr ) ) {
				return;
			}

			$get_attr = json_decode( $get_attr );
		}
		// phpcs:enable

		foreach ( $this->swatch_get_fields( $field_id ) as $key => $arg ) {
			if ( is_array( $arg ) ) {
				foreach ( $arg as $key => $value ) {
					$parameter = ! empty( $get_attr->$field_id ) ? $get_attr->$field_id : '';

					if ( in_array( $field_id, self::IMAGE_SIZES, true ) && empty( $parameter ) ) {
						$parameter = 'woocommerce_thumbnail';
					}

					$selected = strval( $parameter ) === strval( $key ) ? 'selected' : '';

					$field .= '<option value=' . $key . ' ' . $selected . '>' . $value . '</option>';
				}
			}
		}

		$field .= '</select>';

		return $field;
	}

	/**
	 * Generate Dimensions Fields.
	 *
	 * @param string $field_id  Attribute option id as field id.
	 * @since 1.1.0
	 * @return string
	 */
	public function create_field_dimensions( $field_id ) {
		$field    = sprintf( '<div id="%s" class="artbees-was-dimensions">', $field_id );
		$get_attr = [];

		// phpcs:disable
		if ( ! empty( $_GET['edit'] ) ) {
			$get_attr = get_option( 'artbees_wc_attributes-' . wp_unslash( $_GET['edit'] ) . '' );

			if ( ! is_string( $get_attr ) ) {
				return;
			}

			$get_attr = json_decode( $get_attr );
		}
		// phpcs:enable

		$arg = $this->swatch_get_fields( $field_id );

		$parameter = ! empty( $get_attr->$field_id ) ? $get_attr->$field_id : $arg['default'];

		$field .= '<div class="artbees-was-dimensions-item">';
		$field .= sprintf(
			'<input type="range" name="%1$s"  value="%2$s" min="%3$s" max="%4$s" disabled="disabled">',
			$field_id,
			$parameter,
			20,
			100
		);
		$field .= '</div>';

		$field .= '</div>';

		return $field;
	}

	/**
	 * Generate Number Fields.
	 *
	 * @param string $field_id  Attribute option id as field id.
	 * @since 1.6.0
	 * @return string
	 */
	public function create_field_number( $field_id ) {
		$field    = sprintf( '<div id="%s" class="artbees-was-number">', $field_id );
		$get_attr = [];

		// phpcs:disable
		if ( ! empty( $_GET['edit'] ) ) {
			$get_attr = get_option( 'artbees_wc_attributes-' . wp_unslash( $_GET['edit'] ) . '' );

			if ( ! is_string( $get_attr ) ) {
				return;
			}

			$get_attr = json_decode( $get_attr );
		}
		// phpcs:enable

		$arg = $this->swatch_get_fields( $field_id );

		$parameter = ! empty( $get_attr->$field_id ) ? $get_attr->$field_id : $arg['default'];

		$field .= '<div class="artbees-was-number-item">';
		$field .= sprintf(
			'<input type="number" name="%1$s"  value="%2$s" min="%3$s" max="%4$s" disabled="disabled"><p class="description">px</p>',
			esc_attr( $field_id ),
			esc_attr( $parameter ),
			20,
			500
		);
		$field .= '</div>';

		$field .= '</div>';

		return $field;
	}

	/**
	 * Generate Label Fields.
	 *
	 * @param string $field_id  Attribute option id as field id.
	 * @since 1.1.0
	 * @return string
	 */
	public function create_field_label( $field_id ) {
		$field = '';
		foreach ( $this->swatch_get_fields( $field_id ) as $key => $arg ) {
			if ( 'title' === $key ) {
				$field .= '<label for=' . $field_id . '>' . $arg . '</label>';
			}
		}

		return $field;
	}

	/**
	 * Generate Description Fields.
	 *
	 * @param string $field_id  Attribute option id as field id.
	 * @since 1.1.0
	 * @return string
	 */
	public function create_field_description( $field_id ) {
		$field = '';
		foreach ( $this->swatch_get_fields( $field_id ) as $key => $arg ) {
			if ( 'description' === $key ) {
				$field .= '<p class="description">' . $arg . '</p>';
			}
		}

		return $field;
	}

	/**
	 * Return Parent ID.
	 *
	 * @param string $field_id  Attribute option id as field id.
	 * @since 1.1.0
	 * @return string
	 */
	public function create_field_parent_id( $field_id ) {
		foreach ( $this->swatch_get_fields( $field_id ) as $key => $arg ) {
			if ( 'parent' === $key ) {
				return $arg;
			}
		}
	}

	/**
	 * Generate Select Fields For Products.
	 *
	 * @param string $field_id   Attribute option id as field id.
	 * @param string $attribute  Attribute slug as field id.
	 * @since 1.1.0
	 * @return string
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function create_product_field_select( $field_id, $attribute ) {
		$is_parent = '';

		foreach ( $this->swatch_get_fields( $field_id ) as $key => $arg ) {
			if ( 'is_parent' === $key ) {
				$is_parent = 'data-is-parent=' . $field_id;
			}
		}

		$field = sprintf(
			'<select id="%1$s" class="artbees-was-product-select" name="%2$s" %3$s>',
			$field_id,
			'artbees-was[' . $attribute . '][' . $field_id . ']',
			$is_parent
		);

		$parameter = [];

		// phpcs:disable
		if ( ! empty( $_GET['post'] ) ) {
			$parameter = get_post_meta( $_GET['post'], '_artbees-was' );
			$parameter = ! empty( $parameter[0][ $attribute ][ $field_id ] ) ? $parameter[0][ $attribute ][ $field_id ] : "";
		}
		// phpcs:enable

		if ( empty( $parameter ) ) {
			$product_id = sellkit_htmlspecialchars( INPUT_POST, 'product_id' );

			$parameter = get_post_meta( $product_id, '_artbees-was' );
			$parameter = ! empty( $parameter[0][ $attribute ][ $field_id ] ) ? $parameter[0][ $attribute ][ $field_id ] : '';
		}

		foreach ( $this->swatch_get_fields( $field_id ) as $key => $arg ) {
			if ( is_array( $arg ) ) {
				foreach ( $arg as $key => $value ) {
					if ( in_array( $field_id, self::IMAGE_SIZES, true ) && empty( $parameter ) ) {
						$parameter = 'woocommerce_thumbnail';
					}

					$selected = strval( $parameter ) === strval( $key ) ? 'selected' : '';

					$field .= '<option value=' . $key . ' ' . $selected . '>' . $value . '</option>';
				}
			}
		}

		$field .= '</select>';

		return $field;
	}

	/**
	 * Generate Number Fields For Products.
	 *
	 * @param string $field_id  Attribute option id as field id.
	 * @param string $attribute  Attribute slug as field id.
	 * @since 1.6.0
	 * @return string
	 */
	public function create_product_field_number( $field_id, $attribute ) {
		$field     = sprintf( '<div id="%s" class="artbees-was-number">', $field_id );
		$parameter = [];

		// phpcs:disable
		if ( ! empty( $_GET['post'] ) ) {
			$parameter = get_post_meta( wp_unslash( $_GET['post'] ), '_artbees-was' );
		}
		// phpcs:enable

		if ( empty( $parameter ) ) {
			$product_id = sellkit_htmlspecialchars( INPUT_POST, 'product_id' );
			$parameter  = get_post_meta( $product_id, '_artbees-was' );
		}

		$arg       = $this->swatch_get_fields( $field_id );
		$parameter = ! empty( $parameter[0][ $attribute ][ $field_id ] ) ? $parameter[0][ $attribute ][ $field_id ] : $arg['default'];

		$field .= '<div class="artbees-was-number-item">';
		$field .= sprintf(
			'<input type="number" name="%1$s"  value="%2$s" min="%3$s" max="%4$s"><p class="description">px</p>',
			esc_attr( 'artbees-was[' . $attribute . '][' . $field_id . ']' ),
			esc_attr( $parameter ),
			20,
			500
		);
		$field .= '</div>';

		$field .= '</div>';

		return $field;
	}

	/**
	 * Generate Dimensions Fields For Products.
	 *
	 * @param string $field_id   Attribute option id as field id.
	 * @param string $attribute  Attribute slug as field id.
	 * @since 1.1.0
	 * @return string
	 */
	public function create_product_field_dimensions( $field_id, $attribute ) {
		$field     = sprintf( '<div id="%1$s" class="%2$s">', $field_id, 'artbees-was-dimensions' );
		$parameter = [];

		// phpcs:disable
		if ( ! empty( $_GET['post'] ) ) {
			$parameter = get_post_meta( wp_unslash( $_GET['post'] ), '_artbees-was' );
		}
		// phpcs:enable

		if ( empty( $parameter ) ) {
			$product_id = sellkit_htmlspecialchars( INPUT_POST, 'product_id' );
			$parameter  = get_post_meta( $product_id, '_artbees-was' );
		}

		$arg       = $this->swatch_get_fields( $field_id );
		$parameter = ! empty( $parameter[0][ $attribute ][ $field_id ] ) ? $parameter[0][ $attribute ][ $field_id ] : $arg['default'];

		$field .= '<div class="artbees-was-dimensions-item">';
		$field .= sprintf(
			'<input type="range" name="%1$s"  value="%2$s" min="%3$s" max="%4$s">',
			'artbees-was[' . $attribute . '][' . $field_id . ']',
			$parameter,
			20,
			100
		);

		$field .= '</div>';
		$field .= '</div>';

		return $field;
	}

	/**
	 * List of all image size.
	 *
	 * @since 1.6.0
	 * @return array
	 */
	public function get_image_sizes() {
		if ( ! empty( self::$image_size ) ) {
			return self::$image_size;
		}

		global $_wp_additional_image_sizes;

		$default_image_sizes = [ 'thumbnail', 'medium', 'medium_large', 'large' ];

		$image_sizes_default = [];

		foreach ( $default_image_sizes as $size ) {
			$image_sizes_default[ $size ] = [
				'width' => (int) get_option( $size . '_size_w' ),
				'height' => (int) get_option( $size . '_size_h' ),
				'crop' => (bool) get_option( $size . '_crop' ),
			];
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes_default = array_merge( $image_sizes_default, $_wp_additional_image_sizes );
		}

		$wp_image_sizes = $image_sizes_default;

		$image_sizes = [];

		foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
			$size_name = ucwords( str_replace( '_', ' ', $size_key ) );

			if ( is_array( $size_attributes ) ) {
				$size_name .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
			}

			$image_sizes[ $size_key ] = $size_name;
		}

		$image_sizes['full'] = esc_html__( 'Full', 'sellkit-pro' );

		self::$image_size = $image_sizes;

		return self::$image_size;
	}
}
