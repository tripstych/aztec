<?php


defined( 'ABSPATH' ) || die();

/**
 * Prodcuts Atribute Loop for frontend
 *
 * @package Sellkit\Artbees_WC_Attribute_Swatches\Frontend
 * @since 1.1.0
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Artbees_WC_Attribute_Loop extends Artbees_WC_Attribute_Frontend {
	/**
	 * WooCommerce available variations.
	 *
	 * @since 1.5.5
	 * @var array
	 */
	public $available_variations = [];

	/**
	 * Artbees_WC_Attribute_Loop constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		if ( ! sellkit_pro()->is_active_sellkit_pro ) {
			return;
		}

		if ( $this->check_is_variable_product() ) {
			return;
		}

		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'shop_loop_attributes' ], 9 );
		add_filter( 'woocommerce_product_get_image', [ $this, 'handle_default_image' ], 10, 2 );
	}

	/**
	 * Handle product image on load.
	 *
	 * @param array  $product_image Current product featured image.
	 * @param object $product       Current product data.
	 * @since 1.5.5
	 * @return string
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function handle_default_image( $product_image, $product ) {
		$is_enabled = apply_filters( 'sellkit_attribute_swatches_enable_default_variation', true );

		if ( ! $is_enabled ) {
			return $product_image;
		}

		$product_attributes = $product->get_attributes();

		if ( empty( $product_attributes ) || is_singular( 'product' ) || ! $product->is_type( 'variable' ) ) {
			return $product_image;
		}

		$default_variation  = is_a( $product, 'WC_Product' ) ? $product->get_default_attributes() : [];
		$selected_attribute = false;

		if ( empty( $default_variation ) ) {
			return $product_image;
		}

		$first_attribute = array_key_first( $default_variation );

		foreach ( $product_attributes as $key => $attribute ) {
			$terms    = $this->get_attribute_terms( $product->get_id(), $key );
			$settings = $this->get_attribute_general_settings( $attribute['id'], $attribute['name'] );

			$catalog_type = $this->get_catalog_type( $settings );

			if ( $key !== $first_attribute ) {
				continue;
			}

			foreach ( $terms as $term ) {
				if ( 'link' !== $catalog_type && ! empty( $default_variation[ $term->taxonomy ] ) && $term->slug === $default_variation[ $term->taxonomy ] ) {
					$selected_attribute = true;
				}

				if ( empty( $this->available_variations[ $product->get_id() ] ) ) {
					$this->available_variations[ $product->get_id() ] = $product->get_available_variations();
				}

				$variations = ! empty( $this->available_variations[ $product->get_id() ] ) ? $this->available_variations[ $product->get_id() ] : [];

				if ( ! empty( $this->available_variations ) && $selected_attribute ) {
					$variation_image = '';

					foreach ( $variations as $variation ) {
						if ( in_array( $term->slug, $variation['attributes'], true ) && ! empty( $variation['image']['full_src'] ) ) {
							$variation_image = wp_get_attachment_image( $variation['image_id'], 'full' );
						}
					}

					return $variation_image;
				}
			}
		}

		return $product_image;
	}

	/**
	 * Add Attribute for Shop Product.
	 *
	 * @since 1.1.0
	 */
	public function shop_loop_attributes() {
		if ( is_singular( 'product' ) ) {
			return;
		}

		echo $this->shop_loop_attributes_data(); // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get Attribute data for Shop Product.
	 *
	 * @since 1.1.0
	 */
	private function shop_loop_attributes_data() {
		global $product;

		$product_id         = $product->get_id();
		$product_attributes = $product->get_attributes();

		if ( empty( $product_attributes ) ) {
			return;
		}

		$content = '';

		$default_variation = is_a( $product, 'WC_Product' ) ? $product->get_default_attributes() : [];
		$first_attribute   = array_key_first( $default_variation );

		foreach ( $product_attributes as $key => $attribute ) {
			$terms    = $this->get_attribute_terms( $product_id, $key );
			$settings = $this->get_attribute_general_settings( $attribute['id'], $attribute['name'] );
			$class    = $this->generate_attributes_class_for_settings( $settings );

			$content .= $this->shop_loop_attributes_structure( $terms, $class, $settings, $key, $product_id, $first_attribute, $default_variation );
		}

		return $content;
	}

	/**
	 * Generate Attribute Structure for Shop Product.
	 *
	 * @since 1.1.0
	 * @param array  $terms             Product attribute term.
	 * @param string $class             Attribute term CSS classes.
	 * @param array  $settings          Attribute term settings data.
	 * @param string $slug              Attribute slug.
	 * @param int    $product_id        Product id.
	 * @param string $first_attribute   First attribute of product.
	 * @param array  $default_variation Default variations of product.
	 * @return string
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	private function shop_loop_attributes_structure( $terms, $class, $settings, $slug, $product_id, $first_attribute, $default_variation ) {
		$product = wc_get_product( $product_id );

		if ( ! $product->is_type( 'variable' ) ) {
			return;
		}

		$variation_image_size = $this->get_variation_image_size( $settings );
		$catalog_type         = $this->get_catalog_type( $settings );

		$featured_image_data = [];

		if ( 'image' === $catalog_type ) {
			$featured_image      = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), $variation_image_size );
			$featured_image_data = [
				'src' => ! empty( $featured_image[0] ) ? $featured_image[0] : '',
				'width' => ! empty( $featured_image[1] ) ? $featured_image[1] : '',
				'height' => ! empty( $featured_image[2] ) ? $featured_image[2] : '',
			];
		}

		$structure = sprintf(
			'<ul class="artbees-was-swatches artbees-was-swatches-catalog %1$s" data-image-src="%2$s" data-image-width="%3$s" data-image-height="%4$s" >',
			$class,
			! empty( $featured_image_data['src'] ) ? $featured_image_data['src'] : '',
			! empty( $featured_image_data['width'] ) ? $featured_image_data['width'] : '',
			! empty( $featured_image_data['height'] ) ? $featured_image_data['height'] : '',
		);

		if ( ! $this->get_catalog_mode( $settings ) ) {
			return;
		}

		foreach ( $terms as $term ) {
			$selected_attribute = false;

			$is_enabled = apply_filters( 'sellkit_attribute_swatches_enable_default_variation', true );

			if (
				$is_enabled &&
				$slug === $first_attribute &&
				'link' !== $catalog_type &&
				! empty( $default_variation[ $term->taxonomy ] ) &&
				$term->slug === $default_variation[ $term->taxonomy ]
			) {
				$selected_attribute = true;
			}

			if (
				'link' !== $catalog_type &&
				apply_filters( 'sellkit_product_filter_selected_attribute', '' ) === $term->slug
			) {
				$selected_attribute = true;
			}

			$catalog_data = $this->get_catalog_type_data( $catalog_type, $term, $product_id, $variation_image_size );

			$variation_src = $catalog_data;
			$image_width   = '';
			$image_height  = '';

			if ( is_array( $catalog_data ) ) {
				$variation_src = $catalog_data['src'];
				$image_width   = $catalog_data['width'];
				$image_height  = $catalog_data['height'];
			}

			$structure .= sprintf(
				'<li class="artbees-was-swatches-item"><a href="%1$s" class="artbees-was-swatch" %2$s %3$s data-term="%4$s" data-attribute="%5$s" %6$s>%7$s</a></li>',
				$variation_src,
				empty( $image_width ) ? '' : 'data-width=' . $image_width,
				empty( $image_height ) ? '' : 'data-height=' . $image_height,
				$term->slug,
				'#' . $term->taxonomy,
				'link' === $catalog_type ? 'data-catalog=link' : 'data-catalog=image',
				$this->get_attribute_term_data( $term, $settings, $slug, $selected_attribute )
			);
		}

		$structure .= '</ul>';

		return $structure;
	}

	/**
	 * Get Catalog Mode.
	 *
	 * @since 1.1.0
	 * @param array $settings  attribute term settings data.
	 */
	private function get_catalog_mode( $settings ) {
		if ( empty( $settings ) ) {
			return false;
		}

		$type = ! empty( $settings['attribute_type'] ) ? $settings['attribute_type'] : '';

		if ( ! empty( $settings['swatch_type'] ) ) {
			$type = $settings['swatch_type'];
		}

		$valid_key = $type . '_catalog';

		if ( empty( $settings[ $valid_key ] ) || 'selected' === $settings[ $valid_key ] ) {
			return false;
		}

		return true;
	}

	/**
	 * Get Catalog type.
	 *
	 * @since 1.1.0
	 * @param array $settings  attribute term settings data.
	 * @return string
	 */
	private function get_catalog_type( $settings ) {
		$type = ! empty( $settings['attribute_type'] ) ? $settings['attribute_type'] : '';

		if ( ! empty( $settings['swatch_type'] ) ) {
			$type = $settings['swatch_type'];
		}

		$valid_key = $type . '_catalog_sub_field';

		if ( ! isset( $settings[ $valid_key ] ) ) {
			return '';
		}

		if ( 'link' === $settings[ $valid_key ] ) {
			return $settings[ $valid_key ];
		}

		return $settings[ $valid_key ];
	}

	/**
	 * Get variation image size.
	 *
	 * @since 1.5.5
	 * @param array $settings  attribute term settings data.
	 * @return string
	 */
	private function get_variation_image_size( $settings ) {
		$type = ! empty( $settings['attribute_type'] ) ? $settings['attribute_type'] : '';

		if ( ! empty( $settings['swatch_type'] ) ) {
			$type = $settings['swatch_type'];
		}

		$valid_key = $type . '_catalog_sub_field_size';

		if ( empty( $settings[ $valid_key ] ) ) {
			return 'full_src';
		}

		return $settings[ $valid_key ];
	}

	/**
	 * Get Catalog Data.
	 *
	 * @since 1.1.0
	 * @param string $type term catalog type name.
	 * @param object $term term data.
	 * @param int    $id   product id.
	 * @param string $size variation image size.
	 * @return string|array
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	private function get_catalog_type_data( $type, $term, $id, $size ) {
		if ( 'link' === $type ) {
			$attribute_url = '?attribute_' . $term->taxonomy . '= ' . $term->slug . '';

			return get_the_permalink( $id ) . $attribute_url;
		}

		$product = wc_get_product( $id );

		if ( ! $product->is_type( 'variable' ) ) {
			return;
		}

		if ( empty( $this->available_variations[ $id ] ) ) {
			$this->available_variations[ $id ] = $product->get_available_variations();
		}

		$variations = ! empty( $this->available_variations[ $id ] ) ? $this->available_variations[ $id ] : [];

		if ( empty( $this->available_variations ) ) {
			return;
		}

		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'full' );
		$image     = [
			'src' => ! empty( $thumbnail[0] ) ? $thumbnail[0] : '',
			'width' => ! empty( $thumbnail[1] ) ? $thumbnail[1] : '',
			'height' => ! empty( $thumbnail[2] ) ? $thumbnail[2] : '',
		];

		foreach ( $variations as $variation ) {
			if ( in_array( $term->slug, $variation['attributes'], true ) ) {

				if ( ! empty( $variation['image_id'] ) ) {
					$variation_image = wp_get_attachment_image_src( $variation['image_id'], $size );

					$image = [
						'src' => $variation_image[0],
						'width' => $variation_image[1],
						'height' => $variation_image[2],
					];
				}
			}
		}

		return $image;
	}
}
new Artbees_WC_Attribute_Loop();
