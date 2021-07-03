<?php
/**
 * Provide a admin settings page
 *
 * This file is used to markup the admin settings of the plugin.
 *
 * @package enhanced-woocommerce-mautic-integration
 */

?>
<div class="mauwoo-settings-header mauwoo-common-header">
	<h2><?php esc_html_e( 'General Settings', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
</div>
<div class="mauwoo-settings-container">
	<div class="mauwoo-general-settings">
		<?php

		if ( isset( $_POST['mautic_woo_save_gensttings'] ) && check_admin_referer( 'mautic-woo-settings' ) ) {

			$settigns = wp_unslash( $_POST );

			unset( $settigns['mautic_woo_save_gensttings'] );

			if ( ! isset( $settigns['mautic-woo-selected-order-status'] ) ) {

				$settigns['mautic-woo-selected-order-status'] = array();
			}

			if ( ! isset( $settigns['mautic-woo-disabled-custom-fields'] ) ) {

				$settigns['mautic-woo-disabled-custom-fields'] = array();
			}

			foreach ( $settigns as $key => $value ) {

				if ( is_array( $value ) ) {

					$sanitized_value = array();
					foreach ( $value as $k => $v ) {
						$sanitized_value[ $k ] = sanitize_text_field( $v );
					}

					$value = $sanitized_value;
				}

				update_option( $key, $value );
			}

			$message = esc_html__( 'Settings saved', 'enhanced-woocommerce-mautic-integration' );
			Mautic_Woo::mautic_woo_notice( $message, 'success' );
		}
		?>
		<form action="" method="post">
			<div class="mauwoo-order-status">
				<label for="mauwoo-selected-order-status"><?php esc_html_e( 'Sync orders with status', 'enhanced-woocommerce-mautic-integration' ); ?></label>
				<?php
				$desc = __( 'The orders with selected status will only be synced to Mautic. Default will be all order statuses.', 'enhanced-woocommerce-mautic-integration' );
				echo wp_kses_post( wc_help_tip( $desc ) );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
				?>
				<select multiple="multiple" id="mauwoo-order-statuses" name="mautic-woo-selected-order-status[]" data-placeholder="<?php esc_attr_e( 'Select Order Statuses', 'enhanced-woocommerce-mautic-integration' ); ?>">
					<?php

					$selected_order_statuses = get_option( 'mautic-woo-selected-order-status', array() );

					$wc_order_statuses = wc_get_order_statuses();

					foreach ( $selected_order_statuses as $single_status ) {
						if ( array_key_exists( $single_status, $wc_order_statuses ) ) {
							echo '<option value="' . esc_attr( $single_status ) . '" selected="selected">' . esc_html( $wc_order_statuses[ $single_status ] ) . '</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="mauwoo-order-status">
				<label for="mauwoo-disabled-custom-fields"><?php esc_html_e( 'Disable Custom fields', 'enhanced-woocommerce-mautic-integration' ); ?></label>
				<?php
				$desc = esc_html__( 'The data sync will be stop for selected fields', 'enhanced-woocommerce-mautic-integration' );
				echo wp_kses_post( wc_help_tip( $desc ) );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */

				?>
				<select multiple="multiple" class="mauwoo-disabled-fields" id="mautic-woo-disabled-custom-fields" name="mautic-woo-disabled-custom-fields[]" data-placeholder="<?php esc_attr_e( 'Select Custom fields', 'enhanced-woocommerce-mautic-integration' ); ?>">	
					<?php

					$disabled_custom_fields = get_option( 'mautic-woo-disabled-custom-fields', array() );


					$all_properties = MauticWooContactProperties::get_instance()->_get( 'properties' );

					foreach ( $all_properties as $key => $properties ) {

						foreach ( $properties as $k => $v ) {

							if ( in_array( $v['alias'], $disabled_custom_fields, true ) ) {

								echo '<option value="' . esc_attr( $v['alias'] ) . '" selected="selected">' . esc_html( $v['label'] ) . '</option>';
							}
						}
					}

					?>
				</select>
			</div>
			<div class="mauwoo-order-status">
				<label for="mauwoo-disabled-custom-fields"><?php esc_html_e( 'Custom Contact Tags', 'enhanced-woocommerce-mautic-integration' ); ?></label>
				<?php
				$desc = esc_html__( 'Enter text to be added as tags to each contact. For multiple tags separate them with comma, i.e. tag1 , tag2', 'enhanced-woocommerce-mautic-integration' );
				echo wp_kses_post( wc_help_tip( $desc ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */


				$custom_tags = get_option( 'mautic-woo-custom-tags', '' );

				?>
				<input type="text" name="mautic-woo-custom-tags" value="<?php echo esc_attr( $custom_tags ); ?>" class="mautic-woo-custom-tags" placeholder="<?php echo esc_attr( 'tag1 , tag2' ); ?>">
			</div>
			<div class="mauwoo-order-status">
				<label for="mautic_woo_sync_method"><?php esc_html_e( 'Preferred sync method', 'enhanced-woocommerce-mautic-integration' ); ?></label>
				<?php
					$mautic_woo_sync_method = get_option( 'mautic_woo_sync_method', 'instant' );

					$attribute_description = esc_html__( 'Select your preferred method to sync user activity on Mautic.', 'enhanced-woocommerce-mautic-integration' );

					echo wp_kses_post( wc_help_tip( $attribute_description ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */

				?>

				<select class="mauwoo-order-status" name="mautic_woo_sync_method">
					<?php
					if ( 'cron' === $mautic_woo_sync_method ) {
						?>
							<option selected value="cron"><?php esc_html_e( 'In every 5 minutes', 'enhanced-woocommerce-mautic-integration' ); ?></option>
							<option value="instant"><?php esc_html_e( 'Instant Sync', 'enhanced-woocommerce-mautic-integration' ); ?></option>
							<?php
					} else {
						?>
							<option value="cron"><?php esc_html_e( 'In every 5 minutes', 'enhanced-woocommerce-mautic-integration' ); ?></option>
							<option selected value="instant"><?php esc_html_e( 'Instant Sync', 'enhanced-woocommerce-mautic-integration' ); ?></option>
							<?php
					}
					?>
				</select>
			</div>

			<p class="submit">
				<input type="submit" class="mauwoo-button" name="mautic_woo_save_gensttings" value="<?php esc_html_e( 'Save settings', 'enhanced-woocommerce-mautic-integration' ); ?>">
			</p>
			<?php wp_nonce_field( 'mautic-woo-settings' ); ?>
		</form>
	</div>
</div> 

