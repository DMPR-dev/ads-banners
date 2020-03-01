jQuery( function( $ ) {
	$( "button.add-post-btn" ).click( function() {
		var current_posts = JSON.parse( $( this ).parent().find( "input.posts-hidden-input" ).val() );

		var selected_post_id = $( this ).parent().find( "input.posts-select" ).val();

		var duplicateCheck = function ( element ) {
			return element == selected_post_id;
		}

		if( current_posts.findIndex( duplicateCheck ) < 0 )
		{
			current_posts.push( $( this ).parent().find( "input.posts-select" ).val() );

			$( this ).parent().find( "input.posts-hidden-input" ).val( window.ads_posts.postsToString( current_posts ) );

			if( typeof window.ads_posts.posts_names === "undefined" )
			{
				window.ads_posts.posts_names = window.ads_posts.grabPostsNames( $( this ).parent().parent().parent().find( "input.posts-select" ).next( "datalist" ) );
			}

			window.ads_posts.forceCurrentlySelectedPostsRefresh( current_posts );
		}
	} );

	$( "body" ).on( "click", ".posts-list-object-holder span.remove-btn-holder", function() {
		var current_posts = JSON.parse( $( this ).parent().parent().parent().find( "input.posts-hidden-input" ).val() );

		var selected_post_id = parseInt( $( this ).attr( "data-post-id" ) );

		var duplicateCheck = function ( element ) {
			return element == selected_post_id;
		}

		var index_of_selected_post = current_posts.findIndex( duplicateCheck );

		if( index_of_selected_post > -1 )
		{
			current_posts.splice( index_of_selected_post, 1 );

			$( this ).parent().parent().parent().find( "input.posts-hidden-input" ).val( window.ads_posts.postsToString( current_posts ) );

			if( typeof window.ads_posts.posts_names === "undefined" )
			{
				window.ads_posts.posts_names = window.ads_posts.grabPostsNames( $( this ).parent().parent().parent().find( "input.posts-select" ).next( "datalist" ) );
			}

			window.ads_posts.forceCurrentlySelectedPostsRefresh( current_posts );
		}
	} );

	$( "body" ).on( "change", ".posts-select", function() {
		if( typeof window.ads_posts.posts_names === "undefined" )
		{
			window.ads_posts.posts_names = window.ads_posts.grabPostsNames( $( this ).next( "datalist" ) );
		}

		var selected_post_holder = $( this ).parent().parent().find( "div" ).find( "input.currently-selected-post" );

		selected_post_holder.val( window.ads_posts.detectPostName( parseInt( $( this ).val() ) ) );
	} );
} );

window.ads_posts = {
	forceCurrentlySelectedPostsRefresh : function ( posts )
	{
		var container = jQuery( "div.current-posts-list" );

		if( typeof window.ads_posts.holder_sample === "undefined" )
		{
			window.ads_posts.holder_sample = jQuery( container ).find( "span.posts-sample" ).first().get();

			window.ads_posts.holder_sample = jQuery( container ).find( "span.posts-sample" ).first().get();
		}

		container.html( "" );

		for (var i = 0; i < posts.length; i++) 
		{
			var post_object = jQuery( window.ads_posts.holder_sample ).clone();

			post_object.find( "span.cat-name" ).text( this.detectPostName( posts[i] ) );

			post_object.css( "display", "inline-block" );

			post_object.find( "span.remove-btn-holder" ).attr( "data-post-id", posts[i] );

			container.append( post_object );
		}

		if( posts.length == 0)
		{
			var no_posts_linked_el = document.createElement( "span" );
			no_posts_linked_el.className = "no-posts-found";
			no_posts_linked_el.innerText = ads_posts_translations.no_posts;

			container.append( no_posts_linked_el );
		}
	},
	detectPostName : function ( post_id )
	{
		if( typeof window.ads_posts.posts_names[post_id] !== "undefined" )
		{
			return window.ads_posts.posts_names[post_id];
		}
		return "unknown";
	},
	grabPostsNames : function ( select ) 
	{
		var names = {};
		var options = $( select ).find( 'option' );

		for( var i = 0; i < options.length; i++)
		{
			var option = $( options ).eq( i );
			names[$( option ).attr( "value" )] = $( option ).text();
		}

		return names;
	},
	postsToString : function ( object )
	{
		var values = Object.values( object );

		var string = "[";

		for( var i = 0; i < values.length; i++)
		{
			string += values[i];

			if( i < values.length - 1)
			{
				string += ",";
			}
		}

		string += "]";

		return string;
	}
};