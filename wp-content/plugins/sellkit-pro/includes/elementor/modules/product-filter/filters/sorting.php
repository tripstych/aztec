<?php
/**
 * Add Product Sorting Filter.
 *
 * @package JupiterX_Core\sellkit
 * @since 1.6.0
 */

namespace Sellkit_Pro\Elementor\Modules\Product_Filter\Filters;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;
use Elementor\Utils as ElementorUtils;

/**
 * Product Sorting.
 *
 * Initializing the Product Sorting by extending item base abstract class.
 *
 * @since 1.6.0
 */
class Sorting extends Filter_Base {

	/**
	 * Get Filter type.
	 *
	 * Retrieve the Filter type.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @return string Filter type.
	 */
	public function get_type() {
		return 'sorting';
	}

	/**
	 * Get Filter Heading.
	 *
	 * Retrieve the Filter Heading.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @return string Filter Heading.
	 */
	public function get_title() {
		return esc_html__( 'Sorting', 'sellkit-pro' );
	}

	/**
	 * Update filter.
	 *
	 * Update filter settings.
	 *
	 * @since 1.6.0
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
			'sorting_layout_heading' => [
				'name' => 'sorting_layout_heading',
				'label' => esc_html__( 'Layout', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'sorting_display' => [
				'name' => 'sorting_display',
				'label' => esc_html__( 'Display as', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'radio',
				'options' => [
					'radio' => esc_html__( 'Radio List', 'sellkit-pro' ),
					'links' => esc_html__( 'Links', 'sellkit-pro' ),
					'dropdown' => esc_html__( 'Dropdown', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'sorting_disable_radio_shape' => [
				'name' => 'sorting_disable_radio_shape',
				'label' => esc_html__( 'Disable Radio Shape', 'sellkit-pro' ),
				'description' => esc_html__( 'Enable this option to display a dropdown menu that allows users to select a single item from a list of links.', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => '',
				'condition' => [
					'filter_type' => $this->get_type(),
					'sorting_display' => 'radio',
				],
			],
			'sorting_layout' => [
				'name' => 'sorting_layout',
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
					'sorting_display!' => 'dropdown',
				],
			],
			'sorting_cloumns' => [
				'name' => 'sorting_cloumns',
				'label' => esc_html__( 'Coulmns', 'sellkit-pro' ),
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
					'sorting_layout' => 'columns',
					'sorting_display!' => 'dropdown',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $filter_controls );
		$widget->update_control( 'filters', $control_data );
	}

	/**
	 * Render filter content.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function render_content() {
		$field   = $this->field;
		$heading = $this->get_title();

		$catalog_orderby_options = apply_filters(
			'woocommerce_catalog_orderby',
			[
				'menu_order' => esc_html__( 'Default sorting', 'sellkit-pro' ),
				'popularity' => esc_html__( 'Sort by popularity', 'sellkit-pro' ),
				'rating'     => esc_html__( 'Sort by average rating', 'sellkit-pro' ),
				'date'       => esc_html__( 'Sort by latest', 'sellkit-pro' ),
				'price'      => esc_html__( 'Sort by price: low to high', 'sellkit-pro' ),
				'price-desc' => esc_html__( 'Sort by price: high to low', 'sellkit-pro' ),
			],
		);

		$terms = [];

		foreach ( $catalog_orderby_options as $key => $option ) {
			$terms[ $key ] = (object) [
				'taxonomy' => 'order',
				'term_id' => $key,
				'slug' => $key,
				'name' => $option,
			];
		}

		$html = '<div class="sellkit-product-filter-item-wrapper sellkit-product-filter-sorting">';

		$render_function = 'render_' . $field['sorting_display'];

		$html .= $this->render_filter_heading( $heading );
		$html .= $this->$render_function( $terms, $field );

		$html .= '</div>';

		ElementorUtils::print_unescaped_internal_string( $html );
	}
}
