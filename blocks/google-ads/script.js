
if( typeof ads_banners === 'undefined' )
{
	var ads_banners = {
		loadGoogleAdsScript : function () {
			if( jQuery( '#ads-banner-google-ads-script' ).length == 0 )
			{
				var script = document.createElement( 'script' );
				script.id = 'ads-banner-google-ads-script';
				script.async = true;
				script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
				(document.getElementsByTagName( 'head' )[0] || document.getElementsByTagName( 'body' )[0] ).appendChild( script );
			}
		},
		initializeGoogleAds : function () {
			var init = function(){
				try
				{
					for (var i = jQuery( '.adsbygoogle' ).length - 1; i >= 0; i--) {
						( adsbygoogle = window.adsbygoogle || [] ).push( {} ); 
					}
				} 
				catch 
				{

				};
	            jQuery( document ).unbind( 'touchstart', init );
	            jQuery( window ).unbind( 'scroll', init );
			};

			if( jQuery( window ).width() < 1024 )
	        {
	            jQuery( document ).on( 'touchstart', init );
	            jQuery( window ).on( 'scroll', init );
	        }
	        else
	        {
	            init();
	        }
		},
		isGutenbergActive : function () {
			return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
		}
	};
}

( function() {
	/*
		Initialize google ads
	*/
	var interval_iterations = 0;
	var interval = setInterval( function() { 
		if ( ads_banners.isGutenbergActive() === false && jQuery( '.adsbygoogle' ).length > 0 )
		{
			clearInterval( interval );

			ads_banners.loadGoogleAdsScript();

			ads_banners.initializeGoogleAds();
		}
		if( interval_iterations > 5 )
		{
			clearInterval( interval );
		}
		interval_iterations++;
	}, 500);
} )();