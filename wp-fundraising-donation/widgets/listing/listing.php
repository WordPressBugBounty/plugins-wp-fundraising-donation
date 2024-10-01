<?php

namespace Elementor;

defined( 'ABSPATH' ) || exit;

class Wfp_Fundraising_Listing extends Widget_Base {

	public $base;

	public function __construct( array $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_enqueue_style( 'wfp_single_campaign_css' );
	}


	public function get_name() {
		return 'wfp-fundraising-listing';
	}

	public function get_title() {
		return esc_html__( 'Campaign List', 'wp-fundraising' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return array( 'wfp-fundraising' );
	}

	public function wfp_fundrising_navigation_position() {
		$position_options = array(
			'top-left'      => esc_html__( 'Top Left', 'wp-fundraising' ),
			'top-center'    => esc_html__( 'Top Center', 'wp-fundraising' ),
			'top-right'     => esc_html__( 'Top Right', 'wp-fundraising' ),
			'center'        => esc_html__( 'Center', 'wp-fundraising' ),
			'bottom-left'   => esc_html__( 'Bottom Left', 'wp-fundraising' ),
			'bottom-center' => esc_html__( 'Bottom Center', 'wp-fundraising' ),
			'bottom-right'  => esc_html__( 'Bottom Right', 'wp-fundraising' ),
		);

		return $position_options;
	}


	public function wfp_fundrising_pagination_position() {
		$position_options = array(
			'top-left'      => esc_html__( 'Top Left', 'wp-fundraising' ),
			'top-center'    => esc_html__( 'Top Center', 'wp-fundraising' ),
			'top-right'     => esc_html__( 'Top Right', 'wp-fundraising' ),
			'bottom-left'   => esc_html__( 'Bottom Left', 'wp-fundraising' ),
			'bottom-center' => esc_html__( 'Bottom Center', 'wp-fundraising' ),
			'bottom-right'  => esc_html__( 'Bottom Right', 'wp-fundraising' ),
		);

		return $position_options;
	}


	// get query categories
	public function get_category() {
		$taxonomy   = 'wfp-categories';
		$query_args = array(
			'taxonomy'   => array( 'wfp-categories' ), // taxonomy name
			'orderby'    => 'name',
			'order'      => 'DESC',
			'hide_empty' => true,
			'number'     => 6,
		);

		$terms = get_terms( $query_args );

		$options = array();
		$count   = is_array( $terms ) ? count( $terms ) : 0;

		if ( $count > 0 ) :
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		endif;

		return $options;
	}

	// get campaign list
	public function get_campaign_list() {

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$query_args = array(
			'post_type'      => \WfpFundraising\Apps\Content::post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		$query   = new \WP_Query( $query_args );
		$options = array();
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) {
				$query->the_post();
				$options[ get_the_ID() ] = get_the_title();
			}
		endif;

		wp_reset_postdata();

		return count( $options ) > 0 ? $options : array( 0 => __( 'None', 'wp-fundraising' ) );
	}


	/**
	 * Get the first key of an associative array
	 *
	 * @param $array
	 *
	 * @return int|string
	 * @since 1.1.12
	 */
	private function get_first_key( $array ) {

		if ( empty( $array ) ) {
			return '';
		}

		if ( ! is_array( $array ) ) {
			return '';
		}

		foreach ( $array as $key => $val ) {

			return $key;
		}

		return '';
	}

	protected function render_pagination() {
		$settings = $this->get_settings();
		?>
		<div class="wfp_fundrising-position-z-index wfp_fundrising-position-<?php echo esc_attr( $settings['wfp_fundrising_dots_position'] ); ?>">
			<div class="wfp_fundrising-dots-container">
				<div class="wfp_fundrising-swiper-pagination"></div>
			</div>
		</div>
		<?php
	}

	protected function render_navigation() {
		$settings = $this->get_settings();
		?>

		<div class="wfp_fundrising-position-z-index wfp_fundrising-visible@m wfp_fundrising-position-<?php echo esc_attr( $settings['wfp_fundrising_arrows_position'] ); ?>">
			<div class="wfp_fundrising-arrows-container wfp_fundrising-slidenav-container">
				<div class="wfp_fundrising-nav wfp_fundrising-nav-prev">
					<a href=""
					   class="wfp_fundrising-navigation-prev wfp_fundrising-slidenav-previous wfp_fundrising-icon wfp_fundrising-slidenav">
						<?php
						// new icon
						$migrated = isset( $settings['__fa4_migrated']['wfp_fundrising_arrows_prev_icons'] );
						// Check if its a new widget without previously selected icon using the old Icon control
						$is_new = empty( $settings['wfp_fundrising_arrows_prev_icon'] );
						if ( $is_new || $migrated ) {
							// new icon
							Icons_Manager::render_icon( $settings['wfp_fundrising_arrows_prev_icons'], array( 'aria-hidden' => 'true' ) );
						} else {
							?>
							<i class="<?php echo esc_attr( $settings['wfp_fundrising_arrows_prev_icon'] ); ?>"
							   aria-hidden="true"></i>
							<?php
						}
						?>
					</a>
				</div>

				<div class="wfp_fundrising-nav wfp_fundrising-nav-next">
					<a href=""
					   class="wfp_fundrising-navigation-next wfp_fundrising-slidenav-next wfp_fundrising-icon wfp_fundrising-slidenav">
						<?php
						// new icon
						$migrated = isset( $settings['__fa4_migrated']['wfp_fundrising_arrows_next_icons'] );
						// Check if its a new widget without previously selected icon using the old Icon control
						$is_new = empty( $settings['wfp_fundrising_arrows_next_icon'] );
						if ( $is_new || $migrated ) {
							// new icon
							Icons_Manager::render_icon( $settings['wfp_fundrising_arrows_next_icons'], array( 'aria-hidden' => 'true' ) );
						} else {
							?>
							<i class="<?php echo esc_attr( $settings['wfp_fundrising_arrows_next_icon'] ); ?>"
							   aria-hidden="true"></i>
							<?php
						}
						?>
					</a>
				</div>
			</div>
		</div>

		<?php
	}


	protected function register_controls() {

		// content of listing
		$this->start_controls_section(
			'wfp_fundraising_content',
			array(
				'label' => esc_html__( 'Content', 'wp-fundraising' ),
			)
		);

		// headding layout option
		$this->add_control(
			'wfp_fundraising_content__layout_options',
			array(
				'label'     => __( 'Layout Options', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		$this->add_control(
			'wfp_fundraising_content__layout_style',
			array(
				'label'   => esc_html__( 'Layout Style', 'wp-fundraising' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'wfp-layout-grid',
				'options' => array(
					'wfp-layout-list'     => esc_html__( 'List', 'wp-fundraising' ),
					'wfp-layout-grid'     => esc_html__( 'Grid', 'wp-fundraising' ),
					'wfp-layout-masonary' => esc_html__( 'Masonary', 'wp-fundraising' ),
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_content__is_button',
			array(
				'label'     => esc_html__( 'Enable Button', 'wp-fundraising' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
				'condition' => array(
					'wfp_fundraising_content__layout_style' => 'wfp-layout-list',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_content__btn1-text',
			array(
				'label'       => esc_html__( 'Label One', 'wp-fundraising' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Learn more ', 'wp-fundraising' ),
				'placeholder' => esc_html__( 'Learn more ', 'wp-fundraising' ),
				'dynamic'     => array(
					'active' => true,
				),
				'separator'   => 'before',
				'condition'   => array(
					'wfp_fundraising_content__is_button' => 'yes',
				),
			)
		);
		$this->add_control(
			'wfp_fundraising_content__btn1-url',
			array(
				'label'       => esc_html__( 'URL One', 'wp-fundraising' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_url( 'http://your-link.com' ),
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				),
				'condition'   => array(
					'wfp_fundraising_content__is_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_content__btn2-text',
			array(
				'label'     => esc_html__( 'Label Two', 'wp-fundraising' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'separator' => 'before',
				'condition' => array(
					'wfp_fundraising_content__is_button' => 'yes',
				),
			)
		);
		$this->add_control(
			'wfp_fundraising_content__btn2-url',
			array(
				'label'       => esc_html__( 'URL Two', 'wp-fundraising' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_url( 'http://your-link.com' ),
				'dynamic'     => array(
					'active' => true,
				),
				'separator'   => 'after',
				'condition'   => array(
					'wfp_fundraising_content__is_button' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_content__column_grid',
			array(
				'label'              => esc_html__( 'Columns Grid', 'wp-fundraising' ),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options'            => array(
					'1' => esc_html__( '1 Columns', 'wp-fundraising' ),
					'2' => esc_html__( '2 Columns', 'wp-fundraising' ),
					'3' => esc_html__( '3 Columns', 'wp-fundraising' ),
					'4' => esc_html__( '4 Columns', 'wp-fundraising' ),
				),
				'devices'            => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default'    => 3,
				'tablet_default'     => 2,
				'mobile_default'     => 1,
				'default'            => 3,
				'condition'          => array( 'wfp_fundraising_content__layout_style!' => 'wfp-layout-list' ),
			)
		);

		$this->add_control(
			'wfp_fundraising_content__is_carousel',
			array(
				'label'     => esc_html__( 'Enable Carousel? ', 'wp-fundraising' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => esc_html__( 'Yes', 'wp-fundraising' ),
				'label_off' => esc_html__( 'No', 'wp-fundraising' ),
			)
		);

		// headding query option
		$this->add_control(
			'wfp_fundraising_content__query_options',
			array(
				'label'     => __( 'Query Options', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_control(
			'wfp_fundraising_layout_option',
			array(
				'label'   => esc_html__( 'Show Campaign by:', 'wp-fundraising' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'categories',
				'options' => array(
					'categories' => esc_html__( 'Categories', 'wp-fundraising' ),
					'selected'   => esc_html__( 'Selected Campaign', 'wp-fundraising' ),
					'recent'     => esc_html__( 'Recent Campaign', 'wp-fundraising' ),
				),

			)
		);

		$this->add_control(
			'wfp_fundraising_content__categories',
			array(
				'label'     => __( 'Select Categories', 'wp-fundraising' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $this->get_category(),
				'default'   => array(),
				'condition' => array(
					'wfp_fundraising_layout_option' => 'categories',
				),
			)
		);

		$camp_list = $this->get_campaign_list();
		$def       = $this->get_first_key( $camp_list );

		$this->add_control(
			'wfp_fundraising_content__selected',
			array(
				'label'       => esc_html__( 'Select Campaign', 'wp-fundraising' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $camp_list,
				'default'     => array( $def ),
				'label_block' => false,
				'multiple'    => true,
				'condition'   => array(
					'wfp_fundraising_layout_option' => 'selected',
				),
			)
		);

		unset( $camp_list, $def );

		$this->add_control(
			'wfp_fundraising_content__orderby',
			array(
				'label'     => esc_html__( 'Order By', 'wp-fundraising' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'post_date',
				'options'   => array(
					'name'      => esc_html__( 'Name', 'wp-fundraising' ),
					'post_date' => esc_html__( 'Date', 'wp-fundraising' ),
				),
				'condition' => array(
					'wfp_fundraising_layout_option!' => 'recent',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_content__show_post',
			array(
				'label'   => esc_html__( 'Show Per Page', 'wp-fundraising' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 9,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,

			)
		);

		$this->add_control(
			'wfp_fundraising_content__order',
			array(
				'label'   => esc_html__( 'Order', 'wp-fundraising' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'ASC'  => esc_html__( 'ASC', 'wp-fundraising' ),
					'DESC' => esc_html__( 'DESC', 'wp-fundraising' ),
				),
			)
		);
		// headding display option
		$this->add_control(
			'wfp_fundraising_content__display_options',
			array(
				'label'     => __( 'Display Options', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		$this->add_control(
			'wfp_fundraising_content__flip_enable',
			array(
				'label'        => esc_html__( 'Flip Content', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'wfp_fundraising_content__filter_enable',
			array(
				'label'        => esc_html__( 'Show Filter', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'wfp_fundraising_content__featured_enable',
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
			'wfp_fundraising_content__time_left',
			array(
				'label'        => esc_html__( 'Show Time Left', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
				'condition'    => array(
					'wfp_fundraising_content__layout_style' => 'wfp-layout-list',
				),
			)
		);
		$this->add_control(
			'wfp_fundraising_content__title_enable',
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
			'wfp_fundraising_content__title_limit',
			array(
				'label'     => esc_html__( 'Title Limit', 'wp-fundraising' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 40,
				'min'       => 1,
				'max'       => 150,
				'step'      => 1,
				'condition' => array( 'wfp_fundraising_content__title_enable' => 'Yes' ),
			)
		);

		$this->add_control(
			'wfp_fundraising_content__excerpt_enable',
			array(
				'label'        => esc_html__( 'Show Excerpt', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
			)
		);
		$this->add_control(
			'wfp_fundraising_content__excerpt_limit',
			array(
				'label'     => esc_html__( 'Excerpt Limit', 'wp-fundraising' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 60,
				'min'       => 1,
				'max'       => 200,
				'step'      => 1,
				'condition' => array( 'wfp_fundraising_content__excerpt_enable' => 'Yes' ),
			)
		);
		$this->add_control(
			'wfp_fundraising_content__category_enable',
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
			'wfp_fundraising_content__goal_enable',
			array(
				'label'        => esc_html__( 'Show Goal', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
			)
		);
		$this->add_control(
			'wfp_fundraising_content__user_enable',
			array(
				'label'        => esc_html__( 'Show Author Info', 'wp-fundraising' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wp-fundraising' ),
				'label_off'    => esc_html__( 'Hide', 'wp-fundraising' ),
				'return_value' => 'Yes',
				'default'      => 'Yes',
			)
		);
		$this->end_controls_section();

		// Carousel Control
		$this->start_controls_section(
			'wfp_fundrising_section_carousel_settings',
			array(
				'label'     => esc_html__( 'Carousel Settings', 'wp-fundraising' ),
				'condition' => array(
					'wfp_fundraising_content__is_carousel' => 'yes',
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_autoplay',
			array(
				'label'   => esc_html__( 'Autoplay', 'wp-fundraising' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',

			)
		);

		$this->add_control(
			'wfp_fundrising_autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed', 'wp-fundraising' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'condition' => array(
					'wfp_fundrising_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_loop',
			array(
				'label'   => esc_html__( 'Loop', 'wp-fundraising' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',

			)
		);

		$this->add_control(
			'wfp_fundrising_speed',
			array(
				'label'   => esc_html__( 'Animation Speed', 'wp-fundraising' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 50,
				),
				'range'   => array(
					'min'  => 100,
					'max'  => 1000,
					'step' => 10,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wfp_fundrising_section_content_navigation',
			array(
				'label'     => esc_html__( 'Navigation', 'wp-fundraising' ),
				'condition' => array(
					'wfp_fundraising_content__is_carousel' => 'yes',
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_navigation',
			array(
				'label'        => esc_html__( 'Navigation', 'wp-fundraising' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'none',
				'options'      => array(
					'both'   => esc_html__( 'Arrows and Dots', 'wp-fundraising' ),
					'arrows' => esc_html__( 'Arrows', 'wp-fundraising' ),
					'dots'   => esc_html__( 'Dots', 'wp-fundraising' ),
					'none'   => esc_html__( 'None', 'wp-fundraising' ),
				),
				'prefix_class' => 'wfp_fundrising-navigation-type-',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'wfp_fundrising_both_position',
			array(
				'label'     => esc_html__( 'Arrows and Dots Position', 'wp-fundraising' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => $this->wfp_fundrising_navigation_position(),
				'condition' => array(
					'wfp_fundrising_navigation' => 'boths',
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_arrows_position',
			array(
				'label'     => esc_html__( 'Arrows Position', 'wp-fundraising' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => $this->wfp_fundrising_navigation_position(),
				'condition' => array(
					'wfp_fundrising_navigation' => 'arrowss',
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_dots_position',
			array(
				'label'     => esc_html__( 'Dots Position', 'wp-fundraising' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom-center',
				'options'   => $this->wfp_fundrising_pagination_position(),
				'condition' => array(
					'wfp_fundrising_navigation' => 'dotss',
				),
			)
		);

		$this->end_controls_section();
		// End Carousel Control

		// style of global
		$this->start_controls_section(
			'wfp_fundraising_style_global',
			array(
				'label' => esc_html__( 'Global', 'wp-fundraising' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_global__border',
				'label'    => esc_html__( 'Border', 'wp-fundraising' ),
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog',
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_global__border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_global__box_shadow',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog',
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_global__padding',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'wfp_fundraising_style_global__margin',
			array(
				'label'      => esc_html__( 'Margin', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_global__background',
				'label'    => esc_html__( 'Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_global__alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'wp-fundraising' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents ' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section();

		// content wrapper
		$this->start_controls_section(
			'wfp_fundraising_style_content_wrapper',
			array(
				'label' => esc_html__( 'Content Wrapper', 'wp-fundraising' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_content_wrapper__padding',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
		// end content wrapper

		// style of featured information
		$this->start_controls_section(
			'wfp_fundraising_style_featured',
			array(
				'label'     => esc_html__( 'Featured ', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content__featured_enable' => 'Yes' ),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_featured__border',
				'label'    => esc_html__( 'Border', 'wp-fundraising' ),
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog  .wfp-feature-video, {{WRAPPER}} .wfp-list-campaign .campaign-blog  .wfp-post-image',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_featured__box_shadow',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-feature-video, {{WRAPPER}} .wfp-list-campaign .campaign-blog  .wfp-post-image',
			)
		);

		$this->end_controls_section();
		// style of title
		$this->start_controls_section(
			'wfp_fundraising_style_title',
			array(
				'label'     => esc_html__( 'Title', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content__title_enable' => 'Yes' ),
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_title__typography',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--title__link',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_title__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--title__link' => 'color: {{VALUE}}',
				),
			)
		);
		// text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_title__box_shadow',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--title__link',
			)
		);

		// spacing
		$this->add_responsive_control(
			'wfp_fundraising_style_title___margin',
			array(
				'label'      => __( 'Spacing', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// style of excerpt
		$this->start_controls_section(
			'wfp_fundraising_style_excerpt',
			array(
				'label'     => esc_html__( 'Excerpt ', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content__excerpt_enable' => 'Yes' ),
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_excerpt__typography',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--short-description',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_excerpt__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--short-description' => 'color: {{VALUE}}',
				),
			)
		);
		// text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_excerpt__box_shadow',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--short-description',
			)
		);

		// spacing
		$this->add_responsive_control(
			'wfp_fundraising_style_excerpt___margin',
			array(
				'label'      => __( 'Spacing', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--short-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// style of categories
		$this->start_controls_section(
			'wfp_fundraising_style_categories',
			array(
				'label'     => esc_html__( 'Categories ', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content__category_enable' => 'Yes' ),
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_categories__typography',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--cat__link',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_categories__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--cat__link' => 'color: {{VALUE}}',
				),
			)
		);
		// text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_categories__box_shadow',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--cat__link',
			)
		);

		// spacing
		$this->add_responsive_control(
			'wfp_fundraising_style_author__name_margin',
			array(
				'label'      => __( 'Spacing', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content--cat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// style of Goal
		$this->start_controls_section(
			'wfp_fundraising_style_goal',
			array(
				'label'     => esc_html__( 'Goal ', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content__goal_enable' => 'Yes' ),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola__currency_headding',
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
				'name'     => 'wfp_fundraising_style_gola__currency_typography',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .wfp-currency-symbol, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .raised, {{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .target-date-goal',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_gola__currency_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .raised, {{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .target-date-goal' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola__amount_headding',
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
				'name'     => 'wfp_fundraising_style_gola__amount_typography',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .wfp-currency-symbol, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .donate-percentage, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress strong, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .wfp-goal-sp, {{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .wfp-inner-data',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_gola__amount_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .wfp-currency-symbol, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .donate-percentage, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress strong, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfdp-donate-goal-progress .wfp-goal-sp, {{WRAPPER}} .wfp-view .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .wfp-inner-data' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_bar_time_left',
			array(
				'label'     => __( 'Time Left', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_bar_time_left_typo',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .number_donation_count, {{WRAPPER}} .number_donation_count_list',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_bar_time_left_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .number_donation_count, {{WRAPPER}} .number_donation_count_list' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'wfp_fundraising_style_bar_time_left_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .number_donation_count .wfp-icon, {{WRAPPER}} .number_donation_count_list .wfp-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_bar_time_left_bg',
				'label'    => esc_html__( 'Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .number_donation_count, {{WRAPPER}} .number_donation_count_list',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_responsive_control(
			'',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .number_donation_count, {{WRAPPER}} .number_donation_count_list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_bar_time_left_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'after',
				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .wfp-campaign-content .number_donation_count, {{WRAPPER}} .number_donation_count_list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola__bar_headding',
			array(
				'label'     => __( 'Bar', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola__visible_bar_headding',
			array(
				'label' => __( 'Visible Bar', 'wp-fundraising' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_gola__bar_visible_background',
				'label'    => esc_html__( 'Visible Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfdp-progress-bar .xs-progress-bar, {{WRAPPER}} .wfp-round-bar .wfp-round-bar-data',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola__disable_bar_headding',
			array(
				'label' => __( 'Disable Bar', 'wp-fundraising' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_gola__bar_disable_background',
				'label'    => esc_html__( 'Disable Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfdp-progress-bar .xs-progress',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola__global_bar_headding',
			array(
				'label' => __( 'Global Bar', 'wp-fundraising' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_gola__global_bar_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfdp-progress-bar .xs-progress, {{WRAPPER}} .wfp-list-campaign .campaign-blog .wfdp-progress-bar .xs-progress-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_gola__global_bar_height',
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
					'{{WRAPPER}} .wfp-view .wfdp-progress-bar .xs-progress, {{WRAPPER}} .wfp-view .xs-progress-bar' => 'height: {{SIZE}}{{UNIT}} !important;',
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

		// style of author information
		$this->start_controls_section(
			'wfp_fundraising_style_author',
			array(
				'label'     => esc_html__( 'Author ', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content__user_enable' => 'Yes' ),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_author__headding',
			array(
				'label'     => __( 'Photos', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_style_author__photos_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-campign-user .profile-image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_author__photos_width',
			array(
				'label'      => __( 'Size', 'wp-fundraising' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'size' => 36,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),

				'selectors'  => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-campign-user .profile-image > img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_author__headding_title',
			array(
				'label'     => __( 'Title', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_author__title_typography',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .profile-info .display-name',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_author__title_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .profile-info .display-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_style_author__headding_name',
			array(
				'label'     => __( 'Name', 'wp-fundraising' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
			)
		);
		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_style_author__name_typography',
				'selector' => '{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .profile-info .display-name .display-name__author',
			)
		);

		// color
		$this->add_control(
			'wfp_fundraising_style_author__name_color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-list-campaign .campaign-blog .wfp-compaign-contents .profile-info .display-name .display-name__author' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		// button
		$this->start_controls_section(
			'wfp_btn_section_style',
			array(
				'label'     => esc_html__( 'Button', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'wfp_fundraising_content__is_button' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'wfp_btn_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-view .wfp-list-campaign .wfp-fundrising-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_btn_typography',
				'label'    => esc_html__( 'Typography', 'wp-fundraising' ),
				'selector' => '{{WRAPPER}} .wfp-view .wfp-list-campaign .wfp-fundrising-button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'wfp_btn_shadow',
				'selector' => '{{WRAPPER}} .wfp-view .wfp-list-campaign .wfp-fundrising-button',
			)
		);

		$this->start_controls_tabs( 'wfp_btn_tabs_style' );

		$this->start_controls_tab(
			'wfp_btn_tabnormal',
			array(
				'label' => esc_html__( 'Normal', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_btn_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wfp-view .wfp-list-campaign .wfp-fundrising-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_btn_bg_color',
				'default'  => '',
				'selector' => '{{WRAPPER}} .wfp-view .wfp-list-campaign .wfp-fundrising-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wfp_btn_tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_btn_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .wfp-view .wfp-list-campaign .wfp-fundrising-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_btn_bg_hover_color',
				'default'  => '',
				'selector' => '{{WRAPPER}} .wfp-view .wfp-list-campaign .wfp-fundrising-button:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		// end button

		// navigation style controls
		$this->start_controls_section(
			'wfp_fundrising_section_style_navigation',
			array(
				'label'     => esc_html__( 'Navigation', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->start_controls_tabs( 'wfp_fundrising_arrows_tabs' );
		$this->start_controls_tab(
			'wfp_fundrising_arrows_prev_icon_tab',
			array(
				'label' => esc_html__( 'Previous', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_fundrising_arrows_prev_icons',
			array(
				'label'            => esc_html__( 'Icon', 'wp-fundraising' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'wfp_fundrising_arrows_prev_icon',
				'default'          => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'solid',
				),
				// 'condition' => [
				// 'wfp_fundrising_tab_cart_icon_switch' => 'yes'
				// ]
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wfp_fundrising_arrows_next_icon_tab',
			array(
				'label' => esc_html__( 'Next', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_fundrising_arrows_next_icons',
			array(
				'label'            => esc_html__( 'Icon', 'wp-fundraising' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'wfp_fundrising_arrows_next_icon',
				'default'          => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'solid',
				),
				// 'condition' => [
				// 'wfp_fundrising_tab_cart_icon_switch' => 'yes'
				// ]
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'wfp_fundrising_arrows_size',
			array(
				'label'     => esc_html__( 'Arrows Size', 'wp-fundraising' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .wfp_fundrising-slidenav-container .wfp_fundrising-slidenav'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wfp_fundrising-slidenav-container .wfp_fundrising-slidenav svg' => 'max-width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_arrows_space',
			array(
				'label'      => esc_html__( 'Space', 'wp-fundraising' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wfp_fundrising-wc-carousel .wfp_fundrising-navigation-prev' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .wfp_fundrising-wc-carousel .wfp_fundrising-navigation-next' => 'margin-left: {{SIZE}}px;',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'wfp_fundrising_navigation',
							'value' => 'both',
						),
						array(
							'name'     => 'wfp_fundrising_both_position',
							'operator' => '!=',
							'value'    => 'center',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundrising_arrows_margin',
			array(
				'label'      => esc_html__( 'Margin', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}  .wfp_fundrising-slidenav-container .wfp_fundrising-slidenav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'wfp_fundrising_arrows_color_tabs' );
		$this->start_controls_tab(
			'wfp_fundrising_arrows_color_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'wp-fundraising' ),
			)
		);
		$this->add_control(
			'wfp_fundrising_arrows_color',
			array(
				'label'     => esc_html__( 'Arrows Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .wfp_fundrising-slidenav-container .wfp_fundrising-slidenav'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .wfp_fundrising-slidenav-container .wfp_fundrising-slidenav svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wfp_fundrising_arrows_color_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_fundrising_arrows_hover_color',
			array(
				'label'     => esc_html__( 'Arrows Hover Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .wfp_fundrising-slidenav-container .wfp_fundrising-slidenav:hover'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .wfp_fundrising-slidenav-container .wfp_fundrising-slidenav:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'wfp_fundrising-woo-carousel-dots',
			array(
				'label'     => esc_html__( 'Dots', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_dots_size',
			array(
				'label'     => esc_html__( 'Dots Size', 'wp-fundraising' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 5,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination .swiper-pagination-bullet ' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'dots', 'both' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'wfp_fundrising_dots_space',
			array(
				'label'     => esc_html__( 'Dots Space', 'wp-fundraising' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),

				'selectors' => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination .swiper-pagination-bullet ' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundrising_dots_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundrising_dots_margin',
			array(
				'label'      => esc_html__( 'Margin', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundrising_dots_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 100,
					'right'  => 100,
					'bottom' => 100,
					'left'   => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'wfp_fundrising_navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'ekti_dots_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'wp-fundraising' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->start_controls_tabs( 'wfp_fundrising_dots_color_tabs' );
		$this->start_controls_tab(
			'wfp_fundrising_dots_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_fundrising_dots_color',
			array(
				'label'     => esc_html__( 'Dots Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'dots', 'both' ),
				),
				'separator' => 'after',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wfp_fundrising_dots_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_fundrising_dots_hover_color',
			array(
				'label'     => esc_html__( 'Dots Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination .swiper-pagination-bullet:hover, {{WRAPPER}} .wfp_fundrising-swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'dots', 'both' ),
				),
				'separator' => 'after',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wfp_fundrising_dots_active_tab',
			array(
				'label' => esc_html__( 'Active', 'wp-fundraising' ),
			)
		);

		$this->add_control(
			'wfp_fundrising_active_dot_color',
			array(
				'label'     => esc_html__( 'Active Dots Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wfp_fundrising-swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
				),
				'default'   => '#425AF8',
				'condition' => array(
					'wfp_fundrising_navigation' => array( 'dots', 'both' ),
				),
				'separator' => 'after',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		// end navigation style controls

		// Filter
		$this->start_controls_section(
			'wfp_fundraising_filter',
			array(
				'label'     => esc_html__( 'Filter', 'wp-fundraising' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'wfp_fundraising_content__filter_enable' => 'Yes' ),
			)
		);

		// typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wfp_fundraising_filter__typography',
				'selector' => '{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item',
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_filter__padding',
			array(
				'label'      => esc_html__( 'Padding', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wfp_fundraising_filter__margin',
			array(
				'label'      => esc_html__( 'Margin', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_filter__border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'wp-fundraising' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'wfp_fundraising_filter__alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'wp-fundraising' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'wp-fundraising' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .wfp-campaign-filter-nav' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->start_controls_tabs(
			'wfp_fundraising_filter__tabs'
		);
		$this->start_controls_tab(
			'wfp_fundraising_filter__tab',
			array(
				'label' => esc_html__( 'Normal', 'wp-fundraising' ),
			)
		);
		// color
		$this->add_control(
			'wfp_fundraising_filter__color',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_filter__bg',
				'label'    => esc_html__( 'Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'wfp_fundraising_filter__tab2',
			array(
				'label' => esc_html__( 'Active', 'wp-fundraising' ),
			)
		);
		// color
		$this->add_control(
			'wfp_fundraising_filter__color_active',
			array(
				'label'     => esc_html__( 'Color', 'wp-fundraising' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item.active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wfp_fundraising_filter__bg_active',
				'label'    => esc_html__( 'Background', 'wp-fundraising' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wfp-campaign-filter-nav .wfp-campaign-filter-nav-item.active',
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		// end Filter
	}

	// render files
	protected function render() {

		$settings = $this->get_settings_for_display();
		extract( $settings );

		// campaign default blog

		include \WFP_Fundraising::plugin_dir() . 'country-module/country-info.php';

		/*currency information*/
		$getMetaGeneralOp = get_option( \WfpFundraising\Apps\Settings::OK_GENERAL_DATA );
		$getMetaGeneral   = isset( $getMetaGeneralOp['options'] ) ? $getMetaGeneralOp['options'] : array();

		$defaultCurrencyInfo = isset( $getMetaGeneral['currency']['name'] ) ? $getMetaGeneral['currency']['name'] : 'US-USD';
		$explCurr            = explode( '-', $defaultCurrencyInfo );
		$currCode            = isset( $explCurr[1] ) ? $explCurr[1] : 'USD';
		$countCode           = isset( $explCurr[0] ) ? $explCurr[0] : 'US';
		$symbols             = isset( $countryList[ $countCode ]['currency']['symbol'] ) ? $countryList[ $countCode ]['currency']['symbol'] : '';
		$symbols             = strlen( $symbols ) > 0 ? $symbols : $currCode;
		$symbols             = apply_filters( 'wfp_donate_amount_symbol', $symbols, $countryList, $countCode );

		$defaultUse_space = isset( $getMetaGeneral['currency']['use_space'] ) ? $getMetaGeneral['currency']['use_space'] : 'off';

		// main campaign page
		include \WFP_Fundraising::plugin_dir() . 'views/public/fundraising/dynamic-page/campaign-elementor.php';
	}
}
