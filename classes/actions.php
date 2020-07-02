<?php

namespace AdsBanners;

class Actions
{
	public static function register()
	{
		add_action( 'wp_head', 'AdsBanners\Actions::unpublishOnDate' );
	}

	public static function unpublishOnDate()
	{
		global $wpdb;

		$prev_month = date( 'Y-m-d', strtotime( '-1 month' ) );
		$today = date( 'Y-m-d' );

		$query = "
			SELECT * FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->postmeta.meta_key = 'unpublish-date' AND $wpdb->postmeta.meta_value <= '$today' AND $wpdb->posts.post_type = 'ads-banners' AND $wpdb->postmeta.meta_value >= '$prev_month' LIMIT 0, 50
		";

		$results = $wpdb->get_results( 
			$query
		);

		if( is_array( $results ) && sizeof( $results ) > 0 )
		{
			foreach( $results as $ad )
			{
				wp_update_post( array(
					'ID' 			=> $ad->ID,
					'post_status'	=> 'draft'
				) );
			}
		}
	}
}