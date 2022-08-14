<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */


if ( ! function_exists( 'twentytwentytwo_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

	}

endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

function create_posttype() {
// CPT Options

$args = array(
  'labels' => array(
   'name' => __( 'movies' ),
   'singular_name' => __( 'movies' )
  ),
  'public' => true,
  'has_archive' => false,
  'rewrite' => array('slug' => 'movies'),
 );

add_post_type_support('movies', 'thumbnail');

register_post_type('movies', $args); 

}
// Hooking up our function to theme setup

add_action('cmb2_admin_init', 'register_post_custom_meta');
function register_post_custom_meta() {
  $cmb = new_cmb2_box(array(
    'id' => 'data_movies',
    'title' => 'Tambah Data Movies',

    // menentukan meta box akan tampil di konten apa
    // apakah di 'post', 'page', atau custom post lain nya
    'object_types' => array('movies')
  ));

  // add reguler text
  $cmb->add_field(array(
    'id' => 'year',
    'name' => 'Year',
    'desc' => '',
    'type' => 'text_date',
    'timezone_meta_key' => 'wiki_test_timezone',
    'date_format' => 'Y'
  ));
   $cmb->add_field(array(
    'id' => 'duration',
    'name' => 'Duration',
    'desc' => '',
    'type' => 'text_time',
    'attributes' => array(
       'data-timepicker' => json_encode( array(
            'timeOnlyTitle' => __( 'Choose your Time', 'cmb2' ),
           'timeFormat' => 'HH:mm',
          'stepMinute' => 1, // 1 minute increments instead of the default 5
       ) ),
    ),
   ));
   $cmb->add_field(array(
    'id' => 'genres',
    'name' => 'Genres',
    'desc' => '',
    'type' => 'radio',
    'options' => array(
        'fight' => __('Fighting', 'cmb2'),
        'love' => __('Love', 'cmb2'),
        'action' => __('Action', 'cmb2'),
        'advanture' => __('Advanture', 'cmb2'),
        'comedy' => __('Comedy', 'cmb2'),
        'horor' => __('Horor', 'cmb2')
    )
   )); 
}

add_action( 'init', 'create_posttype' );


add_action('movies', function(){
    $args = array(
        'post_type' => 'movies',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post();
        print the_title();
        the_excerpt();
     endwhile;

     wp_reset_postdata(); 

});
