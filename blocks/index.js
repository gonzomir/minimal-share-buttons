( function( blocks, element, i18n ) {
  var el = element.createElement,
    registerBlockType = blocks.registerBlockType,
    __ = i18n.__,
    Editable = blocks.Editable,
    children = blocks.source.children,
    blockStyle = { backgroundColor: '#900', color: '#fff', padding: '20px' };

  registerBlockType( 'msb/share', {

    title: __( 'Minimal Share Buttons' ),

    icon: 'share',

    category: 'widgets',

    attributes: {
      align: {
        type: 'string',
        default: 'none',
      },
      blockTitle: {
        type: 'string',
        default: __( 'Share' ),
      }
    },

    edit: function( props ) {
      var focus = props.focus;

      return [
        focus && (el( blocks.BlockControls, {key: "controls"},
          el( blocks.AlignmentToolbar, {
            value: props.attributes.align,
            onChange: function( nextAlign ){
              props.setAttributes( { align: nextAlign } );
            }
          })
        )),
        focus && (el( blocks.InspectorControls, {key: "inspector"},
          el( blocks.BlockDescription, {},
            el( 'p', {}, __( 'Shows Minimal Shate Buttons widget with the buttons, set in the plugin settings' ) )
          ),
          el( 'h3', {}, __( 'Share block settings' ) ),
          el( blocks.InspectorControls.TextControl, {
            label: __( 'Block title' ),
            type: 'text',
            value: props.attributes.blockTitle,
            onChange: function( value ){
              props.setAttributes( { blockTitle: value } );
            }
          })
        )),
        el( 'aside', { className: 'msb-container ' + props.className },
          el( 'h2', {}, props.attributes.blockTitle ),
          el( 'p', {}, __( 'Share buttons will go here acording to plugin settings.' ) )
        )
      ];
    },

    save: function( props ) {
      /*
      return (
        el( 'aside', { className: 'msb-container ' + props.className },
          el( 'h2', {}, props.attributes.blockTitle),
          el( 'p', {}, __( 'Share buttons will go here acording to plugin settings.' ) )
        )
      )
      */
      return null;
    },

  } );
} )(
  window.wp.blocks,
  window.wp.element,
  window.wp.i18n
);
