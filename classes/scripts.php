<?php

namespace AdsBanners;

class Scripts
{
	protected static $plugin_url;

	public static function register()
	{
		self::$plugin_url = plugins_url() . '/' . basename( plugin_dir_path(  dirname( __FILE__ , 1 ) ) );

		add_action( "admin_enqueue_scripts", "AdsBanners\Scripts::registerAdminScripts" );

		add_action( "enqueue_block_editor_assets", "AdsBanners\Scripts::registerEditorScripts" );

		add_action( "wp_enqueue_scripts", "AdsBanners\Scripts::registerFrontEndScripts" );
	}

	public static function registerAdminScripts()
	{

	}

	public static function registerFrontEndScripts()
	{
		self::registerPopUpScript();
	}
	public static function registerEditorScripts()
	{
		global $post;

		if( !property_exists( $post, "post_type" ) )
		{
			return;
		}

		self::registerBlockHiderScript();

		/*
			Scripts only for 'ads-banners' post type
		*/
		if( $post->post_type !== "ads-banners" ) return;

		self::registerCategoriesInputScript();
		
		self::registerPostsInputScript();
	}
	/*
		Editor scripts
	*/
	protected static function registerCategoriesInputScript()
	{
		wp_enqueue_script( "categories-input-script", self::$plugin_url . '/assets/admin/js/categories-input.js' );

		wp_localize_script( "categories-input-script", "ads_categories_translations", array(
			"no_categories" => __( "No categories linked." )
		) );
	}

	protected static function registerPostsInputScript()
	{
		wp_enqueue_script( "posts-input-script", self::$plugin_url . '/assets/admin/js/posts-input.js' );

		wp_localize_script( "posts-input-script", "ads_posts_translations", array(
			"no_posts" => __( "No posts linked." )
		) );
	}

	protected static function registerBlockHiderScript()
	{
		wp_enqueue_script(
	        'ads-banners-google-ads-block-hider',
	       	self::$plugin_url . '/blocks/google-ads/hide-block.js',
	       	array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' )
	    );
	}
	/*
		Front-end scripts
	*/
	protected static function registerPopUpScript()
	{
		wp_enqueue_script(
	        'ads-banners-popup',
	       	self::$plugin_url . '/assets/js/popup.js',
			array( "jquery" ),
	       	true,
	       	'1.0.0',
	       	true
	    );

	    wp_localize_script( 
	    	'ads-banners-popup',
	    	'popup',
	    	array(
	    		'style_url' => self::$plugin_url . '/assets/css/popup.css'
	    	)
		);
	}
}