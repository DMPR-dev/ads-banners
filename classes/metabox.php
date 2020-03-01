<?php
namespace AdsBanners;

require_once plugin_dir_path( __FILE__ ) . '/inputs.php'; 

class MetaBox
{
	protected static $boxes = array(
		
	);
	public static function register()
	{
		self::InitBoxes();

		add_action( "add_meta_boxes", "AdsBanners\MetaBox::registerBoxes", 10, 2);

		add_action( "save_post", "AdsBanners\MetaBox::saveBoxValues", 10, 2);
	}
	/*
		Initialize a list of metaboxes
	*/
	protected static function initBoxes()
	{
		$locations = array(
			"none"					=> "None",
			"article-content" 		=> "Article - Content", 
			"article-sidebar"		=> "Article - Sidebar",
			"article-popup"			=> "Article - Popup",
			"home-content" 			=> "Home - Content",
			"home-popup" 			=> "Home - Popup"
		);

		$custom_locations = explode( ',', get_option( 'ads-banners-custom-locations' ) );

		if( is_array( $custom_locations ) )
		{
			foreach( $custom_locations as $location )
			{
				$location = str_replace( ' ', '', $location );

				if( strlen( $location ) == 0 ) continue;

				$locations[$location] = ucfirst( $location ); 
			}
		}

		self::$boxes = array(
			array(
				"post_type" => "ads-banners",
				"id"		=> "ads-banners-location",
				"label"		=> __( "Location" ),
				"location"	=> "side",
				"priority"	=> "high",
				"fields" 	=> array(
					array(
						"name" 		=> "location",
						"label" 	=> __( "Location of ad" ) . ":",
						"values"	=> $locations,
						"type"		=> "select"
					)
				)
			),
			array(
				"post_type" => "ads-banners",
				"id"		=> "ads-banners-size-properties",
				"label"		=> __( "Ad size properties" ),
				"location"	=> "side",
				"priority"	=> "high",
				"fields" 	=> array(
					array(
						"name" 		=> "ad_width",
						"label" 	=> __( "Width" ) . ":",
						"type"		=> "text"
					),
					array(
						"name" 		=> "ad_height",
						"label" 	=> __( "Height" ) . ":",
						"type"		=> "text"
					)
				)
			),
			array(
				"post_type" => "ads-banners",
				"id"		=> "ads-banners-categories",
				"label"		=> __( "Categories Linked" ),
				"location"	=> "side",
				"priority"	=> "low",
				"fields" 	=> array(
					array(
						"name" 		=> "categories_linked",
						"label" 	=> __( "Categories" ) . ":",
						"type"		=> "categories_linked"
					)
				)
			),
			array(
				"post_type" => "ads-banners",
				"id"		=> "ads-banners-posts",
				"label"		=> __( "Posts Linked" ),
				"location"	=> "side",
				"priority"	=> "low",
				"fields" 	=> array(
					array(
						"name" 		=> "posts_linked",
						"label" 	=> __( "Posts" ) . ":",
						"type"		=> "posts_linked"
					)
				)
			)
		);
	}
	public static function saveBoxValues( $post_id, $post )
	{
		foreach( self::$boxes as $box )
		{
			if( $post->post_type !== $box["post_type"] ) continue;

			foreach( $box["fields"] as $field )
			{
				if( isset( $field["type"] ) && $field["type"] === 'spoiler' )
				{
					$contents = $field["contents"];

					foreach( $contents as $field_content )
					{
						self::saveMetaData( $post_id, $field_content );
					}
				}
				else
				{
					self::saveMetaData( $post_id, $field );
				}
			}
		}
	}
	protected static function saveMetaData( $post_id, $field = array() )
	{
		if( isset( $_REQUEST[$field["name"]] ))
		{
			if( !update_post_meta( $post_id, $field["name"], sanitize_text_field( $_REQUEST[$field["name"]] ) ) )
			{
				add_post_meta( $post_id, $field["name"], sanitize_text_field( $_REQUEST[$field["name"]] ), true );
			}
		}
	}
	public static function registerBoxes( $post_type, $post )
	{
		foreach( self::$boxes as $box )
		{
			$fields = $box["fields"];
			$post_id = $post->ID;
			$post_type = $box["post_type"];

			$box_cb = function( ) use ( $post_id, $fields ) {
				self::boxCallback( $post_id, $fields );
			};

			add_meta_box( $box["id"], $box["label"], $box_cb, $box["post_type"], $box["location"], isset( $box["priority"] ) ? $box["priority"] : "default" );
		}
	}
	/*
		Callback for metabox
	*/
	public static function boxCallback( $post_id, $fields, $get_data = 'get_post_meta' )
	{
		if(is_array( $fields ) && sizeof( $fields ) > 0)
		{
			foreach( $fields as $field )
			{
				switch ( $field["type"] ) {
					case 'text':
						echo Inputs::TextInput( $post_id , $field["name"], $field["label"], $get_data );
						break;
					case 'select':
						echo Inputs::SelectInput( $post_id , $field["name"], $field["label"], $field["values"], $get_data );
						break;
					case 'categories_linked':
						echo Inputs::CategoriesListInput( $post_id , $field["name"], $field["label"] );
						break;
					case 'posts_linked':
						echo Inputs::PostsListInput( $post_id , $field["name"], $field["label"] );
						break;

					default:
						# code...
						break;
				}
			}
		}
	}	
}