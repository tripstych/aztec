<?php

use Sellkit_Pro\Elementor\Base\Sellkit_Elementor_Base_Module;
use Sellkit_Pro\Elementor\Sellkit_Elementor;

defined( 'ABSPATH' ) || die();

/**
 * Class Sellkit_Elementor_Product_Filter_Module
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Sellkit_Elementor_Product_Filter_Module extends Sellkit_Elementor_Base_Module {

	public static $filter_types = [];

	public $filtered_products = [];

	public $attribute = [];

	public function __construct() {
		parent::__construct();

		// Get data from filters and pass content by data.
		add_action( 'wp_ajax_sellkit_get_products', [ $this, 'get_products' ] );
		add_action( 'wp_ajax_nopriv_sellkit_get_products', [ $this, 'get_products' ] );

		$this->register_filter_types();
	}

	public static function is_active() {
		return function_exists( 'WC' );
	}

	public function get_widgets() {
		return [ 'product-filter' ];
	}

	public static function get_filter_types() {
		return [
			'category'         => esc_html__( 'Category', 'sellkit-pro' ),
			'tag'              => esc_html__( 'Tag', 'sellkit-pro' ),
			'brand'            => esc_html__( 'Brand', 'sellkit-pro' ),
			'price'            => esc_html__( 'Price', 'sellkit-pro' ),
			'rating'           => esc_html__( 'Rating', 'sellkit-pro' ),
			'search_text'      => esc_html__( 'Search Text', 'sellkit-pro' ),
			'stock_status'     => esc_html__( 'Stock Status', 'sellkit-pro' ),
			'on_sale'          => esc_html__( 'On Sale', 'sellkit-pro' ),
			'custom_attribute' => esc_html__( 'Custom Attribute', 'sellkit-pro' ),
			'sorting'          => esc_html__( 'Sorting', 'sellkit-pro' ),
		];
	}

	/**
	 * @SuppressWarnings(PHPMD.UnusedLocalVariable)
	 */
	private function register_filter_types() {
		foreach ( self::get_filter_types() as $field_key => $field_value ) {
			$class_name = 'Sellkit_Pro\Elementor\Modules\Product_Filter\Filters\\' . $field_key;

			self::$filter_types[ $field_key ] = new $class_name();
		}
	}

	public static function render_field( $widget, $filter ) {
		self::$filter_types[ $filter['filter_type'] ]->render( $widget, $filter );
	}

	/**
	 * Create array of filters.
	 *
	 * @since 1.1.0
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function get_products() {
		$nonce = sellkit_htmlspecialchars( INPUT_GET, 'nonce' );

		wp_verify_nonce( $nonce, 'sellkit_elementor' );

		$filters       = filter_input( INPUT_POST, 'filter' );
		$pagination    = filter_input( INPUT_POST, 'pagination' );
		$orderby       = filter_input( INPUT_POST, 'orderby' );
		$default_query = filter_input( INPUT_POST, 'defaultQuery' );
		$archive_data  = filter_input( INPUT_POST, 'archiveData', FILTER_DEFAULT, FILTER_FORCE_ARRAY );

		// Get Jupiterx Products widget settings.
		$jx_products_settings = filter_input( INPUT_POST, 'jxProductsSettings' );

		if ( empty( $filters ) ) {
			$this->ajax_empty_content();
		}

		$filter_data   = [];
		$result_filter = [];

		$filters = json_decode( $filters, true );
		$filters = $this->check_filters( $filters );

		foreach ( $filters as $key => $filter ) {
			$filter_logic = $filter[0];
			$filter_type  = $filter[1];

			foreach ( $filter as $value ) {
				if ( $value !== $filter_logic || empty( $value ) ) {
					if ( is_array( $value ) ) {
						$filter_data[ $key ][] = isset( $value[0] ) ? $value[0] : [];
					}
				}
			}

			$result_filter[ $key ]['logic'] = $filter_logic;
			$result_filter[ $key ]['type']  = $filter_type;
			$result_filter[ $key ]['data']  = $filter_data[ $key ];
		}

		$this->render_final_products(
			$result_filter,
			$pagination,
			$orderby,
			$jx_products_settings,
			$default_query,
			$filters,
			$archive_data
		);
	}

	/**
	 * Normalize database results.
	 *
	 * @since 1.1.0
	 */
	public function normalize_db_result( $results ) {
		$normal_result = [];
		foreach ( $results as $result ) {
			array_push( $normal_result, $result->ID );
		}

		return $normal_result;
	}

	/**
	 * Unset empty indexes from filter array
	 *
	 * @return array
	 */
	public function check_filters( $filters ) {
		foreach ( $filters as $key => $filter ) {
			if ( count( $filter ) < 3 ) {
				unset( $filters[ $key ] );
			}
		}

		return $filters;
	}

	/**
	 * Render final products
	 *
	 * @since 1.1.0
	 * @param array $results Filter ajax result.
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function render_final_products( $results, $pagination, $orderby, $jx_products_settings, $default_query, $filters, $archive_data ) {
		$arguments     = [];
		$content       = [];
		$has_sorting   = false;
		$attribute_key = '';

		$is_valid_archive_attribute = false;

		if ( ! empty( $archive_data ) ) {
			$attribute_key = array_key_first( $archive_data );
		}

		if ( ! empty( $attribute_key ) && strpos( $attribute_key, 'pa_' ) !== false ) {
			$is_valid_archive_attribute = true;
		}

		foreach ( $results as $key => $result ) {
			if ( 'search_text' === $results[ $key ]['type'] ) {
				$search_args = $this->generate_search_text_queries( $results[ $key ] );

				$arguments['post__in'][] = $search_args;
			}

			if ( 'category' === $results[ $key ]['type'] || ! empty( $archive_data['product_cat'] ) ) {
				$category_data = $results[ $key ];

				if ( ! empty( $archive_data['product_cat'] ) ) {
					$category_data = [
						'logic' => 'and',
						'type' => 'category',
						'data' => [
							0 => $archive_data['product_cat'],
						],
					];
				}

				$arguments['tax_query'][] = $this->generate_tax_query_arguments( $category_data, 'product_cat' );
			}

			if ( 'brand' === $results[ $key ]['type'] || ! empty( $archive_data['product_brand'] ) ) {
				$brand_data = $results[ $key ];

				if ( ! empty( $archive_data['product_brand'] ) ) {
					$brand_data = [
						'logic' => 'and',
						'type' => 'brand',
						'data' => [
							0 => $archive_data['product_brand'],
						],
					];
				}

				$arguments['tax_query'][] = $this->generate_tax_query_arguments( $brand_data, 'product_brand' );
			}

			if ( 'tag' === $results[ $key ]['type'] || ! empty( $archive_data['product_tag'] ) ) {
				$tag_data = $results[ $key ];

				if ( ! empty( $archive_data['product_tag'] ) ) {
					$tag_data = [
						'logic' => 'and',
						'type' => 'tag',
						'data' => [
							0 => $archive_data['product_tag'],
						],
					];
				}

				$arguments['tax_query'][] = $this->generate_tax_query_arguments( $tag_data, 'product_tag' );
			}

			if (
				strpos( $results[ $key ]['type'], 'attribute-' ) !== false ||
				$is_valid_archive_attribute
			) {
				$results[ $key ]['type'] = $this->normalize_attribute_data( $results[ $key ] );

				$attribute_data = $results[ $key ];

				if (
					! empty( $archive_data[ $attribute_key ] ) &&
					$is_valid_archive_attribute
				) {
					$attribute_data = [
						'logic' => 'and',
						'type' => $attribute_key,
						'data' => [
							0 => $archive_data[ $attribute_key ],
						],
					];
				}

				$this->handle_variation_image( $attribute_data );

				$arguments['tax_query'][] = $this->generate_tax_query_arguments( $attribute_data, $attribute_data['type'] );
			}

			if ( 'price' === $results[ $key ]['type'] ) {
				$arguments['meta_query'][] = $this->generate_meta_query_arguments( $results[ $key ], '_price' );
			}

			if ( 'rating' === $results[ $key ]['type'] ) {
				$arguments['meta_query'][] = $this->generate_meta_query_arguments( $results[ $key ], '_wc_average_rating' );
			}

			if ( 'yes' !== get_option( 'woocommerce_hide_out_of_stock_items' ) && 'stock_status' === $results[ $key ]['type'] ) {
				$arguments['meta_query'][] = $this->generate_meta_query_arguments( $results[ $key ], '_stock_status' );
			}

			if ( 'on_sale' === $results[ $key ]['type'] ) {
				$arguments['post__in'][] = wc_get_product_ids_on_sale();
			}

			if ( 'sorting' === $results[ $key ]['type'] ) {
				$has_sorting = true;

				if ( empty( $orderby ) ) {
					$orderby = empty( $results[ $key ]['data'][0] ) ? '' : $results[ $key ]['data'][0];
				}
			}
		}

		$args = [
			'post_type' => 'product',
		];

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$arguments['meta_query'][] = [
				'key' => '_stock_status',
				'value' => 'instock',
			];
		}

		if ( isset( $arguments['tax_query'] ) ) {
			$args['tax_query'] = $arguments['tax_query'];
		}

		if ( ! empty( $arguments['post__in'] ) ) {
			if ( count( $arguments['post__in'] ) > 1 ) {
				$arguments['post__in'][0] = array_intersect( $arguments['post__in'][0], $arguments['post__in'][1] );
			}

			$args['post__in'] = ! empty( $arguments['post__in'][0] ) ? $arguments['post__in'][0] : [];

			if ( empty( $args['post__in'] ) ) {
				$this->ajax_empty_content();
			}
		}

		if ( isset( $arguments['meta_query'] ) ) {
			$args['meta_query'] = $arguments['meta_query']; // phpcs:ignore
		}

		$this->filtered_products = $args;

		$loop = new WP_Query( $args );

		if ( empty( $pagination ) ) {
			$pagination = 1;
		}

		$this->attribute = array_merge( $this->attribute,
			[
				'paginate' => true,
			]
		);

		$posts_per_page  = 1;
		$pagination_type = '';

		if ( strpos( $pagination, 'jx-load-more-' ) !== false ) {
			$pagination_type = 'load_more';

			$count = (int) str_replace( 'jx-load-more-', '', $pagination );

			++$count;

			$posts_per_page = (int) $this->get_posts_per_page( $jx_products_settings ) * $count;
			$pagination     = 1;
		} else {
			$posts_per_page = (int) $this->get_posts_per_page( $jx_products_settings );
		}

		if ( ! empty( $orderby ) ) {
			$this->attribute['orderby'] = $orderby;
		}

		if ( ! empty( $default_query ) ) {
			$default_query = str_replace( '\u0000*\u0000', '', $default_query );
			$default_query = json_decode( $default_query, true );
		}

		if ( ! $has_sorting && ! empty( $default_query ) && ! empty( $default_query['attributes'] ) ) {
			$this->attribute['orderby'] = $default_query['attributes']['orderby'];
			$this->attribute['order']   = $default_query['attributes']['order'];
		}

		$this->filtered_products = array_merge( $this->filtered_products,
			[
				'posts_per_page' => $posts_per_page,
				'paged' => (int) $pagination,
			]
		);

		if ( $loop->have_posts() ) {
			// On reset, retrieve the default products.
			if ( ! empty( $default_query ) && empty( $filters ) ) {
				if ( empty( $default_query['query_args']['paged'] ) ) {
					$default_query['query_args']['paged'] = (int) $pagination;
				}

				if ( 'load_more' === $pagination_type ) {
					$default_query['query_args']['paged']          = (int) $pagination;
					$default_query['query_args']['posts_per_page'] = $posts_per_page;
				}

				$this->filtered_products = $default_query['query_args'];

				if ( ! empty( $orderby ) ) {
					$default_query['attributes']['orderby'] = $orderby;
				}

				$this->attribute = $default_query['attributes'];
			}

			add_filter( 'woocommerce_shortcode_products_query', function( $args ) {
				$args = array_merge( $args, $this->filtered_products );

				return $args;
			}, 10 );

			do_action( 'sellkit_product_filter_before_render_product' );

			$shortcode = new WC_Shortcode_Products( $this->attribute, 'products' );
			$content[] = $shortcode->get_content();

			do_action( 'sellkit_product_filter_after_render_product' );

			wp_send_json_success( $content );
		} else {
			$this->ajax_empty_content();
		}
	}

	/**
	 * Generate search query arguments.
	 *
	 * @since 1.1.0
	 * @param array $filter_data Filter data by key.
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function generate_search_text_queries( $filter_data ) {
		$data      = $filter_data['data'][0][0];
		$parameter = $filter_data['data'][0][1];

		global $wpdb;

		$prepared_query = [];

		if ( strpos( $parameter, 'categories' ) ) {
			$category = get_term_by( 'name', $data, 'product_cat' );

			$term_id = ! empty( $category ) ? $category->term_id : '';

			$prepared_query[] = $wpdb->prepare(
				"(taxonomy.term_id = %s AND taxonomy.taxonomy = 'product_cat')",
				$term_id
			);
		}

		if ( strpos( $parameter, 'tag' ) ) {
			$tag = get_term_by( 'name', $data, 'product_tag' );

			$term_id = ! empty( $tag ) ? $tag->term_id : '';

			$prepared_query[] = $wpdb->prepare(
				"(taxonomy.term_id = %s AND taxonomy.taxonomy = 'product_tag')",
				$term_id
			);
		}

		if ( strpos( $parameter, 'attributes' ) ) {
			$attributes      = wc_get_attribute_taxonomies();
			$attributes_list = [];
			$terms_list      = [];

			foreach ( $attributes as $attribute ) {
				$attribute_name = $attribute->attribute_name;

				if ( ! empty( get_term_by( 'name', $data, "pa_{$attribute_name}" ) ) ) {
					$terms_list[] = get_term_by( 'name', $data, "pa_{$attribute_name}" );
				}

				array_push( $attributes_list, $attribute_name );
			}

			foreach ( $terms_list as $term ) {
				$prepared_query[] = $wpdb->prepare(
					'(taxonomy.term_id = %d AND taxonomy.taxonomy = %s)',
					$term->term_id,
					$term->taxonomy
				);
			}

			if ( in_array( $data, $attributes_list, true ) ) {
				$terms = ! empty( get_terms( "pa_{$data}" ) ) ? get_terms( "pa_{$data}" ) : [];
				$terms = wp_list_pluck( $terms, 'term_id' );

				$prepared_query[] = $wpdb->prepare(
					'(taxonomy.term_id IN( %d ) AND taxonomy.taxonomy = %s)',
					implode( ',', $terms ),
					"pa_{$data}"
				);
			}
		}

		if ( strpos( $parameter, 'title' ) ) {
			$value = strtolower( $data );

			$prepared_query[] = $wpdb->prepare(
				"(LOWER( posts.post_title ) LIKE %s AND posts.post_type='product')",
				"%$value%"
			);
		}

		if ( strpos( $parameter, 'content' ) ) {
			$value = strtolower( $data );

			$prepared_query[] = $wpdb->prepare(
				"(LOWER( posts.post_content ) LIKE %s AND posts.post_type='product')",
				"%$value%"
			);
		}

		$where = implode( ' OR ', $prepared_query );

		// phpcs:disable
		$results = $wpdb->get_results(
			"SELECT DISTINCT posts.ID
			FROM {$wpdb->prefix}posts posts
			INNER JOIN {$wpdb->prefix}term_relationships relation ON posts.ID = relation.object_id
			INNER JOIN {$wpdb->prefix}term_taxonomy taxonomy ON relation.term_taxonomy_id = taxonomy.term_taxonomy_id
			INNER JOIN {$wpdb->prefix}terms terms ON terms.term_id = taxonomy.term_id
			WHERE $where",
			ARRAY_A
		);
		// phpcs:enable

		$results_ids = [];

		foreach ( $results as $result ) {
			$results_ids[] = $result['ID'];
		}

		if ( empty( $results_ids ) ) {
			$results_ids[] = 0;
		}

		return $results_ids;
	}

	/**
	 * Generate tax query arguments.
	 *
	 * @since 1.1.0
	 * @param array $filter_data Filter data by key.
	 * @param string $taxonomy Filter taxonomy name.
	 */
	public function generate_tax_query_arguments( $filter_data, $taxonomy ) {
		$tax_query = [];
		$queries   = [];
		$terms     = [];
		$logic     = $filter_data['logic'];

		$tax_query = [
			'relation' => empty( $logic ) ? 'AND' : strtoupper( $logic ),
		];

		if ( empty( $logic ) ) {
			$terms = $filter_data['data'];

			$queries = [
				'taxonomy' => $taxonomy,
				'field' => 'term_id',
				'terms' => $terms,
			];
		}

		if ( in_array( $logic, [ 'or', 'and' ], true ) ) {
			foreach ( $filter_data['data'] as $data ) {
				array_push( $queries, [
					'taxonomy' => $taxonomy,
					'field' => 'term_id',
					'terms' => $data,
				] );
			}

			foreach ( $queries as $query ) {
				array_push( $tax_query, $query );
			}

			return $tax_query;
		}

		$tax_query[] = $queries;

		return $tax_query;
	}

	/**
	 * Generate meta query arguments.
	 *
	 * @since 1.1.0
	 * @param array $filter_data Filter data by key.
	 * @param string $taxonomy Filter taxonomy name.
	 */
	public function generate_meta_query_arguments( $filter_data, $taxonomy ) {
		$meta_query = [];
		$queries    = [];
		$logic      = $filter_data['logic'];

		if ( '_price' === $taxonomy ) {
			$queries = $this->generate_price_query( $logic, $taxonomy, $filter_data );
		}

		if ( '_wc_average_rating' === $taxonomy ) {
			$queries = $this->generate_rating_query( $logic, $taxonomy, $filter_data );
		}

		if ( '_stock_status' === $taxonomy ) {
			$queries = $this->generate_stock_status_query( $logic, $taxonomy, $filter_data );
		}

		$meta_query = [
			'relation' => empty( $logic ) ? 'AND' : strtoupper( $logic ),
		];

		if ( in_array( $logic, [ 'or', 'and' ], true ) ) {
			foreach ( $queries as $query ) {
				array_push( $meta_query, $query );
			}

			return $meta_query;
		}

		$meta_query[] = $queries;

		return $meta_query;
	}

	/**
	 * Generate price meta query.
	 *
	 * @since 1.1.0
	 * @param string|null $logic Price filter logic.
	 * @param string $taxonomy Price filter taxonomy name.
	 * @param array $filter_data Price filter data.
	 */
	public function generate_price_query( $logic, $taxonomy, $filter_data ) {
		$query = [];

		if ( empty( $logic ) ) {
			$item = explode( '-', $filter_data['data'][0] );

			$price_range = [
				'min' => intval( $item[0] ),
				'max' => intval( $item[1] ),
			];

			$query = [
				[
					'key'     => $taxonomy,
					'value'   => $price_range['min'],
					'compare' => '>=',
					'type'    => 'numeric',
				],
				[
					'key'     => $taxonomy,
					'value'   => $price_range['max'],
					'compare' => '<=',
					'type'    => 'numeric',
				],
			];

			return $query;
		}

		if ( in_array( $logic, [ 'or', 'and' ], true ) ) {
			foreach ( $filter_data['data'] as $data ) {
				$item = explode( '-', $data );

				$price_range = [
					'min' => intval( $item[0] ),
					'max' => intval( $item[1] ),
				];

				array_push( $query, [
					[
						'key'     => $taxonomy,
						'value'   => $price_range['min'],
						'compare' => '>=',
						'type'    => 'numeric',
					],
					[
						'key'     => $taxonomy,
						'value'   => $price_range['max'],
						'compare' => '<=',
						'type'    => 'numeric',
					],
				] );
			}

			return $query;
		}
	}

	/**
	 * Generate rating meta query.
	 *
	 * @since 1.1.0
	 * @param string|null $logic Rating filter logic.
	 * @param string $taxonomy Rating filter taxonomy name.
	 * @param array $filter_data Rating filter data.
	 */
	public function generate_rating_query( $logic, $taxonomy, $filter_data ) {
		$query = [];

		if ( empty( $logic ) ) {
			array_push( $query, [
				'key'     => $taxonomy,
				'value'   => $filter_data['data'][0],
				'compare' => '>=',
				'type'    => 'numeric',
			] );

			return $query;
		}

		if ( in_array( $logic, [ 'or', 'and' ], true ) ) {
			foreach ( $filter_data['data'] as $data ) {
				array_push( $query, [
					'key'     => $taxonomy,
					'value'   => $data,
					'compare' => '>=',
					'type'    => 'numeric',
				] );
			}

			return $query;
		}
	}

	/**
	 * Generate rating meta query.
	 *
	 * @since 1.1.0
	 * @param string|null $logic Rating filter logic.
	 * @param string $taxonomy Rating filter taxonomy name.
	 * @param array $filter_data Rating filter data.
	 */
	public function generate_stock_status_query( $logic, $taxonomy, $filter_data ) {
		$query = [];

		if ( empty( $logic ) ) {
			$query = [
				'key' => $taxonomy,
				'value' => $filter_data['data'][0],
				'compare' => '=',
			];

			return $query;
		}

		if ( in_array( $logic, [ 'or', 'and' ], true ) ) {
			foreach ( $filter_data['data'] as $data ) {
				array_push( $query, [
					'key' => $taxonomy,
					'value' => $data,
				] );
			}

			return $query;
		}
	}

	/**
	 * Normalize attribute data.
	 *
	 * @since 1.1.0
	 * @param array $filter_data attribute data.
	 */
	public function normalize_attribute_data( $filter_data ) {
		$filter_id   = preg_replace( '/[^0-9]/', '', $filter_data['type'] );
		$attribute   = wc_get_attribute( $filter_id );
		$filter_name = $attribute->slug;

		return $filter_name;
	}

	/**
	 * Render ajax empty Content.
	 *
	 * @since 1.1.0
	 */
	public function ajax_empty_content() {
		$content = sprintf(
			'<div class="woocommerce"><h5 class="sellkit-not-found">%s</h5></div>',
			esc_html__( 'No Products Found...', 'sellkit-pro' )
		);

		wp_send_json_success( $content );
	}

	/**
	 * Get posts Per Page from customizer.
	 *
	 * @return void
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 */
	public function get_posts_per_page( $jx_products_settings ) {
		if ( ! empty( $jx_products_settings ) ) {
			$settings = json_decode( $jx_products_settings );

			$layout = 'grid';

			if ( isset( $settings[12] ) ) {
				$layout = $settings[12];
			}

			$grid_columns     = (int) $settings[0];
			$grid_rows        = (int) $settings[1];
			$products_number  = (int) isset( $settings[11] ) ? $settings[11] : 6;
			$pagination       = 'yes' === $settings[2] ? true : false;
			$pagination_type  = $settings[3];
			$all_products     = $settings[4];
			$product_settings = [
				'layout' => isset( $settings[5] ) ? $settings[5] : '',
				'swap_effect' => isset( $settings[6] ) ? $settings[6] : '',
				'image_size' => isset( $settings[7] ) ? $settings[7] : '',
				'pc_atc_button_location' => isset( $settings[8] ) ? $settings[8] : '',
				'pc_atc_button_icon' => isset( $settings[9] ) ? (array) $settings[9] : '',
				'wishlist' => isset( $settings[10] ) ? $settings[10] : '',
				'general_layout' => isset( $settings[12] ) ? $settings[12] : 'grid',
				'content_layout' => isset( $settings[13] ) ? $settings[13] : '',
				'atc_button' => isset( $settings[14] ) ? $settings[14] : '',
			];

			if ( ! empty( $product_settings ) && class_exists( 'JupiterX_Core' ) ) {
				apply_filters( 'jx_products_apply_image_size', $product_settings );
				apply_filters( 'jx_products_apply_swap_effects', $product_settings );
				apply_filters( 'jx_products_apply_button_location', $product_settings );
				apply_filters( 'jx_products_apply_button_icon', $product_settings );
				apply_filters( 'jx_products_apply_wishlist', $product_settings );
			}

			if ( 'page_based' === $pagination_type ) {
				remove_action( 'woocommerce_after_shop_loop', 'jupiterx_add_load_more', 30 );
				remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
				add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
			}

			if ( 'load_more' === $pagination_type ) {
				remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
				add_action( 'woocommerce_after_shop_loop', 'jupiterx_add_load_more', 30 );
			}

			if ( 'infinite_load' === $pagination_type ) {
				remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
				remove_action( 'woocommerce_after_shop_loop', 'jupiterx_add_load_more', 30 );
				add_action( 'woocommerce_after_shop_loop', [ $this, 'get_infinite_load_indicator' ], 30 );
			}

			$this->attribute = [
				'columns' => $grid_columns,
				'paginate' => $pagination,
			];

			$posts_per_page = ! in_array( $layout, [ 'matrix', 'metro' ], true ) ? $grid_columns * $grid_rows : $products_number;

			if ( ! empty( $all_products ) ) {
				$posts_per_page = -1;
			}

			return $posts_per_page;
		}

		$grid_columns = intval( get_theme_mod( 'products_per_page', 3 ) );
		$grid_rows    = intval( get_theme_mod( 'products_per_row', 3 ) );

		$current_theme = wp_get_theme();

		if ( 'JupiterX' === $current_theme->name ) {
			$grid_columns = intval( get_theme_mod( 'jupiterx_product_list_grid_columns', 3 ) );
			$grid_rows    = intval( get_theme_mod( 'jupiterx_product_list_grid_rows', 3 ) );
		}

		$posts_per_page = $grid_columns * $grid_rows;

		return $posts_per_page;
	}

	/**
	 * Get infinite load indicator.
	 *
	 * @since 1.9.0
	 */
	public function get_infinite_load_indicator() {
		echo wp_kses_post( '<span class="raven-products-preloader"></span><span class="raven-infinite-load"></span>' );
	}

	/**
	 * Set variation image for selected attribute.
	 *
	 * @since 1.6.7
	 */
	public function handle_variation_image( $result ) {
		add_filter( 'woocommerce_product_get_image', function( $product_image, $product ) use ( $result ) {
			$is_enabled = apply_filters( 'sellkit_product_filter_variation_image', true );

			if ( ! $is_enabled ) {
				return $product_image;
			}

			$product_attributes = $product->get_attributes();

			if ( empty( $product_attributes ) || is_singular( 'product' ) || ! $product->is_type( 'variable' ) ) {
				return $product_image;
			}

			$product_attributes = $product->get_available_variations();

			foreach ( $product_attributes as $attribute ) {
				$attribute_name = 'attribute_' . $result['type'];

				if ( ! array_key_exists( $attribute_name, $attribute['attributes'] ) ) {
					continue;
				}

				foreach ( $result['data'] as $data ) {
					$term_data = get_term( $data, $result['type'] );

					if ( $term_data->slug === $attribute['attributes'][ $attribute_name ] ) {
						add_filter( 'sellkit_product_filter_selected_attribute', function() use ( $term_data ) {
							return $term_data->slug;
						} );

						return wp_get_attachment_image( $attribute['image_id'], 'full' );
					}
				}
			}

			return $product_image;
		}, 10, 2 );
	}

	/**
	 * Get the archive data.
	 *
	 * @since 1.6.7
	 */
	public static function get_archive_data() {
		$archive_data = [];

		if ( is_product_category() ) {
			global $wp_query;

			$category_obj                            = $wp_query->get_queried_object();
			$archive_data[ $category_obj->taxonomy ] = $category_obj->term_id;
		}

		if ( is_product_tag() ) {
			global $wp_query;

			$tag_obj                            = $wp_query->get_queried_object();
			$archive_data[ $tag_obj->taxonomy ] = $tag_obj->term_id;
		}

		if ( ! empty( Sellkit_Elementor::is_attribute_archive() ) ) {
			$attribute_obj = Sellkit_Elementor::is_attribute_archive();

			$archive_data[ $attribute_obj->taxonomy ] = $attribute_obj->term_id;
		}

		return $archive_data;
	}
}
