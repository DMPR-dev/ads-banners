<?php

namespace AdsBanners;

class Styles
{
	protected static $plugin_url;

	public static function register()
	{
		self::$plugin_url = plugins_url() . '/' . basename( plugin_dir_path(  dirname( __FILE__ , 1 ) ) );

		add_action( "admin_enqueue_scripts", "AdsBanners\Styles::registerAdminStyles" );

		add_action( "enqueue_block_editor_assets", "AdsBanners\Styles::registerEditorStyles" );
	}

	public static function registerAdminStyles()
	{
		self::registerSettingsStyles();
	}

	public static function registerEditorStyles()
	{
		global $post;

		if( property_exists( $post, "post_type" ) )
		{
			if( $post->post_type !== "ads-banners" ) return;
		}

		self::registerCategoriesInputStyle();
		
		self::registerPostsInputStyle();
	}

	protected static function registerCategoriesInputStyle()
	{
		wp_enqueue_style( "categories-input-style", self::$plugin_url . '/assets/admin/css/categories-input.css' );
	}

	protected static function registerPostsInputStyle()
	{
		wp_enqueue_style( "posts-input-style", self::$plugin_url . '/assets/admin/css/posts-input.css' );
	}

	protected static function registerSettingsStyles()
	{
		if( strpos( get_current_screen()->base, 'ads-banners-settings' ) !== FALSE )
		{
			wp_enqueue_style( "adsbanners-settings-style", self::$plugin_url . '/assets/admin/css/settings.css' );
		}
	}
}