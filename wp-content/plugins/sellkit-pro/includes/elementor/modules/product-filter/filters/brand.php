<?php
/**
 * Add Product Brand Filter.
 *
 * @package JupiterX_Core\sellkit
 * @since 1.1.0
 */

namespace Sellkit_Pro\Elementor\Modules\Product_Filter\Filters;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;
use Elementor\Utils as ElementorUtils;

/**
 * Product Brand.
 *
 * Initializing the Product Brand by extending item base abstract class.
 *
 * @since 1.1.0
 */
class Brand extends Filter_Base {
	/**
	 * Keep rendered brands.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $brands;

	/**
	 * Get Filter type.
	 *
	 * Retrieve the Filter type.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Filter type.
	 */
	public function get_type() {
		return 'brand';
	}

	/**
	 * Get Filter Heading.
	 *
	 * Retrieve the Filter Heading.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Filter Heading.
	 */
	public function get_title() {
		return __( 'Brand', 'sellkit-pro' );
	}

	/**
	 * Update filter.
	 *
	 * Update filter settings.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param object $widget Widget instance.
	 */
	public function update_controls( $widget ) {
		$control_data = Elementor::$instance->controls_manager->get_control_from_stack(
			$widget->get_unique_name(),
			'filters'
		);

		if ( is_wp_error( $control_data ) ) {
			return;
		}

		$filter_controls = [
			'brand_content_heading' => [
				'name' => 'brand_layout_heading',
				'label' => esc_html__( 'Content', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_count' => [
				'name' => 'brand_count',
				'label' => esc_html__( 'Show Product Count', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_childern' => [
				'name' => 'brand_childern',
				'label' => esc_html__( 'Include children', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_sub_brands' => [
				'name' => 'brand_sub_brands',
				'label' => esc_html__( 'Show/hide sub-brands', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'Hide', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Show', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
					'brand_childern' => 'yes',
					'brand_display' => [
						'radio',
						'checkbox',
						'links',
					],
				],
			],
			'brand_logic' => [
				'name' => 'brand_logic',
				'label' => esc_html__( 'Logic', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'and',
				'options' => [
					'and' => esc_html__( 'And', 'sellkit-pro' ),
					'or' => esc_html__( 'Or', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'brand_display' => [ 'checkbox', 'button' ],
				],
			],
			'brand_orderby' => [
				'name' => 'brand_orderby',
				'label' => esc_html__( 'Order by', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'name',
				'options' => [
					'name' => esc_html__( 'Name', 'sellkit-pro' ),
					'count' => esc_html__( 'Count', 'sellkit-pro' ),
					'id' => esc_html__( 'ID', 'sellkit-pro' ),
					'custom' => esc_html__( 'Custom', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_order' => [
				'name' => 'brand_order',
				'label' => esc_html__( 'Order', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'asc',
				'options' => [
					'asc' => esc_html__( 'ASC', 'sellkit-pro' ),
					'desc' => esc_html__( 'DESC', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_empty' => [
				'name' => 'brand_empty',
				'label' => esc_html__( 'Show items without products', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_exclude' => [
				'name' => 'brand_exclude',
				'label' => esc_html__( 'Exclude', 'sellkit-pro' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => $this->get_taxonomy( 'brand' ),
				'label_block' => true,
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_layout_heading' => [
				'name' => 'brand_layout_heading',
				'label' => esc_html__( 'Layout', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_display' => [
				'name' => 'brand_display',
				'label' => esc_html__( 'Display as', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'radio',
				'options' => [
					'radio' => esc_html__( 'Radio List', 'sellkit-pro' ),
					'checkbox' => esc_html__( 'Checkbox List', 'sellkit-pro' ),
					'links' => esc_html__( 'Links', 'sellkit-pro' ),
					'dropdown' => esc_html__( 'Dropdown', 'sellkit-pro' ),
					'button' => esc_html__( 'Button', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'brand_disable_radio_shape' => [
				'name' => 'brand_disable_radio_shape',
				'label' => esc_html__( 'Disable Radio Shape', 'sellkit-pro' ),
				'description' => esc_html__( 'Enable this option to display a dropdown menu that allows users to select a single item from a list of links.', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => '',
				'condition' => [
					'filter_type' => $this->get_type(),
					'brand_display' => 'radio',
				],
			],
			'brand_layout' => [
				'name' => 'brand_layout',
				'label' => esc_html__( 'Layout', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sellkit-pro' ),
					'vertical' => esc_html__( 'Vertical', 'sellkit-pro' ),
					'columns' => esc_html__( 'Columns', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'brand_display!' => 'dropdown',
				],
			],
			'brand_cloumns' => [
				'name' => 'brand_cloumns',
				'label' => __( 'Coulmns', 'sellkit-pro' ),
				'type' => 'select',
				'default' => '2',
				'options' => [
					'2' => esc_html__( '2', 'sellkit-pro' ),
					'3' => esc_html__( '3', 'sellkit-pro' ),
					'4' => esc_html__( '4', 'sellkit-pro' ),
					'5' => esc_html__( '5', 'sellkit-pro' ),
					'6' => esc_html__( '6', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'brand_layout' => 'columns',
					'brand_display!' => 'dropdown',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $filter_controls );
		$widget->update_control( 'filters', $control_data );
	}

	/**
	 * Render brand terms.
	 *
	 * @param array $field Array of brand type controls.
	 * @since 1.6.7
	 * @access public
	 * @return array
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function render_terms( $field ) {
		if ( ! empty( $this->brands[ $field['_id'] ] ) ) {
			return $this->brands[ $field['_id'] ];
		}

		$args = [
			'taxonomy' => 'product_brand',
			'orderby'  => $field['brand_orderby'],
			'order'    => $field['brand_order'],
			'hide_empty' => false,
			'hierarchical' => false,
		];

		if ( 'yes' !== $field['brand_childern'] ) {
			$args['parent'] = 0;
		}

		$terms = get_terms( $args );

		$sorted_terms = $terms;

		if ( 'yes' === $field['brand_childern'] ) {
			$sorted_terms = [];

			foreach ( $terms as $key => $term ) {
				if ( 0 === $term->parent ) {
					$sorted_terms[] = $term;
				}

				if ( ! empty( get_term_children( $term->term_id, 'product_brand' ) ) ) {
					foreach ( get_term_children( $term->term_id, 'product_brand' ) as $child ) {
						$sorted_terms[] = get_term_by( 'id', $child, 'product_brand' );
					}
				}
			}
		}

		$this->brands[ $field['_id'] ] = $sorted_terms;

		return $this->brands[ $field['_id'] ];
	}

	/**
	 * Render filter content.
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function render_content() {
		$field   = $this->field;
		$heading = $this->get_title();

		$sorted_terms = $this->render_terms( $field );

		// Exclude brand or sub-brands.
		if ( ! empty( $field['brand_exclude'] ) ) {

			foreach ( $sorted_terms as $key => $term ) {
				if ( in_array( (string) $term->term_id, $field['brand_exclude'], true ) ) {

					unset( $sorted_terms[ $key ] );
				}
			}
		}

		// Exclude empty brand.
		if ( 'yes' !== $field['brand_empty'] ) {
			foreach ( $sorted_terms as $key => $term ) {
				if ( 0 === $term->count ) {
					unset( $sorted_terms[ $key ] );
				}
			}
		}

		$toggle = '';

		if (
			'yes' === $field['brand_sub_brands'] &&
			'vertical' === $field['brand_layout']
		) {
			$toggle = 'sellkit-brand-filter-toggle';
		}

		$html = '<div class="sellkit-product-filter-item-wrapper sellkit-product-filter-brand ' . esc_attr( $toggle ) . '">';

		$render_function = 'render_' . $field['brand_display'];

		$archive_data = [];

		if ( is_tax( 'product_brand' ) ) {
			global $wp_query;

			$brand_obj    = $wp_query->get_queried_object();
			$archive_data = $brand_obj;
		}

		$html .= $this->render_filter_heading( $heading );
		$html .= $this->$render_function( $sorted_terms, $field, $archive_data );

		$html .= '</div>';

		ElementorUtils::print_unescaped_internal_string( $html );
	}
}
