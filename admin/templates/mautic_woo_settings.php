
<div class="mauwoo-settings-header mauwoo-common-header">
	<h2><?php _e("General Settings","mautic-woo") ?></h2>
</div>
<div class="mauwoo-settings-container">
	<div class="mauwoo-general-settings">
		<?php

		if( isset( $_POST['mautic_woo_save_gensttings'] ) && check_admin_referer( 'mautic-woo-settings' ) ) {
			
			unset( $_POST['mautic_woo_save_gensttings'] );


			if( !isset( $_POST[ 'mautic-woo-selected-order-status' ] ) ) {

				$_POST['mautic-woo-selected-order-status'] =  array();
			}

			if ( !isset( $_POST[ 'mautic-woo-disabled-custom-fields' ] ) ) {

				$_POST['mautic-woo-disabled-custom-fields'] =  array();
			}

			foreach( $_POST as $key => $value ) {

				update_option( $key, $value );
			}

			$message = __("Settings saved","mautic-woo");
			Mautic_Woo::mautic_woo_notice( $message, 'success' );
		}
		?>
		<form action="" method="post">
			<div class="mauwoo-order-status">
				<label for="mauwoo-selected-order-status"><?php _e("Sync orders with status","mautic-woo"); ?></label>
				<?php
				$desc = __('The orders with selected status will only be synced to Mautic. Default will be all order statuses.','mautic-woo');
				echo wc_help_tip( $desc );
				?>
				<select multiple="multiple" id="mauwoo-order-statuses" name="mautic-woo-selected-order-status[]" data-placeholder="<?php esc_attr_e( 'Select Order Statuses', 'mautic-woo' ); ?>">
					<?php

					$selected_order_statuses = get_option( "mautic-woo-selected-order-status", array() );

					$wc_order_statuses = wc_get_order_statuses();

					foreach ( $selected_order_statuses as $single_status ) 
					{
						if( array_key_exists( $single_status, $wc_order_statuses ) )
						{
							echo '<option value="'.$single_status. '" selected="selected">'.$wc_order_statuses[$single_status].'</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="mauwoo-order-status">
				<label for="mauwoo-disabled-custom-fields"><?php _e("Disable Custom fields","mautic-woo"); ?></label>
				<?php
				$desc = __('The data sync will be stop for selected fields','mautic-woo');
				echo wc_help_tip( $desc );
				?>
				<select multiple="multiple" class="mauwoo-disabled-fields" id="mautic-woo-disabled-custom-fields" name="mautic-woo-disabled-custom-fields[]" data-placeholder="<?php esc_attr_e( 'Select Custom fields', 'mautic-woo' ); ?>">
					
					<?php 

					$disabled_custom_fields = get_option("mautic-woo-disabled-custom-fields" , array());


					$all_properties = MauticWooContactProperties::get_instance()->_get( 'properties') ; 

					foreach ($all_properties as $key => $properties) {

						foreach ($properties as $k => $v) {
							
							if(in_array($v['alias'], $disabled_custom_fields)){

								echo '<option value="'.$v['alias']. '" selected="selected">'.$v['label'].'</option>';
							}
						}
					}

					?>
				</select>
			</div>
			<div class="mauwoo-order-status">
				<label for="mauwoo-disabled-custom-fields"><?php _e("Custom Contact Tags","mautic-woo"); ?></label>
				<?php
				$desc = __('Enter text to be added as tags to each contact. For multiple tags separate them with comma, i.e. tag1 , tag2','mautic-woo');
				echo wc_help_tip( $desc );

				$custom_tags = get_option("mautic-woo-custom-tags" , "");

				?>
				<input type="text" name="mautic-woo-custom-tags" value="<?php echo $custom_tags ;?>" class="mautic-woo-custom-tags" placeholder="<?php echo 'tag1 , tag2' ;?>">
			</div>

			<p class="submit">
				<input type="submit" class="mauwoo-button" name="mautic_woo_save_gensttings" value="<?php _e("Save settings","mautic-woo") ?>">
			</p>
			<?php wp_nonce_field( 'mautic-woo-settings' ); ?>
		</form>
	</div>
</div> 

