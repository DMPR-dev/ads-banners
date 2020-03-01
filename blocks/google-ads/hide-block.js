var diy_family = {

	removedBlocks : [
	    'ads-banners/google-ads'
	],

	unregisterBlocks : function() {

		var blocks = wp.blocks.getBlockTypes();
		if( typeof blocks !== 'undefined' )
		{
			blocks.forEach( function( blockType ) {

				if( typeof blockType !== 'undefined' )
				{
					var _editor = wp.data.select( 'core/editor' );

				  	if( typeof _editor !== 'undefined' )
				  	{
				  		if( _editor.getCurrentPostType() !== "ads-banners" )
				  		{
						    if ( diy_family.removedBlocks.includes( blockType.name ) === true ) 
						    {
						        wp.blocks.unregisterBlockType( blockType.name );
						    }
						}
					}
				}
			});
		}
	}
};

wp.domReady( function() {

	diy_family.unregisterBlocks();

});

