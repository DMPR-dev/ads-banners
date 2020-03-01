(function(editor,blocks,element,components) {

    var el = element.createElement,
    inspector_controls = editor.InspectorControls,
    panel_body = components.PanelBody,
    fragment = element.Fragment;
    inner_blocks = editor.InnerBlocks;

    var adClientInput = function( props ) {
        return [
            el(
                'small', {}, 'Ad Client'
            ),
            el(
                'input',{ type: 'text', defaultValue: props.attributes.ad_client, placeholder: 'Ad Client', style: { width: '100%' }, onChange: function( event ) {
                    props.setAttributes( { ad_client : event.target.value } );
                } }
            )
        ]
    };
    var adSlotInput = function( props ) {
        return [
            el(
                'small', {}, 'Ad Slot'
            ),
            el(
                'input',{ type: 'text', defaultValue: props.attributes.ad_slot, placeholder: 'Ad Slot', style: { width: '100%' }, onChange: function( event ) {
                    props.setAttributes( { ad_slot : event.target.value } );
                } }
            )
        ]
    };
  
    blocks.registerBlockType( 'ads-banners/google-ads', {
        title: 'Google Ads',
        icon: el( 'img', { src: gads_block.icon } ),
        category: 'layout',
        attributes: {
            ad_client: {
                type: 'string'
            },
            ad_slot: {
                type: 'string'
            }
        },
        edit: function( props ) {
            return el( 'div',null,[
                            el(
                                fragment, {}, [
                                    el(
                                        inspector_controls, {},
                                            el( panel_body, { title: 'Advertising Settings' }, [

                                                adClientInput( props ),
                                                adSlotInput( props )
                                            ]
                                        )
                                    ) 
                                ]
                            ),
                            el( 'p', { style: { textAlign: 'center' } }, 'Google Ads Banner' ),
                            el('img',{ 
                                style:{ 
                                    display: 'flex', 
                                    margin: 'auto',
                                    width: '300px',
                                    height: '300px'
                                }, 
                                src: 'http://via.placeholder.com/300?text=Google%20Ads%20Banner' }
                            )
                        ]
                    );
        },
        save: function( props ) {
            return el(
                'div',
                { 
                    style: { height: '100%' } 
                },
                [
                    el( 'ins', {
                        className: 'adsbygoogle ad-banners-google-ads',
                        style: { display: 'block' },
                        'data-ad-client': props.attributes.ad_client,
                        'data-ad-format': 'auto',
                        'data-ad-slot': props.attributes.ad_slot,
                        'data-full-width-responsive': true
                    } )
                ]
            );
        },
    } );
}(
    window.wp.editor,
    window.wp.blocks,
    window.wp.element,
    window.wp.components
) );
