(function ($) {
    const ThimPressMetaboxTabs = () => {
        $( document.body ).on( 'thimpress-metabox-tab', function() {
            $( 'ul.thimpress-metabox-tab__title' ).show();

            $( 'ul.thimpress-metabox-tab__title a' ).on( 'click', function( e ) {
                e.preventDefault();

                const panelWrap = $( this ).closest( 'div.thimpress-metabox-tab' );

                $( 'ul.thimpress-metabox-tab__title li', panelWrap ).removeClass( 'active' );

                $( this ).parent().addClass( 'active' );

                $( 'div.thimpress-metabox-tab__content--inner', panelWrap ).hide();

                $( 'div.thimpress-metabox-tab__content--inner[data-content="' + $( this ).data( 'content' ) + '"]', panelWrap ).show();
            } );

            $( 'div.thimpress-metabox-tab' ).each( function() {
                $( this ).find( 'ul.thimpress-metabox-tab__title li' ).eq( 0 ).find( 'a' ).trigger( 'click' );
            } );
        } ).trigger( 'thimpress-metabox-tab' );
    };

    const thimMetaboxFileInput = () => {
        $( '.thim-meta-box__file' ).each( ( i, element ) => {
            let thimImageFrame;

            const imageGalleryIds = $( element ).find( '.thim-meta-box__file_input' );
            const listImages = $( element ).find( '.thim-meta-box__file_list' );
            const btnUpload = $( element ).find( '.btn-upload' );
            const isMultil = !! $( element ).data( 'multil' );

            $( btnUpload ).on( 'click', ( event ) => {
                event.preventDefault();

                if ( thimImageFrame ) {
                    thimImageFrame.open();
                    return;
                }

                thimImageFrame = wp.media( {
                    states: [
                        new wp.media.controller.Library( {
                            filterable: 'all',
                            multiple: isMultil,
                        } ),
                    ],
                } );

                thimImageFrame.on( 'select', function() {
                    const selection = thimImageFrame.state().get( 'selection' );
                    let attachmentIds = imageGalleryIds.val();

                    selection.forEach( function( attachment ) {
                        attachment = attachment.toJSON();

                        if ( attachment.id ) {
                            if ( ! isMultil ) {
                                attachmentIds = attachment.id;
                                listImages.empty();
                            } else {
                                attachmentIds = attachmentIds ? attachmentIds + ',' + attachment.id : attachment.id;
                            }

                            if ( attachment.type === 'image' ) {
                                const attachmentImage = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                                listImages.append(
                                    '<li class="thim-meta-box__file_list-item image" data-attachment_id="' + attachment.id + '"><img src="' + attachmentImage +
                                    '" /><ul class="actions"><li><a href="#" class="delete"></a></li></ul></li>'
                                );
                            } else {
                                listImages.append(
                                    '<li class="thim-meta-box__file_list-item image" data-attachment_id="' + attachment.id + '"><img class="is_file" src="' + attachment.icon +
                                    '" /><span>' + attachment.filename + '</span><ul class="actions"><li><a href="#" class="delete"></a></li></ul></li>'
                                );
                            }
                        }
                    } );

                    delImage();

                    imageGalleryIds.val( attachmentIds );
                } );

                thimImageFrame.open();
            } );

            if ( isMultil ) {
                listImages.sortable( {
                    items: 'li.image',
                    cursor: 'move',
                    scrollSensitivity: 40,
                    forcePlaceholderSize: true,
                    forceHelperSize: false,
                    helper: 'clone',
                    opacity: 0.65,
                    placeholder: 'thim-metabox-sortable-placeholder',
                    start( event, ui ) {
                        ui.item.css( 'background-color', '#f6f6f6' );
                    },
                    stop( event, ui ) {
                        ui.item.removeAttr( 'style' );
                    },
                    update() {
                        let attachmentIds = '';

                        listImages.find( 'li.image' ).css( 'cursor', 'default' ).each( function() {
                            const attachmentId = $( this ).attr( 'data-attachment_id' );
                            attachmentIds = attachmentIds + attachmentId + ',';
                        } );

                        delImage();

                        imageGalleryIds.val( attachmentIds );
                    },
                } );
            }

            const delImage = () => {
                $( listImages ).find( 'li.image' ).each( ( i, ele ) => {
                    const del = $( ele ).find( 'a.delete' );

                    del.on( 'click', function() {
                        $( ele ).remove();

                        if ( isMultil ) {
                            let attachmentIds = '';

                            $( listImages ).find( 'li.image' ).css( 'cursor', 'default' ).each( function() {
                                const attachmentId = $( this ).attr( 'data-attachment_id' );
                                attachmentIds = attachmentIds + attachmentId + ',';
                            } );

                            imageGalleryIds.val( attachmentIds );
                        } else {
                            imageGalleryIds.val( '' );
                        }

                        return false;
                    } );
                } );
            };

            delImage();
        } );
    };

    const thimSelect2 = () => {
        if ( $.fn.select2 ) {
            $( '.thim-select-2 select' ).select2();
        }
    };

    const thimMetaboxColorPicker = () => {
        $( '.thim-meta-box__color--input' )
            .iris( {
                change( event, ui ) {
                    $( this ).parent().find( '.thim-meta-box__color--preview' ).css( { backgroundColor: ui.color.toString() } );
                },
                hide: true,
                border: true,
            } )

            .on( 'click focus', function( event ) {
                event.stopPropagation();
                $( '.iris-picker' ).hide();
                $( this ).closest( '.thim-meta-box__color' ).find( '.iris-picker' ).show();
                $( this ).data( 'original-value', $( this ).val() );
            } )

            .on( 'change', function() {
                if ( $( this ).is( '.iris-error' ) ) {
                    const originalValue = $( this ).data( 'original-value' );

                    if ( originalValue.match( /^\#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/ ) ) {
                        $( this ).val( $( this ).data( 'original-value' ) ).trigger( 'change' );
                    } else {
                        $( this ).val( '' ).trigger( 'change' );
                    }
                }
            } );

        $( 'body' ).on( 'click', function() {
            $( '.iris-picker' ).hide();
        } );
    };

    const thimShowHide = () => {
        const metaBoxes = document.querySelectorAll( '.thimpress-meta-box' );
        let postEle = document.querySelector( '.editor-post-format .components-select-control__input' );
        let isClassicEditor = false;

        // Classic Editor.
        if ( ! postEle ) {
            postEle = document.querySelectorAll( 'input[name=post_format]' );
            isClassicEditor = true;
        }

        const toggleEle = ( postType ) => {
            [...metaBoxes].map( ele => {
                if ( ele.dataset.show ) {
                    const datas = JSON.parse( ele.dataset.show );

                    if ( postType && datas.post_format ) {
                        if ( (datas.post_format).includes( postType.value ) ) {
                            ele.parentNode.parentNode.style.display = "block";
                        } else {
                            ele.parentNode.parentNode.style.display = "none";
                        }
                    }
                }
            });
        }


        // Classic Editor.
        if ( isClassicEditor && [...postEle].length > 0 ) {
            [...postEle].map( postType => {
                if ( postType.checked ) {
                    toggleEle( postType );
                }

                postType.addEventListener('change', () => {
                    toggleEle( postType )
                } );
            });
        } else if ( postEle.length > 0  ) {
            toggleEle( postEle );

            postEle.addEventListener('change', () => {
                toggleEle( postEle )
            } );
        }
    }

    const thimCondition = () => {
        const metaBoxes = document.querySelectorAll( '.thimpress-meta-box' );

        [...metaBoxes].map( ele => {
            const formField = ele.querySelectorAll( '.form-field' );

            [...formField].map( field => {
                if ( field.hasAttribute('data-hide') && field.dataset.hide ) {
                    let dataHide = JSON.parse( field.dataset.hide );

                    let eleHides = ele.querySelectorAll( `input[id^="${dataHide[0]}"]` );

                    [...eleHides].map( eleHide => {
                        let type = eleHide.getAttribute( 'type' );

                        if ( eleHide ) {
                            switch( type ) {
                                case 'checkbox':
                                    if ( dataHide[1] == '!=' && dataHide[2] !== Boolean( eleHide.checked ) ) {
                                        field.style.display = 'none';
                                    } else if ( dataHide[1] == '=' && dataHide[2] == Boolean( eleHide.checked ) ) {
                                        field.style.display = 'none';
                                    } else {
                                        if ( field.classList.contains('thimpress-meta-box-group') ) {
                                            field.style.display = 'block';
                                        } else {
                                            field.style.display = 'flex';
                                        }
                                    }
                                    break;

                                case 'radio':
                                    if ( eleHide.checked ) {
                                        if ( dataHide[1] == '!=' && dataHide[2] !== eleHide.value ) {
                                            field.style.display = 'none';
                                        } else if ( dataHide[1] == '=' && dataHide[2] == eleHide.value ) {
                                            field.style.display = 'none';
                                        } else {
                                            if ( field.classList.contains('thimpress-meta-box-group') ) {
                                                field.style.display = 'block';
                                            } else {
                                                field.style.display = 'flex';
                                            }
                                        }
                                    }
                                    break;
                            }

                            eleHide.addEventListener( 'change', (e) => {
                                let target = e.target;

                                switch( type ) {
                                    case 'checkbox':
                                        if ( dataHide[1] == '!=' && dataHide[2] !== Boolean( target.checked ) ) {
                                            field.style.display = 'none';
                                        } else if ( dataHide[1] == '=' && dataHide[2] == Boolean( target.checked ) ) {
                                            field.style.display = 'none';
                                        } else {
                                            if ( field.classList.contains('thimpress-meta-box-group') ) {
                                                field.style.display = 'block';
                                            } else {
                                                field.style.display = 'flex';
                                            }
                                        }
                                        break;

                                    case 'select':
                                    case 'radio':
                                        if ( dataHide[1] == '!=' && dataHide[2] !== target.value ) {
                                            field.style.display = 'none';
                                        } else if ( dataHide[1] == '=' && dataHide[2] == target.value ) {
                                            field.style.display = 'none';
                                        } else {
                                            if ( field.classList.contains('thimpress-meta-box-group') ) {
                                                field.style.display = 'block';
                                            } else {
                                                field.style.display = 'flex';
                                            }
                                        }
                                        break;
                                }
                            } );
                        }
                    } );
                }
            });
        });
    }

    $(document).ready( function() {
        ThimPressMetaboxTabs();
        thimMetaboxFileInput();
        thimSelect2();
        thimMetaboxColorPicker();
        thimCondition();

        setTimeout( function() {
            thimShowHide();
        }, 1000 );
    } );
})(jQuery);