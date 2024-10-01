<?php
namespace Elementor;

use WfpFundraising\Apps\Key;

defined( 'ABSPATH' ) || exit;


class Wfp_Fundraising_Donate extends Widget_Base {

	public $base;

	public function get_name() {
		return 'wfp-fundraising-donate';
	}

	public function get_title() {
		return esc_html__( 'Donate Button', 'wp-fundraising' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return array( 'wfp-fundraising' );
	}

	 // get query post query
	public function get_post() {

		$args['post_status'] = 'publish';
		$args['post_type']   = \WfpFundraising\Apps\Content::post_type();
		$args['meta_query']  = array(
			'relation' => 'AND',
			array(
				'key'     => 'wfp_founding_form_format_type',
				'value'   => 'donation',
				'compare' => '=',
			),
		);

		$posts   = get_posts( $args );
		$options = array();
		$count   = count( $posts );
		if ( $count > 0 ) :
			foreach ( $posts as $post ) {
				$options[ $post->ID ] = $post->post_title;
			}
		endif;

		return $options;
	}


	protected function register_controls() {

		// content of listing
		$this->start_controls_section(
			'wfp_fundraising_donate_content',
			array(
				'label' => esc_html__( 'Content', 'wp-fundraising' ),
			)
		);

		 // headding query option
		$this->add_control(
			'wfp_fundraising_donate_content__query_options',
			array(
				'label'     => __( 'Query Options', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		$this->add_control(
			'wfp_fundraising_donate_content__select_post',
			array(
				'label'    => __( 'Select Donate', 'wp-fundraising' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => false,
				'options'  => $this->get_post(),
				'default'  => 0,
			)
		);

		 // headding query option
		$this->add_control(
			'wfp_fundraising_donate_content__display_options',
			array(
				'label'     => __( 'Display Options', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_control(
			'wfp_fundraising_donate_content__form_style',
			array(
				'label'   => esc_html__( 'From Style', 'wp-fundraising' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'only_button',
				'options' => array(
					'all_fields'  => esc_html__( 'All Fields', 'wp-fundraising' ),
					'only_button' => esc_html__( 'Only Button', 'wp-fundraising' ),
				),

			)
		);
		$this->add_control(
			'wfp_fundraising_donate_content__modal_status',
			array(
				'label'   => esc_html__( 'Show Modal', 'wp-fundraising' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'Yes',
				'options' => array(
					'Yes' => esc_html__( 'Yes', 'wp-fundraising' ),
					'No'  => esc_html__( 'No', 'wp-fundraising' ),
				),
				// 'condition' => ['wfp_fundraising_donate_content__form_style' => 'only_button'],
			)
		);

		$this->add_control(
			'wfp_fundraising_content_donate__title_enable',
			array(
				'label'        => esc_html__( 'Show Title', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
			)
		);

		$this->add_control(
			'wfp_fundraising_content_donate__featured_enable',
			array(
				'label'        => esc_html__( 'Show Featured', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
			)
		);
		$this->add_control(
			'wfp_fundraising_content_donate__category_enable',
			array(
				'label'        => esc_html__( 'Show Category', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
			)
		);

		$this->add_control(
			'wfp_fundraising_content_donate__goal_enable',
			array(
				'label'        => esc_html__( 'Show Goal', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wfp_fundraising_style_donate_title',
			array(
				'label'     => esc_html__( 'Title', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content_donate__title_enable' => 'Yes' ),
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_title__typography',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfp-post-title',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_donate_title__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfdp-donation-form  .wfp-post-title' => 'color: {{VALUE}}',
				),
			)
		);
		// text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_title__box_shadow',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfp-post-title',
			)
		);

		$this->end_controls_section();

		 // style of categories
		$this->start_controls_section(
			'wfp_fundraising_style_donate_categories',
			array(
				'label'     => esc_html__( 'Categories ', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content_donate__category_enable' => 'Yes' ),
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_categories__typography',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfp-header-cat .wfp-header-cat--link',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_donate_categories__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfdp-donation-form  .wfp-header-cat .wfp-header-cat--link' => 'color: {{VALUE}}',
				),
			)
		);
		// text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_categories__box_shadow',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfp-header-cat .wfp-header-cat--link',
			)
		);

		$this->end_controls_section();

		  // style of Goal
		$this->start_controls_section(
			'wfp_fundraising_style_donate_goal',
			array(
				'label'     => esc_html__( 'Goal ', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content_donate__goal_enable' => 'Yes' ),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_gola__currency_headding',
			array(
				'label'     => __( 'Text', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		 // typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_gola__currency_typography',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfdp-donate-goal-progress .wfp-currency-symbol, {{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress .raised',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_donate_gola__currency_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress .wfp-currency-symbol,  {{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress .raised' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_gola__amount_headding',
			array(
				'label'     => __( 'Number', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		 // typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_gola__amount_typography',
				'selector' => '{{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress .donate-percentage, {{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress strong, {{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress .wfp-goal-sp',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_donate_gola__amount_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress .donate-percentage, {{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress strong, {{WRAPPER}} .wfdp-donation-form .wfdp-donate-goal-progress .wfp-goal-sp' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_gola__bar_headding',
			array(
				'label'     => __( 'Bar', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_gola__visible_bar_headding',
			array(
				'label' => __( 'Visible Bar', 'wp-fundraising' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_gola__bar_visible_background',
				'label'    => esc_html__( 'Visible Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfdp-donation-form .wfdp-progress-bar .xs-progress-bar, {{WRAPPER}} .wfp-round-bar .wfp-round-bar-data',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_gola__disable_bar_headding',
			array(
				'label' => __( 'Disable Bar', 'wp-fundraising' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_gola__bar_disable_background',
				'label'    => esc_html__( 'Disable Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfdp-donation-form .wfdp-progress-bar .xs-progress',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola_donate__global_bar_headding',
			array(
				'label' => __( 'Global Bar', 'wp-fundraising' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_gola_donate__global_bar_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfdp-donation-form .wfdp-progress-bar .xs-progress' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola_donate__global_bar_height',
			array(
				'label'      => __( 'Bar Height', 'wp-fundraising' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),

				'selectors'  => array(
					'{{WRAPPER}} .wfdp-donation-form .wfdp-progress-bar .xs-progress' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_global_bar_count',
			array(
				'label'      => __( 'Bar Count Size', 'wp-fundraising' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),

				'selectors'  => array(
					'{{WRAPPER}} .wfp-round-bar .wfp-round-bar-data' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_global_bar_count_typo',
				'label'    => __( 'Bar Count Typography', 'wp-fundraising' ),
				'selector' => '{{WRAPPER}} .wfp-round-bar .wfp-round-bar-data',
			)
		);

		$this->add_control(
			'wfp_fundraising_style_global_bar_count_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-round-bar .wfp-round-bar-data' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		 // style of button
		$this->start_controls_section(
			'wfp_fundraising_style_donate_button',
			array(
				'label' => esc_html__( 'Button ', 'wp-fundraising' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_button__typo',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn',
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_donate_button__padding',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_donate_button__margin',
			array(
				'label'      => esc_html__( 'Margin', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_donate_button__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_button__background',
				'label'    => esc_html__( 'Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn',
				'exclude'  => array(
					'image',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_button__border',
				'label'    => esc_html__( 'Border', 'wp-fundraising' ),
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn',
			)
		);
		// border radius
		$this->add_responsive_control(
			'wfp_fundraising_style_donate_button__border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_button__box_shadow',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .xs-btn.submit-btn',
			)
		);

		$this->end_controls_section();

		 // style of input filed
		$this->start_controls_section(
			'wfp_fundraising_style_donate_input',
			array(
				'label' => esc_html__( 'Input ', 'wp-fundraising' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_input_headding_1',
			array(
				'label'     => __( 'Input', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_typo',
				'selector' => '{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol',
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_input_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_bg_color',
				'label'    => esc_html__( 'Background Color', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol',
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_donate_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_donate_input_margin',
			array(
				'label'      => esc_html__( 'Margin', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_border',
				'label'    => esc_html__( 'Border', 'wp-fundraising' ),
				'selector' => '{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol',
			)
		);

		// border radius
		$this->add_responsive_control(
			'wfp_fundraising_style_donate_input_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_box_shadow',
				'selector' => '{{WRAPPER}} .wfp-donation-form-wraper .wfdp-donation-input-form .regular-text, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-field, {{WRAPPER}} .wfdp-donationForm .wfdp-donation-input-form .xs-donate-field-wrap .xs-money-symbol',
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_radio',
			array(
				'label'     => __( 'Radio Input', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_radio_normal_color',
			array(
				'label'     => esc_html__( 'Radio Normal Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-radio-input-style-2 .xs_radio_filed[type=radio]' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_radio_active_color',
			array(
				'label'     => esc_html__( 'Radio Active Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-radio-input-style-2 .xs_radio_filed[type=radio]:checked' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wfp-radio-input-style-2 .xs_radio_filed[type=radio]:checked::before'  => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_input_label',
			array(
				'label'     => __( 'Label', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_label__typography',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfdp-donation-input-form label, .wfp-payment-method-acc-details--title, .wfp-payment-method-acc-details--description, {{WRAPPER}} .wfp-payment-method-acc-details--title',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_donate_input_label__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfdp-donation-form  .wfdp-donation-input-form label, .wfp-payment-method-acc-details--title, .wfp-payment-method-acc-details--description, {{WRAPPER}} .wfp-payment-method-acc-details--title' => 'color: {{VALUE}}',
				),
			)
		);
		// text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_label__box_shadow',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfdp-donation-input-form label, .wfp-payment-method-acc-details--title, .wfp-payment-method-acc-details--description, {{WRAPPER}} .wfp-payment-method-acc-details--title',
			)
		);

		$this->add_control(
			'wfp_fundraising_style_donate_input_headding',
			array(
				'label'     => __( 'Headding', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_headding__typography',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfdp-input-payment-field span, {{WRAPPER}} .wfdp-donation-form .wfp-payment-method-title, {{WRAPPER}} .wfdp-donation-form .wfp-payment-method-acc-details',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_donate_input_headding__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfdp-donation-form  .wfdp-input-payment-field span, {{WRAPPER}} .wfdp-donation-form .wfp-payment-method-title, {{WRAPPER}} .wfdp-donation-form .wfp-payment-method-acc-details' => 'color: {{VALUE}}',
				),
			)
		);
		// text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_donate_input_headding__box_shadow',
				'selector' => '{{WRAPPER}} .wfdp-donation-form  .wfdp-input-payment-field span,  {{WRAPPER}} .wfdp-donation-form .wfp-payment-method-title, {{WRAPPER}} .wfdp-donation-form .wfp-payment-method-acc-details',
			)
		);
		$this->end_controls_section();
	}
	 // render files
	protected function render() {

		$settings = $this->get_settings_for_display();
		extract( $settings );

		if ( empty( $wfp_fundraising_donate_content__select_post ) ) {
			echo esc_html__( 'Select any Donation from Dropdown.', 'wp-fundraising' );
			return '';
		}

		$post = get_post( $wfp_fundraising_donate_content__select_post );

		if ( is_object( $post ) ) {

			$postId = $post->ID;

			$wfp_donation_type = Key::WFP_DONATION_TYPE_SINGLE;
			$show_in_modal     = $wfp_fundraising_donate_content__modal_status;
			$wfp_form_fields   = $wfp_fundraising_donate_content__form_style;  // all_fields, Only_button

			$arrayPayment = xs_payment_services();

			include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/single-page/donation-form.php';

		} else {
			echo esc_html__( 'Please select any donate.', 'wp-fundraising' );
		}
	}
}
