<?php

/*
	Block Name: Google Ads blocks
*/
function adsBannersRegisterGoogleAdsBlock()
{
	wp_register_script(
        'ads-banners-google-ads-frontend-script',
        plugins_url( 'script.js', __FILE__ ),
        array( 'jquery' )
    );

    wp_register_script(
        'ads-banners-google-ads-script',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element' )
    );

    wp_localize_script(
        'ads-banners-google-ads-script',
        'gads_block', 
        array(
            'icon'      =>  plugins_url() . '/' . basename( plugin_dir_path(  dirname( __FILE__ , 2 ) ) ) . '/assets/img/google-ad-icon.png'
        )

    );
 
    register_block_type( 'ads-banners/google-ads', array(
        'editor_script' => 'ads-banners-google-ads-script',
        'script'		=> 'ads-banners-google-ads-frontend-script'
    ) );
 
}


add_action( 'init', 'adsBannersRegisterGoogleAdsBlock' );

/*
    Make sure our google ads front-end script doesn't slow down the page, just load it in async mode
*/
function adsBannersAsyncGoogleAdsScript( $tag, $handle ) 
{   
    if ( 'ads-banners-google-ads-frontend-script' === $handle ) 
    {
        return str_replace( ' src', ' async defer="defer" src', $tag );
    }
    return $tag;
}

add_filter( 'script_loader_tag', 'adsBannersAsyncGoogleAdsScript', 10, 2 );