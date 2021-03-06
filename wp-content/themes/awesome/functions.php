<?php

//Add mobile sidebar
function awesome_2014_mobile_widget_area() {
	register_sidebar( array(
		'name'			=> __( 'Mobile Sidebar', 'awesome_2014' ),
		'id'			=> 'sidebar-mobile',
		'description'	=> __( 'Slideout sidebar for mobile devices.', 'awesome_2014' ),
		'before_widget'	=> '<aside id="%1$s" class="widget %2$s mobile-widget">',
		'after_widget'	=> '</aside>',
		'before_title'	=> '<h1 class="widget-title">',
		'after_title'	=> '</h1>',
		)
	);
}
add_action( 'widgets_init', 'awesome_2014_mobile_widget_area' );

//add mobile menu
function awesome_2014_setup() {
	register_nav_menus( array(
		'mobile' => __( 'Mobile menu in left sidebar', 'awesome_2014' ),
		)
	);
}
add_action( 'after_setup_theme', 'awesome_2014_setup' );

/**
* Add slideout Sidebar
*/
// Enqueue the css/js for slideout
function awesome_2014_add_slideout() {
	wp_enqueue_script( 'sidr', get_stylesheet_directory_uri().'/js/sidr/jquery.sidr.min.js', array('jquery'), null, true );
	wp_enqueue_script( 'slideout', get_stylesheet_directory_uri().'/js/slideout.js', array('sidr'), null, true );
	wp_enqueue_style( 'slideout', get_stylesheet_directory_uri().'/js/sidr/stylesheets/jquery.sidr.dark.css');
}

// add only if is mobile
if ( wp_is_mobile() ) {
	add_action( 'wp_enqueue_scripts', 'awesome_2014_add_slideout', 10 );
}

//enqueue topbar js
function awesome_2014_add_topbarjs() {
	wp_enqueue_script( 'topbarjs', get_stylesheet_directory_uri().'/js/topbar.js', array('jquery'), null, true );
}
//add if is not mobile
if ( !wp_is_mobile() ) {
	add_action( 'wp_enqueue_scripts', 'awesome_2014_add_topbarjs' );
}

//enqueue mobile topbar js
function awesome_2014_add_mobile_topbarjs() {
	wp_enqueue_script( 'mobile-topbarjs', get_stylesheet_directory_uri().'/js/topbar-mobile.js', array('jquery'), null, true );
}
//add if is mobile
if ( wp_is_mobile() ) {
	add_action( 'wp_enqueue_scripts', 'awesome_2014_add_mobile_topbarjs' );
}

function awesome_2014_customize_register() {

	global $wp_customize;

	//add extended featured content section

	//add controls
	$wp_customize->add_setting( 'num_posts_grid', array( 'default' => '6' ) );
	$wp_customize->add_setting( 'num_posts_slider', array( 'default' => '6' ) );
	$wp_customize->add_setting( 'layout_mobile', array( 'default' => 'grid' ) );

	$wp_customize->add_control( 'num_posts_grid', array(
		'label' => __( 'Number of posts for grid', 'text-domain'),
		'section' => 'featured_content',
		'settings' => 'num_posts_grid',
	) );

	$wp_customize->add_control( 'num_posts_slider', array(
		'label' => __( 'Number of posts for slider', 'text-domain'),
		'section' => 'featured_content',
		'settings' => 'num_posts_slider',
	) );

	$wp_customize->add_control( 'layout_mobile', array(
		'label' => __( 'Layout for mobile devices', 'text-domain'),
		'section' => 'featured_content',
		'settings' => 'layout_mobile',
		'type' => 'select',
		'choices' => array(
			'grid' => 'Grid',
			'slider' => 'Slider',
		),
	) );
}
add_action( 'customize_register', 'awesome_2014_customize_register' );

function awesome_2014_theme_mod( $value ) {
	if ( wp_is_mobile() ){
		return get_theme_mod( 'layout_mobile', 'grid' );
	}
	else {
		return $value;
	}
}
add_filter( 'theme_mod_featured_content_layout', 'awesome_2014_theme_mod' );

function awesome_2014_get_featured_posts( $posts ){
	$fc_options = (array) get_option( 'featured-content' );

	if ( $fc_options ) {
		$tag_name = $fc_options['tag-name'];
	}
	else {
		$tag_name = 'featured';
	}

	$layout = get_theme_mod( 'featured_content_layout' );
	$max_posts = get_theme_mod( 'num_posts_' . $layout, 2 );

	$args = array(
			'numberposts' => $settings['quantity'],
			'tax_query'   => array(
				array(
					'field'    => 'term_id',
					'taxonomy' => 'post_tag',
					'terms'    => $tag,
				),
			),
		);

	$new_post_array = get_posts( $args );

	if ( count($new_post_array) > 0 ) {
		return $new_post_array;
	}
	else {
		return $posts;
	}
}
add_filter( 'twentyfourteen_get_featured_posts', 'awesome_2014_get_featured_posts', 999, 1 );


//dequeue/enqueue scripts
function awesome_2014_featured_content_scripts() {
	wp_dequeue_script( 'twentyfourteen-script' );
	wp_dequeue_script( 'twentyfourteen-slider' );
	
	wp_enqueue_script( 'awesome_2014-script', get_stylesheet_directory_uri() . '/js/functions.js', array( 'jquery' ), '' , true );
	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		wp_enqueue_script( 'awesome_2014-slider', get_stylesheet_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery', 'awesome_2014-script' ), '' , true );
		wp_localize_script( 'awesome_2014-slider', 'featuredSliderDefaults', array(
			'prevText' => __( 'Previous', 'awesome_2014' ),
			'nextText' => __( 'Next', 'awesome_2014' )
		) );
	}
}
add_action( 'wp_enqueue_scripts' , 'awesome_2014_featured_content_scripts' , 999 );

?>