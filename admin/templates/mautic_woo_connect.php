<?php 

/**
 * All mautic needed general settings.
 *
 * Template for showing/managing all the mautic general settings
 *
 * @since 1.0.0 
 */
//check if the connect is entered and have valid connect..

global $mautic_woo;

if( isset( $_POST['mautic_woo_activate_connect'] ) && check_admin_referer( 'mautic-woo-settings' ) ) {

	unset( $_POST['mautic_woo_activate_connect'] );

	if( isset( $_POST["mautic_woo_base_url"] ) ) {

		$_POST["mautic_woo_base_url"] = sanitize_text_field( $_POST["mautic_woo_base_url"] );
	}

	if( !empty( $_POST ) ) {

		foreach( $_POST as $key => $value ) {

			update_option( $key, $value );
		}
	}

	$message = __( 'Settings saved successfully.' , 'mautic-woo' );

	$mautic_woo->mautic_woo_notice( $message, 'success' );
}

elseif( isset( $_GET["action"] ) && $_GET["action"] == "changeAccount" ) {

	$mautic_woo->mautic_woo_reset_account_settings();
}

elseif( isset( $_GET["action"] ) && $_GET["action"] == "supportDevlopment" ) {

	$mautic_woo->mautic_woo_send_support_request();
}

$base_url =  $mautic_woo->get_client_mautic_base_url();

$connection_keys =  $mautic_woo->get_mautic_connection_keys();

if( !empty( $base_url ) && !empty( $connection_keys ) ) {

	$status_check = $mautic_woo->is_valid_keys_provided();

	if( $status_check == "ok" ) {

		$oauth_success = $mautic_woo->is_oauth_success();

		if( !$oauth_success ) {

			?>
			<span class="mauwoo_oauth_span">
				<label><?php _e('Please click the button to authorize with Mautic.','mautic-woo'); ?></label>
				<a href="?page=mautic-woo&action=authorize" class="button-primary"><?php _e("Authorize","mautic-woo")?></a>
			</span>
			<?php
		}
	}
	elseif( $status_check == "invalid_url" ) {

		$message = __( 'Invalid Base URL passed for Mautic.', 'mautic-woo' );
		$mautic_woo->mautic_woo_notice( $message, 'error' );
	}
	elseif( $status_check == "empty_keys" ) {

		$message = __( 'Empty keys passed. Please check.', 'mautic-woo' );
		$mautic_woo->mautic_woo_notice( $message, 'error' );
	} 
}

if( !$mautic_woo->is_oauth_success() && !$mautic_woo->is_valid_client_id_stored() ) {

	$message = __( 'Enter your Mautic base url, client and secret keys to connect with Mautic. Refer the below section to know more about APP setup in Mautic.' , 'mautic-woo' );
	?>
		<div class="mauwoo-overview-footer-content-1 mauwoo-footer-container">
			<p><?php _e("Learn more how to setup new APP in Mautic to get keys for connection","mautic-woo");?></p>
            <a href="#" class="mauwoo-button" id="mauwoo-know-about-app-settings-button"><?php _e( "APP SETUP","mautic-woo")?></a>
        </div>
		<div class="mauwoo-connect-form-header mauwoo-common-header">
			<h2><?php _e("Connect with your Mautic Account","mautic-woo") ?></h2>
			<div class="mauwoo-connect-form-desc"><?php echo $message ?></div>
		</div>
		<div class="mauwoo-connection-container">
			<form class="mauwoo-connect-form" action="" method="post">
			  	<div class="mauwoo-connect-base-url">
			  		<label>
			  			<?php _e("Mautic Base URL","mautic-woo") ?>
			  		</label>
			        <input placeholder="<?php _e("http://your-mautic-url.com","mautic-woo")?>" class="regular-text" type="text" id="mauwoo_connect_base_url" name="mautic_woo_base_url" value="<?php echo $base_url ?>" required>
			    </div>
			    <?php  ?>
			    <div class="mauwoo-connect-client-id">
			  		<label>
			  			<?php _e("Mautic Client ID","mautic-woo") ?>
			  		</label>
			  		<?php $client_id = $connection_keys['client_id']?>
			        <input placeholder="<?php _e("Mautic APP Client ID","mautic-woo") ?>" class="regular-text" type="password" id="mauwoo_connect_client_id" name="mautic_woo_client_id" value="<?php echo $client_id?>" required>
			    </div>
			    <div class="mauwoo-connect-secret-id">
			  		<label>
			  			<?php _e("Mautic Secret ID","mautic-woo") ?>
			  		</label>
			  		<?php $secret_id = $connection_keys['client_secret'] ?>
			        <input placeholder="<?php _e("Mautic APP Secret ID","mautic-woo") ?>" class="regular-text" type="password" id="mauwoo_connect_secret_id" name="mautic_woo_secret_id" value="<?php echo $secret_id?>" required>
			    </div>
			    <div class="mauwoo-connect-form-submit">
				    <p class="submit">
				        <input type="submit" name="mautic_woo_activate_connect" value="<?php _e("Save","mautic-woo")?>" class="button-primary" />
				    </p>
				    <?php wp_nonce_field( 'mautic-woo-settings' ); ?>
			    </div>
		    </form>
		</div>
	<?php
}
else {

	?>
		<div class="mauwoo-connect-form-header text-center">
			<h2><?php _e("Mautic Connection","mautic-woo") ?></h2>
		</div>
		<div class="mauwoo-change-account text-center">
			<a href="?page=mautic-woo&mauwoo_tab=mautic_woo_connect&action=changeAccount" class="mauwoo_connect_page_actions mauwoo-button" id="" ><?php _e('Reset Connection', 'mauwoo' ); ?></a>
			<?php if ( ! get_option('mautic_woo_support_request' , false)) { ?>
			<a href="?page=mautic-woo&mauwoo_tab=mautic_woo_connect&action=supportDevlopment" class="mauwoo_connect_page_actions mauwoo-button mauwoo-button-secondary"><?php _e( 'Support Plugin Development', 'mauwoo' ); ?></a>
			<?php } ?>
		</div>
		<div class="mauwoo-connection-info">
			<div class="mauwoo-connection-status mauwoo-connection">
				<img src="<?php echo MAUTIC_WOO_URL . 'admin/images/connected.png' ?>">
				<p class="mauwoo-connection-label">
					<?php _e( "Connection Status","mautic-woo") ?>
				</p>
				<p class="mauwoo-connection-status-text">
					<?php
						if( $mautic_woo->is_valid_client_id_stored() ) {

							_e( "Connected","mautic-woo");
						}
					?>
				</p>
			</div>
			<div class="mauwoo-acc-email mauwoo-connection">
				<img src="<?php echo MAUTIC_WOO_URL . 'admin/images/email-icon.png' ?>">
				<p class="mauwoo-acc-email-label">
					<?php _e( "Account Email","mautic-woo") ?>
				</p>
				<p class="mauwoo-connection-status-text">
					<?php
						if( $mautic_woo->is_valid_client_id_stored() ) {

							$acc_email = $mautic_woo->mautic_woo_account_email_info();

							echo $acc_email;
						}
					?>
				</p>
			</div>
			<div class="mauwoo-token-info mauwoo-connection">
				<img src="<?php echo MAUTIC_WOO_URL . 'admin/images/timer.png' ?>">
				<p class="mauwoo-token-expiry-label">
					<?php _e( "Token Renewal","mautic-woo") ?>
				</p>
				<?php
					if( $mautic_woo->is_oauth_success() ) {

						if( $mautic_woo->is_valid_client_id_stored() ) {

							$token_timestamp = get_option( "mautic_woo_token_expiry", '' );

							if( !empty( $token_timestamp ) ) {

								$exact_timestamp = $token_timestamp - time();

								if( $exact_timestamp > 0 ) {

									?>
									<p class="mauwoo-acces-token-renewal">
										<?php

										$day_string = sprintf( _n( ' In %s second', 'In %s seconds', $exact_timestamp, 'mautic-woo' ), number_format_i18n( $exact_timestamp ) );

										$day_string = '<span id="mauwoo-day-count" >'.$day_string.'</span>';
										echo $day_string;
										?>
									</p>
									<?php
								}
								else {
									?>
									<p class="mauwoo-acces-token-renewal">
										<a href="javascript:void(0);" class="mauwoo-refresh-token"><?php _e("Refresh Token","mautic-woo")?></a>
									</p>
									<?php
								}
							}
							else {

								?>
								<p class="mauwoo-acces-token-renewal">
									<a href="javascript:void(0);" class="mauwoo-refresh-token"><?php _e("Refresh Token","mautic-woo")?></a>
								</p>
								<?php
							}
						}
						else {
							?>
							<p class="mauwoo-acces-token-renewal">
								<a href="javascript:void(0);" class="mauwoo-reauthorize-app"><?php _e("Reauthorize Mautic APP","mautic-woo")?></a>
							</p>
							<?php
						}
					}
				?>
			</div>
		</div>
		<?php

		$mautic_oauth = Mautic_Woo::is_oauth_success();
		$move_to_custom_fields = get_option( "mautic_woo_move_to_custom_fields", false );

		if( $mautic_oauth && !$move_to_custom_fields ) {

			$display = "block";
		}
		else {
			$display = "none";
		}

		?>
		<div class="mauwoo_pop_up_wrap" style="display: <?php echo $display; ?>">
			<div class="pop_up_sub_wrap">
				<p class="updated">
					<?php _e("Congratulations! your Mautic account has been successfully verified and connected.","mautic-woo") ?>
					<br>
					<?php _e("You are ready to go!","mautic-woo") ?>
				</p>

				<?php 
				if (! get_option('mautic_woo_support_request' , false)) { ?>
				<div class="button_wrap">
					<a href="javascript:void(0);" class="mauwoo_support_development"><?php _e('Support Plugin Development','mautic-woo'); ?></a>
				</div>
				<div class="">
					<a href="javascript:void(0);" class="mauwoo_pro_move_to_custom_fields"><?php _e('Proceed to Next Step','mautic-woo'); ?></a>
				</div>
				<?php } else { ?>
				<div class="button_wrap">
					<a href="javascript:void(0);" class="mauwoo_pro_move_to_custom_fields"><?php _e('Proceed to Next Step','mautic-woo'); ?></a>
				</div>

				<?php } ?>
				
			</div>
		</div>
	<?php
}
?>
<div class="mauwoo-app-setup-wrapper">
	<div class="mauwoo-app-setup-content">
		<div class="mauwoo-app-setup-header">
			<h3><?php _e("Mautic APP Setup Guide","mautic-woo")?></h3>
			<div class="mauwoo-app-setup-header-close"><?php _e("X","mautic-woo")?></div>
		</div>
		<div class="mauwoo-app-setup-body">
			<p><?php _e("You can easily setup a new APP for connection with the extension by following these steps:","mautic-woo")?></p>
			<ul>
				<li><?php _e("Navigate to Mautic Settings","mautic-woo")?></li>
			</ul>
			<div>
				<img src="<?php echo MAUTIC_WOO_URL;?>admin/images/mautic-app-setup-steps/app-setup-step-1.png" alt="">
			</div>
			<ul>
				<li><?php _e("Go to API Credentials section to start with new APP.","mautic-woo")?></li>
			</ul>
			<div>
				<img src="<?php echo MAUTIC_WOO_URL;?>admin/images/mautic-app-setup-steps/app-setup-step-2.png" alt="">
			</div>
			<ul>
				<li><?php _e("Click on New to create a fresh APP in mautic ","mautic-woo","mautic-woo")?></li>
			</ul>
			<div>
				<img src="<?php echo MAUTIC_WOO_URL;?>admin/images/mautic-app-setup-steps/app-setup-step-3.png" alt="">
			</div>
			<ul>
				<li><?php _e("Start creating new APP by filling valid credentials. Selct OAuth2 for authorization protocol, give a new name to APP and then use this Redirect URI for the APP: ","mautic-woo")?>
					<p><?php echo admin_url('admin.php');?></p>
				</li>
			</ul>

			<div>
				<img src="<?php echo MAUTIC_WOO_URL;?>admin/images/mautic-app-setup-steps/app-setup-step-4.png" alt="">
			</div>
			<ul>
				<li><?php _e("Save the APP and you will get the keys for connection. Use the keys as shown in image","mautic-woo")?></li>
			</ul>

			<div>
				<img src="<?php echo MAUTIC_WOO_URL;?>admin/images/mautic-app-setup-steps/app-setup-step-5.png" alt="">
			</div>
			<ul>
				<li><?php _e("The refresh token is by default good for 14 days in which the user will need to reauthorize the application with Mautic. However, the refresh token’s expiration time is configurable through Mautic’s Configuration.","mautic-woo")?></li>
				<li><?php _e("You can set the Access token lifetime and Refresh token lifetime from Mautic > Settings > Configuration > API Settings section. ","mautic-woo")?></li>
				<li><?php _e("Increasing the lifetime for access token and refresh token before connecting the extension to Mautic will be preferred. This will also avoid the manual reauthorization with Mautic app after refresh token expiration ","mautic-woo")?></li>
			</ul>

			<div>
				<img src="<?php echo MAUTIC_WOO_URL;?>admin/images/mautic-app-setup-steps/app-setup-step-6.png" alt="">
			</div>
		</div>
	</div>
</div>

