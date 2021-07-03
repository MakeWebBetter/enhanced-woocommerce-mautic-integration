(function( $ ) {
	'use strict';

	// i18n variables
	var ajaxUrl                = mauwooi18n.ajaxUrl;
	var mauwooWentWrong        = mauwooi18n.mauwooWentWrong;
	var mauwooSuccess          = mauwooi18n.mauwooSuccess;
	var mauwooCreatingProperty = mauwooi18n.mauwooCreatingProperty;
	var mauwooSetupCompleted   = mauwooi18n.mauwooSetupCompleted;
	var mauwooSecurity         = mauwooi18n.mauwooSecurity;
	var mauwooConnectTab       = mauwooi18n.mauwooConnectTab;
	var mauwooCustomFields     = mauwooi18n.mauwooCustomFields;
	var mauwooOverviewTab      = mauwooi18n.mauwooOverviewTab;
	var mauwooNoFieldsFound    = mauwooi18n.mauwooNoFieldsFound;

	jQuery( document ).ready(
		function(){
			/*popup*/
			jQuery( document ).on(
				'click',
				'#mauwoo-know-about-app-settings-button' ,
				function(e){
					e.preventDefault();
					jQuery( ".mauwoo-app-setup-wrapper" ).toggleClass( "show" );
				}
			);
			jQuery( document ).on(
				'click',
				'.mauwoo-app-setup-header-close' ,
				function(){
					jQuery( ".mauwoo-app-setup-wrapper" ).toggleClass( "show" );
				}
			);
			jQuery( document ).on(
				'click',
				'.mauwoo-app-setup-wrapper' ,
				function(){
					jQuery( this ).toggleClass( "show" );
				}
			);
			jQuery( document ).on(
				'click',
				'.mauwoo-app-setup-content' ,
				function(e){
					e.stopPropagation();
				}
			);
			// app setup pop up

			jQuery( '.mauwoo-show-pass' ).on(
				'click',
				function(e) {

					var attr = $( this ).closest( 'div' ).find( "input.regular-text" ).attr( 'type' );

					if ( attr == 'password' ) {

						$( this ).closest( 'div' ).find( "input.regular-text" ).attr( 'type', 'text' );
					} else {

						$( this ).closest( 'div' ).find( "input.regular-text" ).attr( 'type','password' );
					}
				}
			);
			jQuery( '.mauwoo-reauthorize-app' ).on(
				'click',
				function(e) {
					jQuery( '#mauwoo_loader' ).show();
					jQuery.post(
						ajaxUrl,
						{'action' : 'mautic_woo_allow_reauth', 'mauwooSecurity' : mauwooSecurity },
						function(response){
							location.reload();
						}
					);
				}
			);

			jQuery( 'a.mauwoo-tab-disabled' ).on(
				"click",
				function(e) {
					e.preventDefault();
					return false;
				}
			);

			jQuery( 'a#mauwoo-all-fields' ).on(
				'click',
				function(e){
					jQuery( 'input.mauwoo_select_property' ).attr( 'checked', true );
				}
			);

			jQuery( 'a#mauwoo-clear-fields' ).on(
				'click',
				function(e){
					jQuery( 'input.mauwoo_select_property' ).attr( 'checked', false );
				}
			);

			jQuery( 'a#mauwoo-rollback' ).on(
				'click',
				function(e){
					jQuery( '#mauwoo_loader' ).show();
					jQuery.post(
						ajaxUrl,
						{'action' : 'mautic_woo_rollback', 'mauwooSecurity' : mauwooSecurity },
						function(response){
							location.reload();
						}
					);
				}
			);

			jQuery( 'a.mauwoo-refresh-token' ).on(
				'click',
				function(e){
					jQuery( ".fa-circle-notch" ).removeClass( 'mauwoo-hide' );
					jQuery.post(
						ajaxUrl,
						{'action' : 'mautic_woo_check_oauth_access_token', 'mauwooSecurity' : mauwooSecurity },
						function(response){

							var oauth_response = jQuery.parseJSON( response );
							var oauth_status   = oauth_response.status;
							var oauthMessage   = oauth_response.message;

							if ( oauth_status ) {
								jQuery( ".fa-circle-notch" ).addClass( 'mauwoo-hide' );
								jQuery( ".mauwoo-acces-token-renewal" ).html( '<p> ' + oauthMessage + ' <i class="fas fa-check-circle mauwoo-check"></i> </p>' );
							} else {

								jQuery( ".fa-circle-notch" ).addClass( 'mauwoo-hide' );
								jQuery( ".mauwoo-acces-token-renewal" ).html( oauthMessage );
							}
							location.reload();
						}
					);
				}
			)

			jQuery( 'a.mauwoo_pro_select_fields' ).on(
				'click',
				function(e) {
					e.preventDefault();
					jQuery( '#mauwoo_loader' ).show();
					jQuery.post(
						ajaxUrl,
						{ 'action' : 'mautic_woo_save_user_choice', 'mauwooSecurity' : mauwooSecurity, async: false, 'choice' : 'yes' },
						function( status ){
							location.reload();
						}
					);
				}
			);

			jQuery( 'a.mauwoo_pro_go_with_integration' ).on(
				'click',
				function(e) {
					e.preventDefault();
					jQuery( '#mauwoo_loader' ).show();
					jQuery.post(
						ajaxUrl,
						{ 'action' : 'mautic_woo_save_user_choice', 'mauwooSecurity' : mauwooSecurity, async: false, 'choice' : 'no' },
						function( status ){
							location.reload();
						}
					);
				}
			);

			jQuery( 'a.mauwoo-run-change-decision' ).on(
				'click',
				function(e) {
					e.preventDefault();
					jQuery( '#mauwoo_loader' ).show();
					jQuery.post(
						ajaxUrl,
						{ 'action' : 'mautic_woo_clear_user_choice', 'mauwooSecurity' : mauwooSecurity, async: false },
						function( status ){
							location.reload();
						}
					);
				}
			);

			jQuery( 'a.mauwoo_pro_move_to_custom_fields' ).on(
				'click',
				function(e) {
					e.preventDefault();

					jQuery.post(
						ajaxUrl,
						{ 'action' : 'mautic_woo_move_to_custom_fields', 'mauwooSecurity' : mauwooSecurity, async: false },
						function( status ){
							window.location.href = mauwooCustomFields;
						}
					);
				}
			);

			jQuery( 'a#mauwoo-get-started' ).on(
				"click",
				function(e){
					e.preventDefault();
					jQuery( '.fa-circle-notch' ).removeClass( 'mauwoo-hide' );
					jQuery.post(
						ajaxUrl,
						{ 'action' : 'mautic_woo_get_started_call', 'mauwooSecurity' : mauwooSecurity, async: false },
						function( status ){
							window.location.href = mauwooConnectTab;
						}
					);
				}
			);

			jQuery( 'a.mauwoo-create-single-field' ).on(
				"click",
				function(e){

					var alias = $( this ).data( "alias" );
					$( this ).addClass( 'mauwoo-hide' );
					var td = $( this ).closest( 'td' );
					td.find( '.fa-circle-notch' ).removeClass( 'mauwoo-hide' );

					jQuery.post(
						ajaxUrl,
						{'action' : 'mautic_woo_check_oauth_access_token', 'mauwooSecurity' : mauwooSecurity },
						function(response){

							var oauth_response = jQuery.parseJSON( response );
							var oauth_status   = oauth_response.status;
							var oauthMessage   = oauth_response.message;

							if ( oauth_status ) {

								jQuery.post(
									ajaxUrl,
									{ 'action' : 'mautic_woo_create_single_field2', 'mauwooSecurity' : mauwooSecurity, 'alias' : alias },
									function( status ){

										var proresponse      = jQuery.parseJSON( status );
										var proerrors        = proresponse.errors;
										var promauwooMessage = "";

										if ( ! proerrors ) {

											var proresponseCode = proresponse.code;

											if ( proresponseCode == 201 ) {
												td.html( '<i class="fas fa-check-circle mauwoo-check"></i>' );
												showNotification( "<strong>" + proresponse.label + " : </strong> " + mauwooCreatingProperty );
											} else if ( proresponseCode == 400 ) {
												var promauwooMessage = proresponse.message;
												td.html( '<i class="fas fa-times-circle mauwoo-cross mauwoo-cross-red"></i>' );
												showNotification( "<strong>" + proresponse.label + " : </strong> " + promauwooMessage , true );
											} else {
												td.html( '<i class="fas fa-times-circle mauwoo-cross mauwoo-cross-red"></i>' );
												showNotification( "<strong>" + proresponse.label + " : </strong> " + mauwooWentWrong , true );
											}
										}
									}
								);
							} else {
								showNotification( mauwooWentWrong , true );
								return false;
							}
						}
					);
				}
			);

			function showNotification(message , error = false ){
				var html = '<div class="mauwoo-noti-wrap"><div class="mauwoo-noti-inner">';
				html    += message;
				html    += '<a href="#" class="mauwoo-noti-close">X</a>';
				html    += '</div></div>';

				jQuery( '.mauwoo-content-container' ).append( html );
				jQuery( '.mauwoo-noti-wrap' ).addClass( 'show' );
				if (error) {
					jQuery( '.mauwoo-noti-wrap' ).addClass( 'mauwoo-noti-wrap-error' );
				}

				setTimeout(
					function() {
						jQuery( ".mauwoo-noti-wrap" ).removeClass( 'show' );
					},
					2000
				);
			}

			jQuery( '#mauwoo-order-statuses' ).select2(
				{
					ajax:{
						url: ajaxurl,
						dataType: 'json',
						delay: 200,
						data: function (params) {
							return {
								q: params.term,
								action: 'mautic_woo_search_for_order_status'
							};
						},
						processResults: function( data ) {
							var options = [];
							if ( data ) {
								$.each(
									data,
									function( index, text )
									{
										options.push( { id: text[0], text: text[1]  } );
									}
								);
							}
							return {
								results:options
							};
						},
						cache: true
					},
				}
			);
			jQuery( '#mautic_go_pro_link' ).on(
				'click',
				function(e) {
					e.preventDefault();
					jQuery( '.mauwoo_pop_up_wrap' ).show();
				}
			);
			jQuery( '#mautic_woo_close_popup' ).click(
				function(){
					$( '.mauwoo_pop_up_wrap' ).hide();

				}
			);

			// v-1.0.3

			jQuery( 'a.mauwoo_support_development' ).on(
				'click',
				function(e) {
					e.preventDefault();

					jQuery.post(
						ajaxUrl,
						{ 'action' : 'mautic_woo_support_development', 'mauwooSecurity' : mauwooSecurity, async: false },
						function( status ){

							window.location.href = mauwooCustomFields;
						}
					);
				}
			);

			jQuery( '#mautic-woo-disabled-custom-fields' ).select2(
				{
					ajax:{
						url: ajaxurl,
						dataType: 'json',
						delay: 200,
						data: function (params) {
							return {
								q: params.term,
								action: 'mautic_woo_search_for_custom_fields'
							};
						},
						processResults: function( data ) {
							var options = [];
							if ( data ) {
								$.each(
									data,
									function( index, text )
									{
										options.push( { id: text[0], text: text[1]  } );
									}
								);
							}
							return {
								results:options
							};
						},
						cache: true
					},
				}
			);

		}
	);

	jQuery( document ).on(
		'click',
		'#mauwoo2-create-selected-fields',
		function(event) {

			event.preventDefault();

			var allFields = [];

			jQuery( ".mauwoo-progress-wrap" ).show();

			jQuery.each(
				jQuery( "input[name='selected_properties[]']:checked" ),
				function(){

					var alias = jQuery( this ).val();

					if (alias != "" || ( typeof alias !== "undefined")) {

						allFields.push( alias );

					}
				}
			);

			var count = 1;
			jQuery( 'html,body' ).animate(
				{
					scrollTop: jQuery( ".mauwoo-content-template" ).offset().top},
				'slow' ,
				function(){

					if (count == 1 ) {

						jQuery.post(
							ajaxUrl,
							{'action' : 'mautic_woo_check_oauth_access_token', 'mauwooSecurity' : mauwooSecurity },
							function(response){

								var oauth_response = jQuery.parseJSON( response );
								var oauth_status   = oauth_response.status;
								var oauthMessage   = oauth_response.message;

								if ( oauth_status ) {

									jQuery.each(
										allFields ,
										function(index, value ) {

											if ( value != "" || ( typeof value !== "undefined")) {

												jQuery.ajax(
													{
														url: ajaxUrl,
														type: 'POST',
														async: false,
														data:{
															action:'mautic_woo_create_single_field2',
															'alias':value ,
															'mauwooSecurity' : mauwooSecurity,
															'totalFields' : allFields.length,
															'current':index,
														},

														dataType: "json",
														success:function(data)
													{
															var percent = Math.ceil( ((index + 1 ) / allFields.length) * 100 );
															jQuery( '.mauwoo-progress-bar' ).css( 'width', percent + '%' );
															jQuery( '.mauwoo-progress-bar' ).html( percent + '%' );
															jQuery( '.mauwoo-progress-notice' ).html( "<p><strong>" + data.label + "</strong> : " + data.message + "</p>" );

															if (allFields.length == (index + 1) ) {
																jQuery.ajax(
																	{
																		url: ajaxUrl,
																		type: 'POST',
																		data:{
																			action:'mautic_woo_setup_completed',
																			'mauwooSecurity' : mauwooSecurity,

																		},
																		dataType: "json",
																		success:function(data)
																	{
																			jQuery( '.mauwoo-progress-notice' ).html( "<p>Your Custom fields setup has been completed!</p>" );
																			location.reload();
																		}
																	}
																);
															}
														}
													}
												);
											}
										}
									);
								}
							}
						);
					}
					count++;

				}
			);

		}
	);

})( jQuery );
