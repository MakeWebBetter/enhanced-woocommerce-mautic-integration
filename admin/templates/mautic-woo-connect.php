<?php
/**
 * All mautic needed general settings.
 *
 * Template for showing/managing all the mautic general settings
 *
 * @since 1.0.0
 * @package  enhanced-woocommerce-mautic-integration
 */

// check if the connect is entered and have valid connect..

global $mautic_woo;

if ( isset( $_POST['mautic_woo_activate_connect'] ) && check_admin_referer( 'mautic-woo-settings' ) ) {

	$settings = wp_unslash( $_POST );

	unset( $settings['mautic_woo_activate_connect'] );

	if ( isset( $settings['mautic_woo_base_url'] ) ) {

		$settings['mautic_woo_base_url'] = esc_url_raw( $settings['mautic_woo_base_url'] );
	}

	if ( isset( $settings['mautic_woo_client_id'] ) ) {

		$settings['mautic_woo_client_id'] = sanitize_text_field( $settings['mautic_woo_client_id'] );
	}

	if ( isset( $settings['mautic_woo_secret_id'] ) ) {

		$settings['mautic_woo_secret_id'] = sanitize_text_field( $settings['mautic_woo_secret_id'] );
	}

	if ( ! empty( $settings ) ) {

		foreach ( $settings as $key => $value ) {

			update_option( $key, $value );
		}
	}

	$message = esc_html__( 'Settings saved successfully.', 'enhanced-woocommerce-mautic-integration' );

	$mautic_woo->mautic_woo_notice( $message, 'success' );
} elseif ( isset( $_GET['action'] ) ) {

	if ( 'changeAccount' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {

		$mautic_woo->mautic_woo_reset_account_settings();
	}
	if ( 'supportDevlopment' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {

		$mautic_woo->mautic_woo_send_support_request();
	}
}


$base_url = $mautic_woo->get_client_mautic_base_url();

$connection_keys = $mautic_woo->get_mautic_connection_keys();

if ( ! empty( $base_url ) && ! empty( $connection_keys ) ) {

	$status_check = $mautic_woo->is_valid_keys_provided();

	if ( 'ok' === $status_check ) {

		$oauth_success = $mautic_woo->is_oauth_success();
		if ( ! $oauth_success ) {

			?>
			<span class="mauwoo_oauth_span">
				<label><?php esc_html_e( 'Please click the button to authorize with Mautic.', 'enhanced-woocommerce-mautic-integration' ); ?></label>
				<a href="<?php echo esc_url( wp_nonce_url( '?page=mautic-woo&action=authorize', 'mautic-woo-get', 'mautic-woo-get' ) ); ?>" class="button-primary"><?php esc_html_e( 'Authorize', 'enhanced-woocommerce-mautic-integration' ); ?></a>
			</span>
			<?php
		}
	} elseif ( 'invalid_url' === $status_check ) {

		$message = esc_html__( 'Invalid Base URL passed for Mautic.', 'enhanced-woocommerce-mautic-integration' );
		$mautic_woo->mautic_woo_notice( $message, 'error' );
	} elseif ( 'empty_keys' === $status_check ) {

		$message = esc_html__( 'Empty keys passed. Please check.', 'enhanced-woocommerce-mautic-integration' );
		$mautic_woo->mautic_woo_notice( $message, 'error' );
	}
}

$mautic_oauth          = Mautic_Woo::is_oauth_success();
$move_to_custom_fields = get_option( 'mautic_woo_move_to_custom_fields', false );

if ( $mautic_oauth && ! $move_to_custom_fields ) {

	?>

	<span class="mauwoo_oauth_span success">
		<label><?php esc_html_e( 'Congratulations! your Mautic account has been successfully verified and connected.', 'enhanced-woocommerce-mautic-integration' ); ?></label>
	</span>

	<?php
}

if ( ! $mautic_woo->is_oauth_success() && ! $mautic_woo->is_valid_client_id_stored() ) {

	$message = esc_html__( 'Enter your Mautic base url, client and secret keys to connect with Mautic. Refer the below section to know more about APP setup in Mautic.', 'enhanced-woocommerce-mautic-integration' );
	?>
		<div class="mauwoo-overview-footer-content-1 mauwoo-footer-container">
			<p><?php esc_html_e( 'Learn more how to setup new APP in Mautic to get keys for connection', 'enhanced-woocommerce-mautic-integration' ); ?></p>
			<a href="#" class="mauwoo-button" id="mauwoo-know-about-app-settings-button"><?php esc_html_e( 'Mautic APP Setup', 'enhanced-woocommerce-mautic-integration' ); ?></a>
		</div>
		<div class="mauwoo-connect-form-header mauwoo-common-header">
			<h2><?php esc_html_e( 'Connect with your Mautic Account', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
			<div class="mauwoo-connect-form-desc"><?php echo esc_html( $message ); ?></div>
		</div>
		<div class="mauwoo-connection-container">
			<form class="mauwoo-connect-form" action="" method="post">
				<div class="mauwoo-connect-base-url">
				<label>
					<?php esc_html_e( 'Mautic Base URL', 'enhanced-woocommerce-mautic-integration' ); ?>
				</label>
					<input placeholder="<?php echo esc_attr( 'http://your-mautic-url.com' ); ?>" class="regular-text" type="text" id="mauwoo_connect_base_url" name="mautic_woo_base_url" value="<?php echo esc_url( $base_url ); ?>" required>
				</div>
				<div class="mauwoo-connect-client-id">
				<label>
					<?php esc_html_e( 'Mautic Client ID', 'enhanced-woocommerce-mautic-integration' ); ?>
				</label>
					<?php $client_id = $connection_keys['client_id']; ?>
					<input placeholder="<?php echo esc_attr_e( 'Mautic APP Client ID', 'enhanced-woocommerce-mautic-integration' ); ?>" class="regular-text" type="password" id="mauwoo_connect_client_id" name="mautic_woo_client_id" value="<?php echo esc_attr( $client_id ); ?>" required>
					<i class="fas fa-eye mauwoo-show-pass"></i>
				</div>

				<div class="mauwoo-connect-secret-id">
					<label>
					<?php esc_html_e( 'Mautic Secret ID', 'enhanced-woocommerce-mautic-integration' ); ?>
					</label>
					<?php $secret_id = $connection_keys['client_secret']; ?>
					<input placeholder="<?php echo esc_attr__( 'Mautic APP Secret ID', 'enhanced-woocommerce-mautic-integration' ); ?>" class="regular-text" type="password" id="mauwoo_connect_secret_id" name="mautic_woo_secret_id" value="<?php echo esc_attr( $secret_id ); ?>" required>
					<i class="fas fa-eye mauwoo-show-pass"></i>
				</div>
				<div class="mauwoo-connect-form-submit">
					<p class="submit">
						<input type="submit" name="mautic_woo_activate_connect" value="<?php echo esc_attr__( 'Save', 'enhanced-woocommerce-mautic-integration' ); ?>" class="button-primary" />
					</p>
					<?php wp_nonce_field( 'mautic-woo-settings' ); ?>
				</div>
			</form>
		</div>
	<?php
} else {

	?>
		<div class="mauwoo-connect-form-header text-center">
			<h2><?php esc_html_e( 'Mautic Connection', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
		</div>
		<div class="mauwoo-change-account text-center">
			<a href="<?php echo esc_url( '?page=mautic-woo&mauwoo_tab=mautic-woo-connect&action=changeAccount' ); ?>" class="mauwoo_connect_page_actions mauwoo-button" id="" ><?php esc_html_e( 'Reset Connection', 'enhanced-woocommerce-mautic-integration' ); ?></a>
			<?php if ( ! get_option( 'mautic_woo_support_request', false ) ) { ?>
			<a href="?page=mautic-woo&mauwoo_tab=mautic-woo-connect&action=supportDevlopment" class="mauwoo_connect_page_actions mauwoo-button mauwoo-button"><?php esc_html_e( 'Support Plugin Development', 'enhanced-woocommerce-mautic-integration' ); ?></a>
			<?php } ?>
		</div>
		<div class="mauwoo-connection-info">
			<div class="mauwoo-connection-status mauwoo-connection">
				<div class="mauwoo-connection-icon"><i class="far fa-check-circle"></i></div>
				<p class="mauwoo-connection-label">
					<?php esc_html_e( 'Connection Status', 'enhanced-woocommerce-mautic-integration' ); ?>
				</p>
				<p class="mauwoo-connection-status-text">
					<?php
					if ( $mautic_woo->is_valid_client_id_stored() ) {

						esc_html_e( 'Connected', 'enhanced-woocommerce-mautic-integration' );
					}
					?>
				</p>
			</div>
					<div class="mauwoo-acc-email mauwoo-connection">
				<div class="mauwoo-connection-icon"><i class="fas fa-envelope-open-text"></i></div>
				<p class="mauwoo-acc-email-label">
					<?php esc_html_e( 'Account Email', 'enhanced-woocommerce-mautic-integration' ); ?>
				</p>
				<p class="mauwoo-connection-status-text">
					<?php
					if ( $mautic_woo->is_valid_client_id_stored() ) {

						$acc_email = $mautic_woo->mautic_woo_account_email_info();

						echo esc_html( $acc_email );
					}
					?>
				</p>
			</div>
			<div class="mauwoo-token-info mauwoo-connection">
				<div class="mauwoo-connection-icon"><i class="far fa-clock"></i></div>
				<p class="mauwoo-token-expiry-label">
					<?php esc_html_e( 'Token Renewal', 'enhanced-woocommerce-mautic-integration' ); ?>
				</p>
				<?php
				if ( $mautic_woo->is_oauth_success() ) {

					if ( $mautic_woo->is_valid_client_id_stored() ) {

						$token_timestamp = get_option( 'mautic_woo_token_expiry', '' );

						if ( ! empty( $token_timestamp ) ) {

							$exact_timestamp = $token_timestamp - time();

							if ( $exact_timestamp > 0 ) {

								?>
									<p class="mauwoo-acces-token-renewal">
									<?php


									/* translators: 1: seconds  2: time */
									$day_string = sprintf( _n( ' In %s second', 'In %s seconds', $exact_timestamp, 'enhanced-woocommerce-mautic-integration' ), number_format_i18n( $exact_timestamp ) );

									$day_string = '<span id="mauwoo-day-count" >' . esc_html( $day_string ) . '</span>';

									//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo $day_string;

									?>
									</p>
									<?php
							} else {
								?>
									<p class="mauwoo-acces-token-renewal">
										<a href="javascript:void(0);" class="mauwoo-refresh-token"><?php esc_html_e( 'Refresh Token', 'enhanced-woocommerce-mautic-integration' ); ?></a>
										<i class="fas fa-circle-notch fa-spin mauwoo-hide "></i>
									</p>
									<?php
							}
						} else {

							?>
								<p class="mauwoo-acces-token-renewal">
									<a href="javascript:void(0);" class="mauwoo-refresh-token"><?php esc_html_e( 'Refresh Token', 'enhanced-woocommerce-mautic-integration' ); ?></a>
									<i class="fas fa-circle-notch fa-spin mauwoo-hide "></i>
								</p>
								<?php
						}
					} else {
						?>
							<p class="mauwoo-acces-token-renewal">
								<a href="javascript:void(0);" class="mauwoo-reauthorize-app"><?php esc_html_e( 'Reauthorize Mautic APP', 'enhanced-woocommerce-mautic-integration' ); ?></a>
							</p>
							<?php
					}
				}
				?>
			</div>
		</div>
		<?php

		$mautic_oauth          = Mautic_Woo::is_oauth_success();
		$move_to_custom_fields = get_option( 'mautic_woo_move_to_custom_fields', false );

		if ( $mautic_oauth && ! $move_to_custom_fields ) {

			$display = 'block';
		} else {
			$display = 'none';
		}

		?>
		<div class="mauwoo_pop_up_wrap" style="display: <?php echo esc_attr( $display ); ?>">
			<div class="pop_up_sub_wrap">
				<p class="updated">
					<?php esc_html_e( 'Congratulations! your Mautic account has been successfully verified and connected.', 'enhanced-woocommerce-mautic-integration' ); ?>
					<br>
					<?php esc_html_e( 'You are ready to go!', 'enhanced-woocommerce-mautic-integration' ); ?>
				</p>

				<?php
				if ( ! get_option( 'mautic_woo_support_request', false ) ) {
					?>
				<div class="button_wrap">
					<a href="javascript:void(0);" class="mauwoo_support_development"><?php esc_html_e( 'Support Plugin Development', 'enhanced-woocommerce-mautic-integration' ); ?></a>
				</div>
				<div class="">
					<a href="javascript:void(0);" class="mauwoo_pro_move_to_custom_fields"><?php esc_html_e( 'Proceed to Next Step', 'enhanced-woocommerce-mautic-integration' ); ?></a>
				</div>
				<?php } else { ?>
				<div class="button_wrap">
					<a href="javascript:void(0);" class="mauwoo_pro_move_to_custom_fields"><?php esc_html_e( 'Proceed to Next Step', 'enhanced-woocommerce-mautic-integration' ); ?></a>
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
			<h3><?php esc_html_e( 'Mautic APP Setup Guide', 'enhanced-woocommerce-mautic-integration' ); ?></h3>
			<div class="mauwoo-app-setup-header-close"><?php esc_html_e( 'X', 'enhanced-woocommerce-mautic-integration' ); ?></div>
		</div>
		<div class="mauwoo-app-setup-body">
			<p><?php esc_html_e( 'You can easily setup a new APP for connection with the extension by following these steps:', 'enhanced-woocommerce-mautic-integration' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'Navigate to Mautic Settings', 'enhanced-woocommerce-mautic-integration' ); ?></li>
			</ul>
			<div>
				<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic-app-setup-steps/app-setup-step-1.png' ); ?>" alt="">
			</div>
			<ul>
				<li><?php esc_html_e( 'Go to API Credentials section to start with new APP.', 'enhanced-woocommerce-mautic-integration' ); ?></li>
			</ul>
			<div>
				<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic-app-setup-steps/app-setup-step-2.png' ); ?>" alt="">
			</div>
			<ul>
				<li><?php esc_html_e( 'Click on New to create a fresh APP in mautic ', 'enhanced-woocommerce-mautic-integration' ); ?></li>
			</ul>
			<div>
				<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic-app-setup-steps/app-setup-step-3.png' ); ?>" alt="">
			</div>
			<ul>
				<li><?php esc_html_e( 'Start creating new APP by filling valid credentials. Selct OAuth2 for authorization protocol, give a new name to APP and then use this Redirect URI for the APP: ', 'enhanced-woocommerce-mautic-integration' ); ?>
					<p><?php echo esc_url( admin_url( 'admin.php' ) ); ?></p>
				</li>
			</ul>

			<div>
				<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic-app-setup-steps/app-setup-step-4.png' ); ?>" alt="">
			</div>
			<ul>
				<li><?php esc_html_e( 'Save the APP and you will get the keys for connection. Use the keys as shown in image', 'enhanced-woocommerce-mautic-integration' ); ?></li>
			</ul>

			<div>
				<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic-app-setup-steps/app-setup-step-5.png' ); ?>" alt="">
			</div>
			<ul>
				<li><?php esc_html_e( 'The refresh token is by default good for 14 days in which the user will need to reauthorize the application with Mautic. However, the refresh token’s expiration time is configurable through Mautic’s Configuration.', 'enhanced-woocommerce-mautic-integration' ); ?></li>
				<li><?php esc_html_e( 'You can set the Access token lifetime and Refresh token lifetime from Mautic > Settings > Configuration > API Settings section. ', 'enhanced-woocommerce-mautic-integration' ); ?></li>
				<li><?php esc_html_e( 'Increasing the lifetime for access token and refresh token before connecting the extension to Mautic will be preferred. This will also avoid the manual reauthorization with Mautic app after refresh token expiration ', 'enhanced-woocommerce-mautic-integration' ); ?></li>
			</ul>

			<div>
				<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic-app-setup-steps/app-setup-step-6.png' ); ?>" alt="">
			</div>
		</div>
	</div>
</div>

