<?php
/**
 * Add Product Stock Status Filter.
 *
 * @package JupiterX_Core\sellkit
 * @since 1.1.0
 */

namespace Sellkit_Pro\Elementor\Modules\Product_Filter\Filters;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;
use Elementor\Utils as ElementorUtils;

/**
 * Stock Status.
 *
 * Initializing the Stock Status by extending item base abstract class.
 *
 * @since 1.1.0
 */
class Stock_Status extends Filter_Base {

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
		return 'stock_status';
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
		return __( 'Stock Status', 'sellkit-pro' );
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
			'stock_status_content_heading' => [
				'name' => 'stock_status_content_heading',
				'label' => esc_html__( 'Content', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
					'stock_status_display' => [ 'checkbox', 'button' ],
				],
			],
			'stock_status_logic' => [
				'name' => 'stock_status_logic',
				'label' => esc_html__( 'Logic', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'and',
				'options' => [
					'and' => esc_html__( 'And', 'sellkit-pro' ),
					'or' => esc_html__( 'Or', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'stock_status_display' => [ 'checkbox', 'button' ],
				],
			],
			'stock_status_layout_heading' => [
				'name' => 'stock_status_layout_heading',
				'label' => esc_html__( 'Layout', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'stock_status_display' => [
				'name' => 'stock_status_display',
				'label' => esc_html__( 'Display as', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'checkbox',
				'options' => [
					'checkbox' => esc_html__( 'Checkbox List', 'sellkit-pro' ),
					'links' => esc_html__( 'Links', 'jsellkit' ),
					'button' => esc_html__( 'Button', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $filter_controls );
		$widget->update_control( 'filters', $control_data );
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

		$stock_status = [
			'instock' => __( 'In Stock', 'sellkit-pro' ),
			'outofstock' => __( 'Out of Stock', 'sellkit-pro' ),
			'onbackorder' => __( 'On Backorder', 'sellkit-pro' ),
		];

		foreach ( $stock_status as $key => $status ) {
			$terms[ $key ] = (object) [
				'taxonomy' => '_stock_status',
				'term_id' => $key,
				'slug' => $key,
				'name' => $status,
			];
		}

		$html = '<div class="sellkit-product-filter-item-wrapper sellkit-product-filter-stock-status">';

		$render_function = 'render_' . $field['stock_status_display'];

		$html .= $this->render_filter_heading( $heading );
		$html .= $this->$render_function( $terms, $field );

		$html .= '</div>';

		ElementorUtils::print_unescaped_internal_string( $html );
	}
}
