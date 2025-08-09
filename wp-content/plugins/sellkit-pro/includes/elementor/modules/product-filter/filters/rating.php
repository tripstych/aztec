<?php
/**
 * Add Rating Filter.
 *
 * @package JupiterX_Core\sellkit
 * @since 1.1.0
 */

namespace Sellkit_Pro\Elementor\Modules\Product_Filter\Filters;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;
use Elementor\Utils as ElementorUtils;

/**
 * Rating.
 *
 * Initializing the Rating by extending item base abstract class.
 *
 * @since 1.1.0
 */
class Rating extends Filter_Base {

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
		return 'rating';
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
		return __( 'Rating', 'sellkit-pro' );
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
			'rating_content_heading' => [
				'name' => 'rating_content_heading',
				'label' => esc_html__( 'Content', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'rating_count' => [
				'name' => 'rating_count',
				'label' => esc_html__( 'Show Product Count', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => '',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'rating_star' => [
				'name' => 'rating_star',
				'label' => __( 'Enable stars', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => __( 'No', 'sellkit-pro' ),
				'label_on' => __( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
					'rating_display' => 'checkbox',
				],
			],
			'enable_label' => [
				'name' => 'enable_label',
				'label' => esc_html__( 'Show Label', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
					'rating_display' => 'checkbox',
				],
			],
			'rating_logic' => [
				'name' => 'rating_logic',
				'label' => esc_html__( 'Logic', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'and',
				'options' => [
					'and' => esc_html__( 'And', 'sellkit-pro' ),
					'or' => esc_html__( 'Or', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'rating_display' => [ 'checkbox', 'button' ],
				],
			],
			'rating_logic' => [
				'name' => 'rating_logic',
				'label' => esc_html__( 'Logic', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'and',
				'options' => [
					'and' => esc_html__( 'And', 'sellkit-pro' ),
					'or' => esc_html__( 'Or', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'rating_display' => [ 'checkbox', 'button' ],
				],
			],
			'rating_layout_heading' => [
				'name' => 'rating_layout_heading',
				'label' => esc_html__( 'Layout', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'rating_display' => [
				'name' => 'rating_display',
				'label' => esc_html__( 'Display as', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'radio',
				'options' => [
					'radio' => esc_html__( 'Radio List', 'sellkit-pro' ),
					'checkbox' => esc_html__( 'Checkbox List', 'sellkit-pro' ),
					'links' => esc_html__( 'Links', 'jsellkit' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'rating_disable_radio_shape' => [
				'name' => 'rating_disable_radio_shape',
				'label' => esc_html__( 'Disable Radio Shape', 'sellkit-pro' ),
				'description' => esc_html__( 'Enable this option to display a dropdown menu that allows users to select a single item from a list of links.', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => '',
				'condition' => [
					'filter_type' => $this->get_type(),
					'rating_display' => 'radio',
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
		$terms   = [];

		$ratings = [
			5 => esc_html__( '5 only', 'sellkit-pro' ),
			4 => esc_html__( '4 and up', 'sellkit-pro' ),
			3 => esc_html__( '3 and up', 'sellkit-pro' ),
			2 => esc_html__( '2 and up', 'sellkit-pro' ),
			1 => esc_html__( '1 and up', 'sellkit-pro' ),
		];

		$html = sprintf(
			'<div class="sellkit-product-filter-item-wrapper sellkit-product-filter-rating %s">',
			'yes' === $field['rating_star'] ? 'sellkit-product-filter-rating-stars' : ''
		);

		foreach ( $ratings as $key => $rating ) {
			if ( ! empty( $field['rating_count'] ) ) {
				$args = [
					'post_type' => 'product',
					'meta_query' => [ // phpcs:ignore
						[
							'key'     => '_wc_average_rating',
							'value'   => $key,
							'compare' => '>=',
							'type'    => 'numeric',
						],
					],
				];

				$loop = new \WP_Query( $args );
			}

			$terms[ $rating ] = (object) [
				'taxonomy' => 'rating',
				'term_id' => $key,
				'slug' => $key,
				'name' => $rating,
				'rating_name' => ! empty( $field['enable_label'] ) ? 0 : 1,
				'rating_count' => ! empty( $field['rating_count'] ) ? $loop->found_posts : null,
			];
		}

		$render_function = 'render_' . $field['rating_display'];

		$html .= $this->render_filter_heading( $heading );
		$html .= $this->$render_function( $terms, $field );

		$html .= '</div>';

		ElementorUtils::print_unescaped_internal_string( $html );
	}
}
