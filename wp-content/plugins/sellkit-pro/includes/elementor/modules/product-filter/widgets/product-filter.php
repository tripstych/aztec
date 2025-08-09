<?php

use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Plugin as Elementor;

defined( 'ABSPATH' ) || die();
/**
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Sellkit_Elementor_Product_Filter_Widget extends Sellkit_Elementor_Base_Widget {

	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public function get_name() {
		return 'sellkit-product-filter';
	}

	public function get_title() {
		return esc_html__( 'Product Filter', 'sellkit-pro' );
	}

	public function get_icon() {
		return 'sellkit-element-icon sellkit-product-filter-icon';
	}

	public function get_style_depends() {
		return [ 'elementor-icons' ];
	}

	protected function register_controls() {
		$this->register_content_section_controls();
		$this->register_layout_section_controls();
		$this->register_style_widget_controls();
		$this->register_style_filter_group_controls();
		$this->register_style_checkbox_controls();
		$this->register_style_radio_controls();
		$this->register_style_button_controls();
		$this->register_style_link_controls();
		$this->register_style_dropdown_controls();
		$this->register_style_search_controls();
		$this->register_style_swatches();
		$this->register_style_image_swatches();
		$this->register_style_switch();
		$this->register_style_product_count();
		$this->register_style_applied_filter();
		$this->register_style_not_found();
	}

	private function register_content_section_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Content', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'content_style',
			[
				'label' => esc_html__( 'Style', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'horizontal',
				'frontend_available' => true,
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'sellkit-pro' ),
					'vertical' => esc_html__( 'Vertical', 'sellkit-pro' ),
				],
			]
		);

		$this->add_control(
			'dropdown_animation',
			[
				'label'        => esc_html__( 'Dropdown Animation', 'sellkit-pro' ),
				'type'         => 'select',
				'prefix_class' => 'product-filter-animation-',
				'default'      => 'none',
				'options'      => [
					'none'        => esc_html__( 'None', 'sellkit-pro' ),
					'fade'        => esc_html__( 'Fade', 'sellkit-pro' ),
					'slide_down'  => esc_html__( 'Slide Down', 'sellkit-pro' ),
					'slide_up'    => esc_html__( 'Slide Up', 'sellkit-pro' ),
					'slide_left'  => esc_html__( 'Slide Left', 'sellkit-pro' ),
					'slide_right' => esc_html__( 'Slide Right', 'sellkit-pro' ),
					'scale_down'  => esc_html__( 'Scale Down', 'sellkit-pro' ),
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'dropdown_trigger',
			[
				'label'              => esc_html__( 'Show Dropdown On', 'sellkit-pro' ),
				'type'               => 'select',
				'frontend_available' => true,
				'default'            => 'click',
				'options'            => [
					'hover'      => esc_html__( 'Hover', 'sellkit-pro' ),
					'click' => esc_html__( 'Click', 'sellkit-pro' ),
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'applied_filter_heading',
			[
				'label' => esc_html__( 'Applied Filters', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_location',
			[
				'label' => __( 'Location', 'sellkit-pro' ),
				'type' => 'select',
				'default' => 'topOfProductList',
				'frontend_available' => true,
				'options' => [
					'topOfProductList' => esc_html__( 'Top of product list', 'sellkit-pro' ),
					'topOfFilters' => esc_html__( 'Top of filters', 'sellkit-pro' ),
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_control(
			'reset_text',
			[
				'label' => esc_html__( 'Reset Text', 'sellkit-pro' ),
				'type' => 'text',
				'placeholder' => esc_html__( 'Enter your text...', 'sellkit-pro' ),
				'default' => esc_html__( 'Clear All', 'sellkit-pro' ),
				'label_block' => true,
				'frontend_available' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'scroll_to_top',
			[
				'label' => esc_html__( 'Scroll To Top', 'sellkit-pro' ),
				'description' => esc_html__( 'Scroll to top after apply filter.', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'no',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'allow_toggle_able',
			[
				'label' => esc_html__( 'Allow field groups toggle-able', 'sellkit-pro' ),
				'type' => 'switcher',
				'label_off' => esc_html__( 'No', 'sellkit-pro' ),
				'label_on' => esc_html__( 'Yes', 'sellkit-pro' ),
				'default' => 'yes',
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_layout_section_controls() {
		$this->start_controls_section(
			'filter_layout',
			[
				'label' => esc_html__( 'Layout', 'sellkit-pro' ),
				'tab' => 'layout',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'filter_type',
			[
				'label' => esc_html__( 'Type', 'sellkit-pro' ),
				'type' => 'select',
				'options' => Sellkit_Elementor_Product_Filter_Module::get_filter_types(),
				'default' => 'category',
			]
		);

		$repeater->add_responsive_control(
			'dropdown_alignment',
			[
				'label'  => esc_html__( 'Alignment', 'sellkit-pro' ),
				'type' => 'choose',
				'description' => esc_html__( 'This option will apply in horizontal style only.', 'sellkit-pro' ),
				'default' => '',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto !important;',
					'center' => 'margin-right: auto !important;margin-left: auto !important;',
					'right' => 'margin-left: auto !important;',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.sellkit-product-filter-repeater-item' => '{{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'label_icon',
			[
				'label' => esc_html__( 'Label Icon', 'sellkit-pro' ),
				'type' => 'icons',
				'label_block' => false,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-chevron-down',
					'library' => 'solid',
				],
				'description' => esc_html__( 'This option will apply in horizontal style only.', 'sellkit-pro' ),
			]
		);

		$repeater->add_responsive_control(
			'dropdown_width',
			[
				'label' => esc_html__( 'Content Field Width', 'sellkit-pro' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal {{CURRENT_ITEM}} .sellkit-product-filter-item-wrapper' => 'width: {{SIZE}}{{UNIT}} !important;max-width: {{SIZE}}{{UNIT}} !important;min-width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$repeater->add_control(
			'dropdown_width_note',
			[
				'type' => 'raw_html',
				'raw' => esc_html__( 'This option will override this filter group dropdown in horizontal style only.', 'sellkit-pro' ),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$this->add_control(
			'filters',
			[
				'type' => 'repeater',
				'fields' => $repeater->get_controls(),
				'frontend_available' => true,
				'default' => [
					[
						'filter_type' => 'category',
						'category_display' => 'button',
					],
					[
						'filter_type' => 'price',
						'price_display' => 'links',
					],
					[
						'filter_type' => 'rating',
						'rating_display' => 'checkbox',
					],
					[
						'filter_type' => 'search_text',
					],
				],
				'frontend_available' => true,
				'title_field' => '{{{ filter_type.replace( /_/g, " " ) }}}',
			]
		);

		$this->end_controls_section();
	}

	private function register_style_widget_controls() {
		$this->start_controls_section(
			'section_style_widget',
			[
				'label' => esc_html__( 'Widget', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'widgt_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'widget_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter',
			]
		);

		$this->add_responsive_control(
			'widget_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'widget_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'widget_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	private function register_style_filter_group_controls() {
		$this->start_controls_section(
			'section_style_filter_group',
			[
				'label' => esc_html__( 'Filter Group', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		// Horizental Controls
		$this->add_control(
			'filter_group_button_heading',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Group Button', 'sellkit-pro' ),
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_button_min_width',
			[
				'label' => esc_html__( 'Min Width', 'sellkit-pro' ),
				'type' => 'slider',
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_button_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_button_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter  .sellkit-product-filter-form-horizontal .sellkit-product-filter-repeater-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'filter_group_button_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector',
				'scheme' => '3',
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_button_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'filter_group_button_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector',
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->start_controls_tabs( 'filter_group_button_tabs' );

		$this->start_controls_tab(
			'filter_group_button_normal',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_button_color',
			[
				'label' => esc_html__( 'Text Color', 'sellkit-pro' ),
				'type' => 'color',
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector' => 'color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_button_icon_color',
			[
				'label' => __( 'Icon Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector .elementor-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .product-filter-selector .elementor-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_group_button_hover',
			[
				'label' => esc_html__( 'Hover', 'sellkit-pro' ),
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_button_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'sellkit-pro' ),
				'type' => 'color',
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-repeater-item:hover .product-filter-selector' => 'color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-repeater-item:hover .product-filter-selector' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_group_button_icon_color_hover',
			[
				'label' => esc_html__( 'Icon Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-repeater-item:hover .product-filter-selector .elementor-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-repeater-item:hover .product-filter-selector .elementor-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_group_button_active',
			[
				'label' => esc_html__( 'Active', 'sellkit-pro' ),
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_button_color_active',
			[
				'label' => esc_html__( 'Text Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-has-data .product-filter-selector' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-force-active .product-filter-selector' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-price-range-has-data .product-filter-selector' => 'color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_button_background_color_active',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-has-data .product-filter-selector' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-force-active .product-filter-selector' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-price-range-has-data .product-filter-selector' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'filter_group_button_icon_color_active',
			[
				'label' => esc_html__( 'Icon Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-has-data .product-filter-selector .elementor-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-has-data .product-filter-selector .elementor-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-force-active .product-filter-selector .elementor-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-filter-force-active .product-filter-selector .elementor-icon svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-price-range-has-data .product-filter-selector .elementor-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-price-range-has-data .product-filter-selector .elementor-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'filter_group_content_heading',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Group Content Field', 'sellkit-pro' ),
				'separator' => 'before',
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_content_width',
			[
				'label' => esc_html__( 'Width', 'sellkit-pro' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%', 'rem', 'em', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-item-wrapper' => 'width: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_content_min_height',
			[
				'label' => esc_html__( 'Min Height', 'sellkit-pro' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%', 'rem', 'em', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-item-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_content_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-item-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-item-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_content_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-item-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-item-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'filter_group_content_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-item-wrapper',
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'filter_group_content_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-item-wrapper' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-horizontal .sellkit-product-filter-item-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'horizontal',
				],
			]
		);

		// Vertical Controls
		$this->add_control(
			'filter_group_vertical_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'filter_group_border_vertical',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content',
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_heading',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Heading', 'sellkit-pro' ),
				'separator' => 'before',
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_divider',
			[
				'type' => 'divider',
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'filter_group_vertical_heading_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-form-vertical .sellkit-product-filter-content h3.product-filter-item-heading',
				'scheme' => '1',
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'filter_group_vertical_heading_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content h3.product-filter-item-heading',
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_heading_color',
			[
				'label' => esc_html__( 'Text Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content h3.product-filter-item-heading' => 'color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_heading_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content h3.product-filter-item-heading' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content h3.product-filter-item-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_heading_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-vertical .sellkit-product-filter-content h3.product-filter-item-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'content_style' => 'vertical',
				],
			]
		);

		// Toggle-able icon.
		$this->add_control(
			'filter_group_vertical_icon_heading',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Toggle-able Icon', 'sellkit-pro' ),
				'separator' => 'before',
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-toggle-able-heading .sellkit-toggle-able i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-toggle-able-heading .sellkit-toggle-able svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_icon_vertical_offset',
			[
				'label' => esc_html__( 'Vertical Offset', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-toggle-able-heading .sellkit-toggle-able' => 'top: {{SIZE}}{{UNIT}};position: absolute;',
				],
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_icon_horizental_offset',
			[
				'label' => esc_html__( 'Horizontal Offset', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-toggle-able-heading .sellkit-toggle-able' => 'right: {{SIZE}}{{UNIT}};position: absolute;',
				],
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'filter_group_vertical_icon_align',
			[
				'label'  => esc_html__( 'Heading Align', 'sellkit-pro' ),
				'type' => 'choose',
				'default' => '',
				'prefix_class' => 'sllkit%s-product-button-icon-align-',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-toggle-able-heading' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'filter_group_vertical_icon_tabs' );

		$this->start_controls_tab(
			'filter_group_vertical_icon_collapsed_tab',
			[
				'label' => esc_html__( 'Collapsed', 'sellkit-pro' ),
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_icon_collapsed',
			[
				'label' => esc_html__( 'Icon', 'sellkit-pro' ),
				'type' => 'icons',
				'fa4compatibility' => 'submit_button_icon',
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
				'default' => [
					'value' => 'fa fa-chevron-down',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_icon_collapsed_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-toggle-able .sellkit-toggle-able-collapsed' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-toggle-able .sellkit-toggle-able-collapsed' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_group_vertical_icon_expanded_tab',
			[
				'label' => esc_html__( 'Expanded', 'sellkit-pro' ),
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_icon_expanded',
			[
				'label' => esc_html__( 'Icon', 'sellkit-pro' ),
				'type' => 'icons',
				'fa4compatibility' => 'submit_button_icon',
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
				'default' => [
					'value' => 'fa fa-chevron-up',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'filter_group_vertical_icon_expanded_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-toggle-able .sellkit-toggle-able-expanded' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-toggle-able .sellkit-toggle-able-expanded' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'content_style' => 'vertical',
					'allow_toggle_able' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_style_checkbox_controls() {
		$this->start_controls_section(
			'section_style_checkbox',
			[
				'label' => esc_html__( 'Checkbox List', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'checkbox_size',
			[
				'label' => esc_html__( 'Size', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input:checked + label::before' => 'height: {{SIZE}}{{UNIT}} !important;width: {{SIZE}}{{UNIT}} !important;left: calc({{SIZE}}{{UNIT}} * -1);',
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input + label' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'checkbox_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'checkbox_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'checkbox_space_between',
			[
				'label' => esc_html__( 'Space Between', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'checkbox_label_spacing',
			[
				'label' => esc_html__( 'Label Spacing', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox-wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'checkbox_space',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'checkbox_tabs' );

		$this->start_controls_tab(
			'checkbox_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'checkbox_background_color_normal',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'checkbox_border_normal',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'checkbox_box_shadow_normal',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'checkbox_checked_tab',
			[
				'label' => esc_html__( 'Checked', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'checkbox_background_color_checked',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input:checked + label::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkbox_icon_color_checked',
			[
				'label' => esc_html__( 'Icon Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input:checked + label::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'checkbox_border_checked',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input:checked + label::before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'checkbox_box_shadow_checked',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input:checked + label::before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'checkbox_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-checkbox input:checked + label::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	private function register_style_radio_controls() {
		$this->start_controls_section(
			'section_style_radio',
			[
				'label' => esc_html__( 'Radio List', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'radio_size',
			[
				'label' => esc_html__( 'Size', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input:checked + label::before' => 'width:calc( {{SIZE}}{{UNIT}} - 8px ) !important;height:calc( {{SIZE}}{{UNIT}} - 8px ) !important;left: calc(( {{SIZE}}{{UNIT}} - 4px) * -1) !important;',
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input + label' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'radio_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'radio_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio label',
				'scheme' => '3',
			]
		);

		$this->add_responsive_control(
			'radio_space_between',
			[
				'label' => esc_html__( 'Space Between', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'radio_label_spacing',
			[
				'label' => esc_html__( 'Label Spacing', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio-wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'radio_space',
			[
				'label' => esc_html__( 'Space', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'radio_tabs' );

		$this->start_controls_tab(
			'radio_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'radio_background_color_normal',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'radio_border_normal',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'radio_box_shadow_normal',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'radio_checked_tab',
			[
				'label' => esc_html__( 'Checked', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'radio_background_color_checked',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input:checked + label::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'radio_border_checked',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input:checked + label::before',
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'radio_box_shadow_checked',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-radio input:checked + label::before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_style_button_controls() {
		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button List', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label'  => esc_html__( 'Alignment', 'sellkit-pro' ),
				'type' => 'choose',
				'default' => '',
				'prefix_class' => 'sellkit-product-filter-button-align-%s',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'button_normal_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_normal_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item span',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'button_normal_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item:hover span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item.active-button span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_hover_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item:hover span',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'button_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_active_tab',
			[
				'label' => esc_html__( 'Active', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'button_active_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item.active-button span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'button_active_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item.active-button span',
			]
		);

		$this->add_group_control(
			'background',
			[
				'name' => 'button_active_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item.active-button',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_heading',
			[
				'type' => 'heading',
				'label' => esc_html__( 'Border', 'sellkit-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .sellkit-product-filter-button .product-filter-item',
			]
		);

		$this->end_controls_section();
	}

	private function register_style_link_controls() {
		$this->start_controls_section(
			'section_style_link',
			[
				'label' => esc_html__( 'Link List', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'link_spacing',
			[
				'label' => esc_html__( 'Item Spacing', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-links' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'link_tabs' );

		$this->start_controls_tab(
			'link_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'link_normal_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .product-filter-item-links',
			]
		);

		$this->add_control(
			'link_normal_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-links' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-links:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'link_hover_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .product-filter-item-links:hover',
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-links:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-links:hover:before' => 'background-color: {{VALUE}};',

				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_selected_tab',
			[
				'label' => esc_html__( 'Selected', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'link_selected_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .sellkit-product-filter .product-filter-item-links.active-link',
			]
		);

		$this->add_control(
			'link_selected_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-links.active-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-links.active-link:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_style_dropdown_controls() {
		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => esc_html__( 'Dropdown', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->start_controls_tabs( 'dropdown_tabs' );

		$this->start_controls_tab(
			'dropdown_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'dropdown_normal_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_normal_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'dropdown_normal_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select',
			]
		);

		$this->add_responsive_control(
			'dropdown_normal_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper .product-filter-item-select-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'dropdown_normal_box_shadow',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdown_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'dropdown_focus_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dropdown_focus_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-dropdown select:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'dropdown_focus_border',
				'selector' => '{{WRAPPER}}  .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select:focus',
			]
		);

		$this->add_responsive_control(
			'dropdown_focus_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}  .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'dropdown_focus_box_shadow',
				'selector' => '{{WRAPPER}}  .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			'typography',
			[
				'name' => 'dropdown_normal_typography',
				'scheme' => '3',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-dropdown .sellkit-product-filter-dropdown-wrapper select',
			]
		);

		$this->add_control(
			'dropdown_icon_new',
			[
				'label' => esc_html__( 'Icon', 'sellkit-pro' ),
				'type' => 'icons',
				'fa4compatibility' => 'submit_button_icon',
				'separator' => 'before',
				'default' => [
					'value' => 'fa fa-chevron-down',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'dropdown_icon_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label svg path' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_icon_size',
			[
				'label' => esc_html__( 'Size', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_icon_vertical_offset',
			[
				'label' => esc_html__( 'Vetical Offset', 'sellkit-pro' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%', 'vm' ],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label i' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label svg' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_icon_horizental_offset',
			[
				'label' => esc_html__( 'Horizontal Offset', 'sellkit-pro' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%', 'vm' ],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label i' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label svg' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dropdown_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter .product-filter-item-select-label select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_search_controls() {
		$this->start_controls_section(
			'section_style_search',
			[
				'label' => esc_html__( 'Search Forms', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_control(
			'search_form_heading',
			[
				'label' => esc_html__( 'Form', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_form_background_color',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-search-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'search_form_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-search-wrapper',
			]
		);

		$this->add_responsive_control(
			'search_form_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-search-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'box-shadow',
			[
				'name' => 'search_form_box_shadow',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-search-wrapper',
			]
		);

		$this->add_responsive_control(
			'search_form_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-search-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'search_form_input_heading',
			[
				'label' => esc_html__( 'Inputs', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_form_input_input_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'search_form_input_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-type input',
				'scheme' => '3',
			]
		);

		$this->add_control(
			'search_form_placeholder_heading',
			[
				'label' => esc_html__( 'Placeholder', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'search_form_input_placeholder_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'search_form_input_placeholder_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-type input::placeholder',
				'scheme' => '3',
				'exclude' => [
					'line_height',
				],
			]
		);

		$this->add_control(
			'search_form_button_heading',
			[
				'label' => esc_html__( 'Submit Buttons', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'search_form_buttons_tabs' );

		$this->start_controls_tab(
			'search_form_buttons_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'search_form_button_color_normal',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_form_button_background_color_normal',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'search_form_button_size_normal',
			[
				'label' => esc_html__( 'Button Size', 'sellkit-pro' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button' => 'font-size: {{SIZE}}{{UNIT}};width: max-content;height: max-content;',
					'{{WRAPPER}} .sellkit-product-filter-form-type button svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_form_buttons_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'search_form_button_color_hover',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button:hover' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_form_button_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'search_form_button_size_hover',
			[
				'label' => esc_html__( 'Button Size', 'sellkit-pro' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button:hover' => 'font-size: {{SIZE}}{{UNIT}};width: max-content;height: max-content;',
					'{{WRAPPER}} .sellkit-product-filter-search-wrapper button:hover svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'search_form_button_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'default' => [
					'top' => 5,
					'right' => 5,
					'bottom' => 5,
					'left' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'search_form_button_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-form-type button',
			]
		);

		$this->add_responsive_control(
			'search_form_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-form-type button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_swatches() {
		$this->start_controls_section(
			'section_style_swatch',
			[
				'label' => esc_html__( 'Color Swatch', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'swatches_size',
			[
				'label' => esc_html__( 'Size', 'sellkit-pro' ),
				'type' => 'slider',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-color-swatch .product-filter-item-color' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}}' => '--sellkit-product-filter-color-swatch-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'swatches_space_between',
			[
				'label' => esc_html__( 'Space between', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'swatch_tabs' );

		$this->start_controls_tab(
			'swatch_tabs_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'swatch_normal_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item > span.product-filter-item-color ',
			]
		);

		$this->add_responsive_control(
			'swatch_normal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item > span.product-filter-item-color ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'swatch_tabs_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'swatch_hover_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item:hover > span.product-filter-item-color ',
			]
		);

		$this->add_responsive_control(
			'swatch_hover_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item:hover > span.product-filter-item-color' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'swatch_tabs_active_tab',
			[
				'label' => esc_html__( 'Active', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'swatch_active_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item > span[class*="active-"]',
			]
		);

		$this->add_responsive_control(
			'swatch_active_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item > span[class*="active-"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'variation_swatch_color_checkmark',
			[
				'label' => esc_html__( 'Add Checkmark', 'sellkit-pro' ),
				'type' => 'switcher',
				'default' => '',
				'separator' => 'before',
				'prefix_class' => 'sellkit-product-filter-checkmark-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'variation_swatch_color_checkmark_size',
			[
				'label' => esc_html__( 'Icon Size', 'sellkit-pro' ),
				'type' => 'slider',
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 55,
				],
				'condition'   => [
					'variation_swatch_color_checkmark!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.sellkit-product-filter-checkmark-yes .product-filter-item-color:before' => 'font-size:calc( var(--sellkit-product-filter-color-swatch-size,30px) * calc( {{SIZE}} / 100 ) )',
				],
			]
		);

		$this->add_control(
			'variation_swatch_color_checkmark_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'default' => '#fff',
				'condition'   => [
					'variation_swatch_color_checkmark!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}.sellkit-product-filter-checkmark-yes .product-filter-item-color:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'text-shadow',
			[
				'label' => esc_html__( 'Shadow', 'sellkit-pro' ),
				'name' => 'variation_swatch_color_checkmark_shadow',
				'condition'   => [
					'variation_swatch_color_checkmark!' => '',
				],
				'selector' => '{{WRAPPER}}.sellkit-product-filter-checkmark-yes .product-filter-item-color:before',
			]
		);

		$this->add_group_control(
			'text-stroke',
			[
				'label' => esc_html__( 'Stroke', 'sellkit-pro' ),
				'name' => 'variation_swatch_color_checkmark_stroke',
				'condition'   => [
					'variation_swatch_color_checkmark!' => '',
				],
				'selector' => '{{WRAPPER}}.sellkit-product-filter-checkmark-yes .product-filter-item-color:before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'swatch_tabs_heading',
			[
				'label' => esc_html__( 'Label', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'swatch_label_color',
			[
				'label' => esc_html__( 'Text color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item .term-name' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'swatch_label_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-color-swatch .product-filter-item .term-name',
				'scheme' => '3',
			]
		);

		$this->end_controls_section();
	}

	private function register_style_image_swatches() {
		$this->start_controls_section(
			'section_style_image_swatch',
			[
				'label' => esc_html__( 'Image Swatch', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'image_swatches_size',
			[
				'label' => esc_html__( 'Image Max-Width', 'sellkit-pro' ),
				'type' => 'slider',
				'size_units' => [ 'px', '%' ],
				'default' => [
					'size' => 150,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-image-swatch .product-filter-item-image' => 'max-width: {{SIZE}}{{UNIT}} !important;width: auto!important;height: auto!important;',
				],
			]
		);

		$this->add_responsive_control(
			'image_swatches_space_between',
			[
				'label' => esc_html__( 'Space between', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%', 'vm' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'image_swatch_tabs' );

		$this->start_controls_tab(
			'image_swatch_tabs_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'image_swatch_normal_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item > span.product-filter-item-image',
			]
		);

		$this->add_responsive_control(
			'image_swatch_normal_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item > span.product-filter-item-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_swatch_tabs_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'image_swatch_hover_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item:hover > span.product-filter-item-image',
			]
		);

		$this->add_responsive_control(
			'image_swatch_hover_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item:hover > span.product-filter-item-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'image_swatch_tabs_active_tab',
			[
				'label' => esc_html__( 'Active', 'sellkit-pro' ),
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'image_swatch_active_border',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item > span[class*="active-"]',
			]
		);

		$this->add_responsive_control(
			'image_swatch_active_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item > span[class*="active-"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'image_swatch_tabs_heading',
			[
				'label' => esc_html__( 'Label', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_swatch_label_color',
			[
				'label' => esc_html__( 'Text color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item .term-name' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'image_swatch_label_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-custom-attributes .sellkit-product-filter-image-swatch .product-filter-item .term-name',
				'scheme' => '3',
			]
		);

		$this->end_controls_section();
	}

	private function register_style_product_count() {
		$this->start_controls_section(
			'section_style_product_count',
			[
				'label' => esc_html__( 'Product Count', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'product_count_space_between',
			[
				'label' => esc_html__( 'Space Between', 'sellkit-pro' ),
				'type' => 'slider',
				'size_units' => [ 'px' ],
				'default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-item-wrapper:not(.sellkit-product-filter-rating) .product-filter-item > label' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter-item-wrapper.sellkit-product-filter-rating .product-filter-item .sellkit-product-filter-count' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .product-filter-item > .term-name' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter-button .product-filter-item > span' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sellkit-product-filter-links .product-filter-item > .product-filter-item-links' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .product-filter-item .sellkit-product-filter-count' => 'padding-left: 0 !important;',
				],
			]
		);

		$this->add_control(
			'product_count_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .product-filter-item .sellkit-product-filter-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'product_count_typography',
				'selector' => '{{WRAPPER}} .product-filter-item .sellkit-product-filter-count',
			]
		);

		$this->end_controls_section();
	}

	private function register_style_switch() {
		$this->start_controls_section(
			'section_style_toggle_button',
			[
				'label' => esc_html__( 'Switch', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'filter_group_switch_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-filter-on-sale .sellkit-product-filter-on-sale-wrapper label',
				'scheme' => '3',
			]
		);

		$this->add_control(
			'filter_group_switch_color',
			[
				'label' => esc_html__( 'Text color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-on-sale .sellkit-product-filter-on-sale-wrapper' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'switch_tabs_heading',
			[
				'label' => esc_html__( 'Switch color', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'switch_tabs' );

		$this->start_controls_tab(
			'switch_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'swtich_color_normal',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-on-sale .control-on-sale-checkbox .control-on-sale-checkbox-handler' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'swtich_background_color_normal',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-on-sale .control-on-sale-checkbox' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'switch_active_tab',
			[
				'label' => esc_html__( 'Active', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'swtich_color_active',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-on-sale input:checked + .control-on-sale-checkbox .control-on-sale-checkbox-handler' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'swtich_background_color_active',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-filter-on-sale input:checked + .control-on-sale-checkbox' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_style_applied_filter() {
		$this->start_controls_section(
			'section_style_applied_filter',
			[
				'label' => esc_html__( 'Applied Filter', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'applied_filter_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'applied_filter_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'applied_filter_text_heading',
			[
				'label' => esc_html__( 'Text', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'applied_filter_text_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item',
				'scheme' => '3',
			]
		);

		$this->start_controls_tabs( 'applied_filter_tabs' );

		$this->start_controls_tab(
			'applied_filter_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'applied_filter_color_normal',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'applied_filter_background_color_normal',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'applied_filter_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sellkit-pro' ),
			]
		);

		$this->add_control(
			'applied_filter_color_hover',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item:hover:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'applied_filter_background_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item:hover' => 'background-color: {{VALUE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'applied_filter__heading',
			[
				'label' => esc_html__( 'Border', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'border',
			[
				'name' => 'applied_filter_border',
				'selector' => '{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item',
			]
		);

		$this->add_responsive_control(
			'applied_filter_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', '%' ],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-selected-filter-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'applied_filter_reset_button_heading',
			[
				'label' => esc_html__( 'Reset Button', 'sellkit-pro' ),
				'type' => 'heading',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'applied_filter_reset_button_typography',
				'selector' => '{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-filter-clear',
				'scheme' => '3',
			]
		);

		$this->add_control(
			'applied_filter_reset_button_hover',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-filter-clear' => 'color: {{VALUE}} !important;border-bottom-color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'applied_filter_reset_button_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sellkit-product-selected-filter-wrapper .sellkit-product-filter-clear' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_style_not_found() {
		$this->start_controls_section(
			'section_style_not_found_text',
			[
				'label' => esc_html__( 'Not Found Text', 'sellkit-pro' ),
				'tab' => 'style',
			]
		);

		$this->add_responsive_control(
			'not_found_text_align',
			[
				'label'  => esc_html__( 'Alignment', 'sellkit-pro' ),
				'type' => 'choose',
				'default' => '',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sellkit-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'.sellkit-active-wrapper.sellkit-active-filter-{{ID}} .sellkit-not-found' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'not_found_text_color',
			[
				'label' => esc_html__( 'Color', 'sellkit-pro' ),
				'type' => 'color',
				'selectors' => [
					'.sellkit-active-wrapper.sellkit-active-filter-{{ID}} .sellkit-not-found' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			'typography',
			[
				'name' => 'not_found_text_typography',
				'selector' => '.sellkit-active-wrapper.sellkit-active-filter-{{ID}} .sellkit-not-found',
			]
		);

		$this->add_responsive_control(
			'not_found_text_padding',
			[
				'label' => esc_html__( 'Padding', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors' => [
					'.sellkit-active-wrapper.sellkit-active-filter-{{ID}} .sellkit-not-found' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'not_found_text_margin',
			[
				'label' => esc_html__( 'Margin', 'sellkit-pro' ),
				'type' => 'dimensions',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'.sellkit-active-wrapper.sellkit-active-filter-{{ID}} .sellkit-not-found' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$filters  = $settings['filters'];

		$content_style = $settings['content_style'];

		$elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );

		$shop_class = '';

		if ( is_shop() && empty( $elementor_page ) ) {
			$shop_class = 'sellkit-is-default-shop';
		}

		$archive_data = Sellkit_Elementor_Product_Filter_Module::get_archive_data();

		$this->add_render_attribute( 'filter-wrapper', [
			'id' => "sellkit-product-filter-{$this->get_id()}",
			'class' => "sellkit-product-filter {$shop_class}",
			'data-archive' => wp_json_encode( $archive_data, JSON_FORCE_OBJECT ),
		] );

		$this->add_render_attribute( 'filter-form', [
			'class' => "sellkit-product-filter-form sellkit-product-filter-form-{$content_style}",
		] );
		?>
		<div <?php echo $this->get_render_attribute_string( 'filter-wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'filter-form' ); ?>>
			<?php
			foreach ( $filters as $filter ) {
				echo '<div class="sellkit-product-filter-repeater-item elementor-repeater-item-' . esc_attr( $filter['_id'] ) . '">';
				Sellkit_Elementor_Product_Filter_Module::render_field( $this, $filter );
				echo '</div>';
			}
			?>
			</div>
		</div>
		<?php
	}
}
