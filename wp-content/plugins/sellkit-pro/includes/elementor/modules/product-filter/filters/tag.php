<?php
/**
 * Add Product Tag Filter.
 *
 * @package JupiterX_Core\sellkit
 * @since 1.1.0
 */

namespace Sellkit_Pro\Elementor\Modules\Product_Filter\Filters;

defined( 'ABSPATH' ) || die();

use Elementor\Plugin as Elementor;
use Elementor\Utils as ElementorUtils;

/**
 * Tag.
 *
 * Initializing the Tagby extending item base abstract class.
 *
 * @since 1.1.0
 */
class Tag extends Filter_Base {
	/**
	 * Keep rendered tags.
	 *
	 * @access public
	 *
	 * @var array
	 */
	public $tags;

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
		return 'tag';
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
		return __( 'Tags', 'sellkit-pro' );
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
			'tag_content_heading' => [
				'name' => 'tag_content_heading',
				'label' => esc_html__( 'Content', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'tag_count' => [
				'name' => 'tag_count',
				'label' => esc_html__( 'Show Product Count', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'tag_logic' => [
				'name' => 'tag_logic',
				'label' => esc_html__( 'Logic', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'and',
				'options' => [
					'and' => esc_html__( 'And', 'sellkit-pro' ),
					'or' => esc_html__( 'Or', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'tag_display' => [ 'checkbox', 'button' ],
				],
			],
			'tag_orderby' => [
				'name' => 'tag_orderby',
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
			'tag_order' => [
				'name' => 'tag_order',
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
			'tag_empty' => [
				'name' => 'tag_empty',
				'label' => esc_html__( 'Show items without products', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'tag_exclude' => [
				'name' => 'tag_exclude',
				'label' => esc_html__( 'Exclude', 'sellkit-pro' ),
				'type' => 'select2',
				'multiple' => true,
				'options' => $this->get_taxonomy( 'tag' ),
				'label_block' => true,
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'tag_layout_heading' => [
				'name' => 'tag_layout_heading',
				'label' => esc_html__( 'Layout', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'tag_display' => [
				'name' => 'tag_display',
				'label' => esc_html__( 'Display as', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'radio',
				'options' => [
					'radio' => esc_html__( 'Radio List', 'sellkit-pro' ),
					'checkbox' => esc_html__( 'Checkbox List', 'sellkit-pro' ),
					'links' => esc_html__( 'Links', 'jsellkit' ),
					'dropdown' => esc_html__( 'Dropdown', 'sellkit-pro' ),
					'button' => esc_html__( 'Button', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
				],
			],
			'tag_disable_radio_shape' => [
				'name' => 'tag_disable_radio_shape',
				'label' => esc_html__( 'Disable Radio Shape', 'sellkit-pro' ),
				'description' => esc_html__( 'Enable this option to display a dropdown menu that allows users to select a single item from a list of links.', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => '',
				'condition' => [
					'filter_type' => $this->get_type(),
					'tag_display' => 'radio',
				],
			],
			'tag_layout' => [
				'name' => 'tag_layout',
				'label' => __( 'Layout', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sellkit-pro' ),
					'vertical' => esc_html__( 'Vertical', 'sellkit-pro' ),
					'columns' => esc_html__( 'Columns', 'sellkit-pro' ),
				],
				'condition' => [
					'filter_type' => $this->get_type(),
					'tag_display!' => 'dropdown',
				],
			],
			'tag_cloumns' => [
				'name' => 'tag_cloumns',
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
					'tag_layout' => 'columns',
					'tag_display!' => 'dropdown',
				],
			],
		];

		$control_data['fields'] = $this->inject_field_controls( $control_data['fields'], $filter_controls );
		$widget->update_control( 'filters', $control_data );
	}

	/**
	 * Render category terms.
	 *
	 * @param array $field Array of category type controls.
	 * @since 1.6.7
	 * @access public
	 * @return array
	 */
	public function render_terms( $field ) {
		if ( ! empty( $this->tags[ $field['_id'] ] ) ) {
			return $this->tags[ $field['_id'] ];
		}

		$args = [
			'taxonomy' => 'product_tag',
			'orderby'  => $field['tag_orderby'],
			'order'    => $field['tag_order'],
			'hide_empty' => true,
		];

		if ( 'yes' === $field['tag_empty'] ) {
			$args['hide_empty'] = false;
		}

		if ( ! empty( $field['tag_exclude'] ) ) {
			$args['exclude'] = implode( ',', $field['tag_exclude'] );
		}

		$this->tags[ $field['_id'] ] = get_terms( $args );

		return $this->tags[ $field['_id'] ];
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

		$terms = $this->render_terms( $field );

		$html = '<div class="sellkit-product-filter-item-wrapper sellkit-product-filter-tag">';

		$render_function = 'render_' . $field['tag_display'];

		$archive_data = [];

		if ( is_product_tag() ) {
			global $wp_query;

			$tag_obj      = $wp_query->get_queried_object();
			$archive_data = $tag_obj;
		}

		$html .= $this->render_filter_heading( $heading );
		$html .= $this->$render_function( $terms, $field, $archive_data );

		$html .= '</div>';

		ElementorUtils::print_unescaped_internal_string( $html );
	}
}
