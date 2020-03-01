jQuery( function( $ ) {
	$( "button.add-category-btn" ).click( function() {
		var current_categories = JSON.parse( $( this ).parent().find( "input.categories-hidden-input" ).val() );

		var selected_category_id = $( this ).parent().find( "select.categories-select" ).val();

		var duplicateCheck = function ( element ) {
			return element == selected_category_id;
		}

		if( current_categories.findIndex( duplicateCheck ) < 0 )
		{
			current_categories.push( $( this ).parent().find( "select.categories-select" ).val() );

			$( this ).parent().find( "input.categories-hidden-input" ).val( window.ads_categories.categoriesToString( current_categories ) );

			if( typeof window.ads_categories.categories_names === "undefined" )
			{
				window.ads_categories.categories_names = window.ads_categories.grabCategoriesNames( $( this ).parent().parent().parent().find( "select.categories-select" ) );
			}

			window.ads_categories.forceCurrentlySelectedCategoriesRefresh( current_categories );
		}
	} );

	$( "body" ).on( "click", ".categories-list-object-holder span.remove-btn-holder", function() {
		var current_categories = JSON.parse( $( this ).parent().parent().parent().find( "input.categories-hidden-input" ).val() );

		var selected_category_id = parseInt( $( this ).attr( "data-category-id" ) );

		var duplicateCheck = function ( element ) {
			return element == selected_category_id;
		}

		var index_of_selected_category = current_categories.findIndex( duplicateCheck );

		if( index_of_selected_category > -1 )
		{
			current_categories.splice( index_of_selected_category, 1 );

			$( this ).parent().parent().parent().find( "input.categories-hidden-input" ).val( window.ads_categories.categoriesToString( current_categories ) );

			if( typeof window.ads_categories.categories_names === "undefined" )
			{
				window.ads_categories.categories_names = window.ads_categories.grabCategoriesNames( $( this ).parent().parent().parent().find( "select.categories-select" ) );
			}

			window.ads_categories.forceCurrentlySelectedCategoriesRefresh( current_categories );
		}
	} );
} );

window.ads_categories = {
	forceCurrentlySelectedCategoriesRefresh : function ( categories )
	{
		var container = jQuery( "div.current-categories-list" );

		if( typeof window.ads_categories.holder_sample === "undefined" )
		{
			window.ads_categories.holder_sample = jQuery( container ).find( "span.category-sample" ).first().get();

			window.ads_categories.holder_sample = jQuery( container ).find( "span.category-sample" ).first().get();
		}

		container.html( "" );

		var detectCategoryName = function ( category_id ) {
			if( typeof window.ads_categories.categories_names[category_id] !== "undefined" )
			{
				return window.ads_categories.categories_names[category_id];
			}
			return "unknown";
		}

		for (var i = 0; i < categories.length; i++) 
		{
			var category_object = jQuery( window.ads_categories.holder_sample ).clone();

			category_object.find( "span.cat-name" ).text( detectCategoryName( categories[i] ) );

			category_object.css( "display", "inline-block" );

			category_object.find( "span.remove-btn-holder" ).attr( "data-category-id", categories[i] );

			container.append( category_object );
		}

		if( categories.length == 0)
		{
			var no_categories_linked_el = document.createElement( "span" );
			no_categories_linked_el.className = "no-categories-found";
			no_categories_linked_el.innerText = ads_categories_translations.no_categories;

			container.append( no_categories_linked_el );
		}
	},
	grabCategoriesNames : function ( select ) 
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
	categoriesToString : function ( object )
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