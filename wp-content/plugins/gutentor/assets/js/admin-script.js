(function( $ ){

	let settingAjax = function( action, block_id ){

		var data = {
			action   : action,
			nonce    : GUTENTOR_SETTINGS.ajax_nonce,
			block_id : block_id
		};

		$.ajax({
			url : GUTENTOR_SETTINGS.ajax_url,
			type: "POST",
			data: data,
			success: function(result){
				// console.log( result );
		  	},
		  	beforeSend: function(){

		  	},
			complete : function(){

			}
		});
	};

	$( document ).ready( function(){
        $("#gutentor-admin-element-tab").click(function () {
            $(this).addClass("tablist-item-active").siblings().removeClass("tablist-item-active");
            $(".gutentor-admin-element-content").addClass("gutentor-active").siblings().removeClass("gutentor-active");
        });
        $("#gutentor-admin-module-tab").click(function () {
            $(this).addClass("tablist-item-active").siblings().removeClass("tablist-item-active");
            $(".gutentor-admin-module-content").addClass("gutentor-active").siblings().removeClass("gutentor-active");
        });
		$("#gutentor-admin-post-tab").click(function () {
			$(this).addClass("tablist-item-active").siblings().removeClass("tablist-item-active");
			$(".gutentor-admin-post-content").addClass("gutentor-active").siblings().removeClass("gutentor-active");
		});
		$("#gutentor-admin-term-tab").click(function () {
			$(this).addClass("tablist-item-active").siblings().removeClass("tablist-item-active");
			$(".gutentor-admin-term-content").addClass("gutentor-active").siblings().removeClass("gutentor-active");
		});
        $("#gutentor-admin-widget-tab").click(function () {
            $(this).addClass("tablist-item-active").siblings().removeClass("tablist-item-active");
            $(".gutentor-admin-widget-content").addClass("gutentor-active").siblings().removeClass("gutentor-active");
        });

        $( document ).on( 'click', '.onoffswitch', function( e ){
            e.preventDefault();

			let block_id = $( this ).find('input').data( 'action' );

			$( this ).find('input').prop( 'checked', !$( this ).find('input').prop( 'checked' ) );
            let val = $( this ).find('input').prop( 'checked' );
            let action = val ? 'activate_block' : 'deactivate_block';
			if( 'bulk' === block_id ){
				action = val ? 'bulk_activate_blocks' : 'bulk_deactivate_blocks';
			}

            settingAjax( action, block_id );

		});

        /*Video*/
        $('.gutentor-getting-started-watch-video').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false,
        });

		/*Color Picker
		* Copied for FieldPress
		* https://github.com/addonspress/fieldpress
		* */
		if( typeof Color === 'function' ) {

			/*adding alpha support for Automattic Color.js toString function.*/
			Color.fn.toString = function () {

				/*check for alpha*/
				if ( this._alpha < 1 ) {
					return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
				}

				var hex = parseInt( this._color, 10 ).toString( 16 );

				if ( this.error ) { return ''; }

				/*maybe left pad it*/
				if ( hex.length < 6 ) {
					for (var i = 6 - hex.length - 1; i >= 0; i--) {
						hex = '0' + hex;
					}
				}
				return '#' + hex;
			};
		}
		var FPPARSERGBACOLOR = function( val ) {
			console.log(val)

			var value = val.replace(/\s+/g, ''),
				alpha = ( value.indexOf('rgba') !== -1 ) ? parseFloat( value.replace(/^.*,(.+)\)/, '$1') * 100 ) : 100,
				rgba  = ( alpha < 100 );
			return { value: value, alpha: alpha, rgba: rgba };
		};
		var FPCOLORPICKER = function( $this ) {

			/*Default Color Picker*/
			if( $this.data('rgba') && $this.data('rgba') === 1 ){
				/*parse rgba*/
				var picker = FPPARSERGBACOLOR( $this.val() );
				$this.wpColorPicker({

					/*wpColorPicker.clear*/
					clear: function() {
						$this.trigger('keyup');
					},

					/*wpColorPicker.change*/
					change: function( event, ui ) {

						var ui_color_value = ui.color.toString();

						/*update checkerboard background color*/
						$this.closest('.wp-picker-container').find('.fp-rgba-slider-offset').css('background-color', ui_color_value);
						$this.val(ui_color_value).trigger('change');
					},

					/*wpColorPicker.create*/
					create: function() {

						/*set variables for alpha slider*/
						var a8cIris       = $this.data('a8cIris'),
							$container    = $this.closest('.wp-picker-container'),

							/*appending alpha wrapper*/
							$alpha_wrap   = $('<div class="fp-rgba-wrap">' +
								'<div class="fp-rgba-slider"></div>' +
								'<div class="fp-rgba-slider-offset"></div>' +
								'<div class="fp-rgba-text"></div>' +
								'</div>').appendTo( $container.find('.wp-picker-holder') ),

							$alpha_slider = $alpha_wrap.find('.fp-rgba-slider'),
							$alpha_text   = $alpha_wrap.find('.fp-rgba-text'),
							$alpha_offset = $alpha_wrap.find('.fp-rgba-slider-offset');

						/*alpha slider*/
						$alpha_slider.slider({

							/*slider.slide*/
							slide: function( event, ui ) {
								console.log(ui)

								var slide_value = parseFloat( ui.value / 100 );

								/*update iris data alpha && wpColorPicker color option && alpha text*/
								a8cIris._color._alpha = slide_value;
								console.log(a8cIris._color._alpha)
								$this.wpColorPicker( 'color', a8cIris._color.toString() );
								$alpha_text.text( ( slide_value < 1 ? slide_value : '' ) );
							},

							/*slider: create*/
							create: function() {

								var slide_value = parseFloat( picker.alpha / 100 ),
									alpha_text_value = slide_value < 1 ? slide_value : '';

								/*update alpha text && checkerboard background color*/
								$alpha_text.text(alpha_text_value);
								$alpha_offset.css('background-color', picker.value);

								/*wpColorPicker clear for update iris data alpha && alpha text && slider color option*/
								$container.on('click', '.wp-picker-clear', function() {

									a8cIris._color._alpha = 1;
									$alpha_text.text('').trigger('change');
									$alpha_slider.slider('option', 'value', 100).trigger('slide');
								});

								/*wpColorPicker default button for update iris data alpha && alpha text && slider color option*/
								$container.on('click', '.wp-picker-default', function() {

									var default_picker = FPPARSERGBACOLOR( $this.data('default-color') ),
										default_value  = parseFloat( default_picker.alpha / 100 ),
										default_text   = default_value < 1 ? default_value : '';
									a8cIris._color._alpha = default_value;
									$alpha_text.text(default_text);
									$alpha_slider.slider('option', 'value', default_picker.alpha).trigger('slide');
								});

								/*show alpha wrapper on click color picker button*/
								$container.on('click', '.wp-color-result', function() {
									$alpha_wrap.toggle();
								});

								/*hide alpha wrapper on click body*/
								$(document).on( 'click.wpcolorpicker', function() {
									$alpha_wrap.hide();
								});
							},

							/* slider: options  */
							value: picker.alpha,
							step: 1,
							min: 1,
							max: 100
						});
					}
				});
			}
			else{
				$this.wpColorPicker( {
					change: _.throttle( function() {  /* For Customizer  */

						$this.trigger( 'change' );
					}, 3000 ),
					clear: _.throttle( function() {  /* For Customizer  */
						$this.trigger( 'change' );
					}, 4000 )
				});
			}
		};
		$('body').find('.gutentor-color-picker').each(function(){
			FPCOLORPICKER($(this));
		});

		/*image loader*/
		var FPIMAGEUPLOAD = function () {
			$(document).on('click', '.gutentor-img-uploader-open', function (e){
				e.preventDefault();
				var image_uploader_open = $(this),
					image_wrapper = image_uploader_open.closest('.gutentor-fields'),
					preview = image_wrapper.find('.gutentor-img-preview'),
					input   = image_wrapper.find('input'),
					image     = image_wrapper.find('img'),
					wp_media_frame;

				/* Check if the `wp.media.gallery` API exists. */
				if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
					return;
				}
				/* If the media frame already exists, reopen it. */
				if ( wp_media_frame ) {
					wp_media_frame.open();
					return;
				}
				/* Create the media frame. */
				wp_media_frame = wp.media({
					library: {
						type: 'image'
					},
					title: image_uploader_open.data('upload'),
					button: {
						text: image_uploader_open.data('button-text')
					}
				});

				/*select selected image*/
				wp_media_frame.on('open', function(){
					var selected = input.val(); // the id of the image
					if (selected) {
						var selection = wp_media_frame.state().get('selection'),
							attachment = wp.media.attachment(selected);
						selection.add(attachment);
					}
				});

				/* When an image is selected, run a callback. */
				wp_media_frame.on( 'select', function() {
					var attachment = wp_media_frame.state().get('selection').first().toJSON(),
						thumbnail  = ( typeof attachment.sizes.thumbnail !== 'undefined' ) ? attachment.sizes.thumbnail.url : attachment.url;

					preview.removeClass('hidden');
					image.attr('src', thumbnail);
					input.val( attachment.id );
					input.trigger('change');
				});

				/* Finally, open the modal. */
				wp_media_frame.open();
			});

			/* Remove image */
			$(document).on('click','.gutentor-clear-img',function (e) {
				e.preventDefault();
				var image_wrapper = $(this).closest('.gutentor-fields'),
					preview = image_wrapper.find('.gutentor-img-preview'),
					input   = image_wrapper.find('input');

				input.val('');
				preview.addClass('hidden');
			});
		};
		FPIMAGEUPLOAD();
	});
})( jQuery );