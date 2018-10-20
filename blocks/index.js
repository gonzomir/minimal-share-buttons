( function( blocks, element, i18n ) {
  var el = element.createElement,
      registerBlockType = blocks.registerBlockType,
      __ = i18n.__;

  registerBlockType( 'msb/share', {

    title: __( 'Minimal Share Buttons', 'minimal-share-buttons' ),
    description: __( 'Shows Minimal Shate Buttons widget with the buttons, set in the plugin settings', 'minimal-share-buttons' ),
    icon: 'share',
    category: 'widgets',

    attributes: {
      align: {
        type: 'string',
        default: 'none',
      },
      title: {
        type: 'string',
        default: __( 'Share', 'minimal-share-buttons' ),
      }
    },

    edit: function( props ) {
      var isSelected = props.isSelected;

      return [
        isSelected && (el( wp.editor.BlockControls, {key: "controls"},
          el( wp.editor.BlockAlignmentToolbar, {
            value: props.attributes.align,
            controls: ['left', 'center', 'right'],
            onChange: ( nextAlign ) => {
              props.setAttributes( { align: nextAlign } );
            }
          }),
        )),
        el( wp.editor.InspectorControls, {key: "inspector"},
          el( wp.components.PanelBody, {
              title: __( 'Main Settings', 'minimal-share-buttons' ),
            },
            el( wp.components.TextControl, {
              label: __( 'Title', 'minimal-share-buttons' ),
              type: 'text',
              value: props.attributes.title,
              onChange: function( value ) {
                props.setAttributes( { title: value } );
              }
            })
          ),
        ),
        el( wp.components.ServerSideRender, {
          block: 'msb/share',
          attributes: props.attributes,
        }),
      ];
    },

    save: function( props ) {
      return null;
    },

  } );
} )(
  window.wp.blocks,
  window.wp.element,
  window.wp.i18n
);
