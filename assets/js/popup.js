( function() {
	if( jQuery( '#ads-banner-popup-style' ).length == 0 )
	{
		var style = document.createElement( 'link' );
		style.id = 'ads-banner-popup-style';
		style.rel = 'stylesheet';
		style.href = popup.style_url;
		(document.getElementsByTagName( 'head' )[0] || document.getElementsByTagName( 'body' )[0] ).appendChild( style );
	}
	jQuery( function( $ ) {
		$( 'body' ).on( 'click', 'span.ads-banners-popup-close-btn', function(){
			$( 'div.ads-banners-popup-container' ).animate( { opacity : 0 }, 350, function() {
				$( 'div.ads-banners-popup-container' ).remove();
			} );
		} );

		var initPopup = function(){
			setTimeout( function() {
				$( 'div.ads-banners-popup-container' ).css( 'opacity', 0 );
				$( 'div.ads-banners-popup-container' ).css( 'display', 'flex' );
				$( 'div.ads-banners-popup-container' ).css( 'visibility', 'visible' );
				$( 'div.ads-banners-popup-container' ).css( 'pointer-events', 'all' );
				$( 'div.ads-banners-popup-container' ).css( 'z-index', '99999999' );
				$( 'div.ads-banners-popup-container' ).animate( {opacity : 1 }, 500 );
			}, 4000 );

			jQuery( document ).unbind( 'touchstart', initPopup );
	        jQuery( window ).unbind( 'scroll', initPopup );

	        var img_figcaption = $( "div.ads-banners-popup-container .ads-banners-popup div.wp-block-image > figure > figcaption" );
			if( $( img_figcaption ).height() == 0 )
			{
				$( img_figcaption ).remove();
			}

	        initPopup = function() {};
		};

		if( jQuery( window ).width() < 1024 )
        {
            jQuery( document ).on( 'touchstart', initPopup );
            jQuery( window ).on( 'scroll', initPopup );
        }
        else
        {
            initPopup();
        }
	} );
} )();