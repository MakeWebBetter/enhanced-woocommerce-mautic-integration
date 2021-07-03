<?php

if( isset( $_POST[ 'mautic_woo_save_selected_fields' ] ) && check_admin_referer( 'mautic-woo-settings' ) ) {

	unset( $_POST[ 'mautic_woo_save_selected_fields' ] );

	if( !isset( $_POST[ 'selected_properties' ] ) ) {

		$_POST[ 'selected_properties' ] = array();
	}

	update_option( 'mautic_woo_selected_properties', $_POST[ 'selected_properties' ] );

	$message = __( 'Fields saved successfully.' , 'mautic-woo' );

	Mautic_Woo::mautic_woo_notice( $message, 'success' );
}
?>

<div class="mauwoo-fields-header mauwoo-common-header text-center">
	<h2><?php _e("Mautic Custom Fields","mautic-woo") ?></h2>
</div> 
<?php
	$user_choice = Mautic_Woo::mautic_woo_user_choice();

	if( !Mautic_Woo::is_setup_completed() ) {
?>
		<div class="mauwoo-fields-container">
			<?php

				if( empty( $user_choice ) ) {

					$display = "block";
				}
				else {
					$display = "none";
				}
			?>
			<div class="mauwoo_pop_up_wrap" style="display: <?php echo $display; ?>">
				<div class="pop_up_sub_wrap">
					<p>
						<?php _e("Before we start with the custom fields setup, will you like to filter the fields before creating them? Filtering the fields will allow you to select the fields which you want to be created in your Mautic account.","mautic-woo") ?>
					</p>
					<div class="button_wrap">
						<a href="javascript:void(0);" class="mauwoo_pro_select_fields"><?php _e('Yes, allow me to filter','mautic-woo'); ?></a>
						<a href="javascript:void(0);" class="mauwoo_pro_go_with_integration"><?php _e("I will like to go with the extension.",'mautic-woo'); ?></a>
					</div>
				</div>
			</div>
			<div class="mauwoo-fields-on-user-choice">
				<?php 

					if( $user_choice == "no" ) {

						?>
							<div class="mauwoo-fields-display">
								<p><?php _e( "These are the custom fields which will be created by the extension on your Mautic account or if you want, can change your decision.","mautic-woo") ?></p>
								<?php
									$mauwoo_groups = MauticWooContactProperties::get_instance()->_get( 'groups' );
									if( is_array( $mauwoo_groups ) && count( $mauwoo_groups ) ) {
										foreach( $mauwoo_groups as $key => $single_group ) {
											?>
											<div class="mauwoo_groups">
												<table class="form-table">
													<tbody>
														<tr valign="top">
															<th scope="row" class="titledesc">
																<p class="mauwoo_group_name">
																	<?php echo $single_group["displayName"] ?>
																</p>
															</th>
															<td class="forminp forminp-text">
																<table>
															    	<?php 
															    	$mauwoo_properties = MauticWooContactProperties::get_instance()->_get( 'properties', $single_group["name"] );
															    	if( is_array( $mauwoo_properties ) && count( $mauwoo_properties ) ) {
															    		foreach( $mauwoo_properties as $single_property ) {
																    			?>
																	    			<tr>
																	    				<td><?php echo $single_property["label"] ?></td>
																    				</tr>
																    			<?php
																    		}
														    			}
														    		?>
													    		</table>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<?php
									 	}
								 	}	
								?>
							</div>
						<?php
					}
					else {
						?>
						<form action="" method="post">
							<div class="mauwoo-fields-select">
								<p><?php _e( "Start selecting fields those you want to create on your Mautic account or if you want, can change your decision.","mautic-woo") ?></p>
								<div class="mauwoo-actions text-center"><a href="javascript:void(0);" class="mauwoo-action-field mauwoo-button" id="mauwoo-all-fields"><?php _e("Select all","mautic-woo")?></a>
								<a href="javascript:void(0);" class="mauwoo-action-field mauwoo-button" id="mauwoo-clear-fields"><?php _e("Clear","mautic-woo")?></a></div>
								<?php
									$mauwoo_groups = MauticWooContactProperties::get_instance()->_get( 'groups' );
									
									if( is_array( $mauwoo_groups ) && count( $mauwoo_groups ) ) {

										foreach( $mauwoo_groups as $key => $single_group ) {

											?>
											<div class="mauwoo_groups">

												<table class="form-table">

													<tbody>

														<tr valign="top">

															<th scope="row" class="titledesc">

																<p class="mauwoo_group_name">

																	<?php echo $single_group["displayName"] ?>

																</p>

															</th>

															<td class="forminp forminp-text">

																<table>

															    	<?php 

															    	$mauwoo_properties = MauticWooContactProperties::get_instance()->_get( 'properties', $single_group["name"] );

															    	$mauwoo_selected_properties = Mautic_Woo::mautic_woo_user_selected_fields();
															    	
															    	if( is_array( $mauwoo_properties ) && count( $mauwoo_properties ) ) {

															    		foreach( $mauwoo_properties as $single_property ) {

															    			if( in_array( $single_property["alias"], $mauwoo_selected_properties ) ) {
															    			
															    				?>

																	    			<tr>
																	    				<td><?php echo $single_property["label"] ?></td>
																	    				<td><input data-id="<?php echo $key ?>" class="mauwoo_select_property" type="checkbox" checked name="selected_properties[]" value="<?php echo $single_property["alias"] ?>"></td>
																    				</tr>

																    			<?php
																    		}
																    		else {

																    			?>

																	    			<tr>
																	    				<td><?php echo $single_property["label"] ?></td>
																	    				<td><input data-id="<?php echo $key ?>" class="mauwoo_select_property" type="checkbox" name="selected_properties[]" value="<?php echo $single_property["alias"] ?>"></td>
																    				</tr>

																    			<?php
																    		}
														    			}
														    		}
														    		?>
													    		</table>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<?php
									 	}
								 	}	
								?>
								<p class="submit">
									<input type="submit" class="button button-primary" name="mautic_woo_save_selected_fields" value="<?php echo __("Save Changes","mautic-woo") ?>">
									<?php wp_nonce_field( 'mautic-woo-settings' ); ?>
								</p>
							</div>
						</form>
						<?php
					}
				?>
					<?php add_thickbox(); ?>
					<div id="mauwoo-setup-process" style="display: none;">
						<div class="popupwrap">
				          <p> <?php _e('We are setting up, Please do not navigate or reload the page before our confirmation message.', 'mautic-woo')  ?></p>
					      <div class="progress">
					        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					        </div>
					      </div>
					        <div class="mauwoo-message-area">
					        </div>
					    </div>
				    </div>
				<div class="mauwoo-fields-setup">
					<?php if( Mautic_Woo::is_fields_to_create() ): ?>
						<a class="mauwoo-run-fields-setup mauwoo-button mauwoo-button-secondary" href="javascript:void(0);"><?php _e("Start Setup","mautic-woo") ?></a>
					<?php endif;?>
					<a class="mauwoo-button" href="javascript:void(0);"><?php _e("Change Decision","mautic-woo") ?></a>
				</div>
			</div>
		</div>
<?php
	} 
else {

	$final_properties = Mautic_Woo::mautic_woo_get_final_fields();
	
	?>
	<div class="mauwoo-fields-created">
		<div class="mauwoo-fields-created-list">
			<p class="text-center"><?php _e("Fields overview to see which are on your Mautic account. You can create new fields directly from here")?></p>
			<table>
				<thead>
					<tr>
						<th><?php _e('Field Name','mautic-woo') ?></th>
						<th><?php _e('Action','mautic-woo') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
						if( is_array( $final_properties ) && count( $final_properties ) ) {

							foreach( $final_properties as $single_property ) {

								if( isset( $single_property['detail'] ) && !empty( $single_property['status'] ) && $single_property['status'] == 'created' ) {
									?>
									<tr>
										<td>
											<?php echo $single_property['detail']['label']?>
										</td>
										<td>
											<div class="mauwoo-field-checked">
												<img src="<?php echo MAUTIC_WOO_URL;?>admin/images/checked.png">
											</div>
										</td>
									</tr>
									<?php
								}
								else {
									?>
									<tr>
										<td>
											<?php echo $single_property['detail']['label']?>
										</td>
										<td>
											<a href="javascript:void(0);" class="button button-primary mauwoo-create-single-field" data-alias="<?php echo $single_property['detail']['alias'] ?>"><?php echo __('Create','mautic-woo') ?></a>
										</td>
									</tr>
									<?php
								}                               
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}
?>