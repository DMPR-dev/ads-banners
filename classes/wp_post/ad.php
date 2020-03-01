<?php

namespace AdsBanners;

class Ad
{
	public $ID;

	public $wp_post;

	public $location;

	public $ad_width;

	public $ad_height;

	public function __construct( $id )
	{
		$this->init( $id );
	}

	protected function init( $id )
	{
		$this->ID = $id;

		$this->wp_post = get_post( $id );

		$this->location = get_post_meta( $this->ID, "location", true );

		$categories_linked = json_decode( get_post_meta( $this->ID, "categories_linked", true ) );
		$this->categories_linked = is_null( $categories_linked ) ? array() : $categories_linked;

		$posts_linked = json_decode( get_post_meta( $this->ID, "posts_linked", true ) );
		$this->posts_linked = is_null( $posts_linked ) ? array() : $posts_linked;

		$ad_width = get_post_meta( $this->ID, "ad_width", true );
		$this->ad_width = strlen( $ad_width ) === 0 ? "100%" : $ad_width;

		$ad_height = get_post_meta( $this->ID, "ad_height", true );
		$this->ad_height = strlen( $ad_height ) === 0 ? "auto" : $ad_height;
	}
	/*
		Static methods
	*/
	public static function register()
	{
		add_action( "article-sidebar-ads", function() {
			Ad::renderArticleSideBarAd();
		} );
		add_action( "article-content-ads", function() {
			Ad::renderArticleContentAd();
		} );
		add_action( "article-popup-ads", function() {
			Ad::renderArticlePopupAd();
		} );
		add_action( "home-popup-ads", function() {
			Ad::renderHomePopupAd();
		} );
		add_action( "home-content-ads", function() {
			Ad::renderHomeContentAd();
		} );

		$custom_locations = explode( ',', get_option( 'ads-banners-custom-locations' ) );

		if( is_array( $custom_locations ) )
		{
			foreach( $custom_locations as $location )
			{
				add_action( "print-custom-ad-" . $location , function() use ( $location ) {
					echo Ad::getAd( $location );
				} );
			}
		}
	}
	public static function getAd( $location, $only_array = false )
	{
		$ads_query = new \WP_Query( array( 
			"post_type"		=> "ads-banners",
			"post_status"	=> "publish",
			"meta_query"	=> array(
				array(
					"key"	  => "location",
					"value"	  => $location,
					"compare" => "="
				)
			),
			"posts_per_page" => -1
		) );
		wp_reset_postdata();

		if( !$only_array )
		{
			return self::displayAd( self::prioritizeAd( $ads_query->posts ) );
		}
		return self::prioritizeAd( $ads_query->posts );
	}
	protected static function displayAd( $ads = array() )
	{
		ob_start();

		if( is_array( $ads ) && sizeof( $ads ) > 0 )
		{
			$ad = new Ad( $ads[0]->ID );
			self::renderAdContent( $ad );
		}

		return ob_get_clean();
	}
	protected static function renderAdContent( $ad )
	{
		?>
		<div style="width: <?php echo $ad->ad_width; ?>; height: <?php echo $ad->ad_height; ?>">
			<?php
				echo apply_filters( "the_content", $ad->wp_post->post_content );
			?>
		</div>
		<?php
	}
	protected static function prioritizeAd( $ads = array() )
	{
		if( is_array( $ads ) && sizeof( $ads ) > 0 )
		{
			$ads = self::prioritizeAdByPostAndCategory( $ads );
		}
		return array_values( $ads );
	}
	protected static function getPostPrioritizedAd( $ads = array() )
	{
		global $post;

		if( is_array( $ads ) && sizeof( $ads ) > 0 && !is_null( $post ) )
		{
			if( property_exists( $post , "ID" ) )
			{
				foreach ( $ads as $key => $single_ad )
				{
					$ad = new Ad( $single_ad->ID );

					if( is_array( $ad->posts_linked ) && sizeof( $ad->posts_linked ) > 0 && in_array( $post->ID, $ad->posts_linked ) )
					{
						return $single_ad;
					}
				}
			}
		}
		return null;
	}
	protected static function getCategoryPrioritizedAds( $ads = array() )
	{
		global $post;

		$prioritized_ads = array();

		if( is_array( $ads ) && sizeof( $ads ) > 0 && !is_null( $post ) )
		{
			if( property_exists( $post , "ID" ) )
			{
				foreach ( $ads as $key => $single_ad )
				{
					$ad = new Ad( $single_ad->ID );

					$categories = wp_get_post_categories( $post->ID );

					$categories_intersect = array_intersect( $categories, $ad->categories_linked );

					if( is_array( $categories_intersect ) && sizeof( $categories_intersect ) > 0 )
					{
						array_push( $prioritized_ads, $single_ad ); 
					}
				}
			}
		}
		return $prioritized_ads;
	}
	protected static function prioritizeAdByPostAndCategory( $ads = array() )
	{
		global $post;

		$current_post_prioritized_ad = self::getPostPrioritizedAd( $ads );

		$current_categories_prioritized_ads = self::getCategoryPrioritizedAds( $ads );

		if( is_array( $ads ) && sizeof( $ads ) > 0 && !is_null( $post ) )
		{
			if( property_exists( $post , "ID" ) )
			{
				foreach ( $ads as $key => $single_ad )
				{
					if( property_exists( $single_ad, "ID" ) )
					{
						$ad = new Ad( $single_ad->ID );

						if( 
							( 
								( !in_array( $single_ad, $current_categories_prioritized_ads ) && sizeof( $current_categories_prioritized_ads ) > 0 
								)
								||
								(
									sizeof( $current_categories_prioritized_ads ) === 0 && sizeof( $ad->categories_linked ) > 0
								)
							) 
							&& 
							( is_object( $current_post_prioritized_ad ) && property_exists( $current_post_prioritized_ad, "ID" ) 
								&& $single_ad->ID !== $current_post_prioritized_ad->ID 
								|| is_null( $current_post_prioritized_ad ) 
							)
						)
						{
							unset( $ads[$key] );
						}
						/*
							Post prioritization
						*/
						if( is_array( $ad->posts_linked ) && sizeof( $ad->posts_linked ) > 0 && ! in_array( $post->ID, $ad->posts_linked ) )
						{
							unset( $ads[$key] );
						}

						if( is_object( $current_post_prioritized_ad ) && property_exists( $current_post_prioritized_ad, "ID" ) && $single_ad->ID !== $current_post_prioritized_ad->ID )
						{
							unset( $ads[$key] );
						}
					}
					else
					{
						unset( $ads[$key] );
					}
				}
			}
		}
		return $ads;
	}
	protected static function setPopupCookie()
	{
		if( !isset( $_COOKIE["ads-banners-popup-shown"] ) )
		{
			?>
			<script>
				var d = new Date();
				d.setTime( d.getTime() + ( 3600 * 3 * 1000 ) );
				var expires = "expires="+ d.toUTCString();
				document.cookie = "ads-banners-popup-shown=1; " + expires + "; path=/;";
			</script>
			<?php
		}
	}
	/*
		Renderers
	*/
	public static function renderArticleSideBarAd()
	{
		echo Ad::getAd( "article-sidebar" );
	}
	public static function renderArticleContentAd()
	{
		echo Ad::getAd( "article-content" );
	}
	public static function renderArticlePopupAd()
	{
		if( !isset( $_COOKIE["ads-banners-popup-shown"] ) )
		{
			echo Ad::renderPopup( Ad::getAd( "article-popup", true ) );
		}
	}
	public static function renderHomePopupAd()
	{
		if( !isset( $_COOKIE["ads-banners-popup-shown"] ) )
		{
			echo Ad::renderPopup( Ad::getAd( "home-popup", true ) );
		}
	}
	public static function renderHomeContentAd()
	{
		echo Ad::getAd( "home-content" );
	}
	public static function renderPopup( $ads = array() )
	{
		if( sizeof( $ads ) === 0 ) return '';

		self::setPopupCookie();

		ob_start();
		?>
			<div class="ads-banners-popup-container" style="visibility: hidden">
				<div class="ads-banners-popup">
					<span class="ads-banners-popup-close-btn">
						x
					</span>
					<div class="p-4" style="width: 100%; height: 100%;">
						<?php
							echo self::displayAd( $ads );
						?>
					</div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}
}