<?php
namespace AdsBanners;

class CPT
{
	protected static $labels = array(
		
	);
	public static function register()
	{
		self::initLabels();

		add_action( "init", array( "AdsBanners\CPT", "RegisterAds" ) );
	}
	protected static function initLabels()
	{
		self::$labels = array(
			'name' 			=> __( 'Ads' ),
		    'singular_name' => __( 'Ad' ),
		    'menu_name'     => __( 'Ads Banners' ),
		    'add_new_item'	=> __( 'Add new ad' ),
		    'add_new'	    => __( 'New advertising' )
		);
	}
	public static function registerAds()
	{
		register_post_type( 'ads-banners',
    		// CPT Options
	        array(
	            'labels' => self::$labels,
	            'show_in_rest' => true,
	            'public' => true,
	            'has_archive' => true,
	            'rewrite' => array( 'slug' => 'ads-banners' ),
	            'supports' => array( 'title', 'editor' ),
	            'show_ui' => true,
	            'menu_position' => 5,
	            'show_in_menu' => true,
	            'show_in_nav_menus' => true
	        )
	    );
	}
}