<?php
/**
 * Provide a admin settings page
 *
 * This file is used to markup the admin settings of the plugin.
 *
 * @package enhanced-woocommerce-mautic-integration
 */

$mautic_woo_selected_properties = Mautic_Woo::mautic_woo_user_selected_fields();
$final_properties               = Mautic_Woo::mautic_woo_get_final_fields();

?>

<div class="mauwoo-fields-header mauwoo-common-header text-center">
	<h2><?php esc_html_e( 'Mautic Custom Fields', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
</div>
<div class="mauwoo-fields-notice">
</div>
<div class="mauwoo-progress-wrap" style="display: none;">
	<div class="mauwoo-progress-notice">
	</div>
	<div class="mauwoo-progress">
		<div class="mauwoo-progress-bar" role="progressbar" style="width:">
		</div>
	</div>
	<div class="mauwoo-progress-notice-bottom">
		<?php esc_html_e( "Hold on we are creating your custom fields, Please don't refresh the page.", 'enhanced-woocommerce-mautic-integration' ); ?>
	</div>
</div>

<?php if ( ! Mautic_Woo::is_setup_completed() ) : ?>

<div class="mauwoo-fields-container">
	<div class="mauwoo-fields-on-user-choice">
		<form action="" method="post">
			<div class="mauwoo-fields-head-text">
				<?php esc_html_e( 'Select the custom fields you want to create in your Mautic account and start the setup', 'enhanced-woocommerce-mautic-integration' ); ?>
			</div>
			<div class="mauwoo-actions">
				<a href="javascript:void(0);" class="mauwoo-action-field mauwoo-button mauwoo-small-button"
					id="mauwoo-all-fields"><?php esc_html_e( 'Select all', 'enhanced-woocommerce-mautic-integration' ); ?></a>
				<a href="javascript:void(0);" class="mauwoo-action-field mauwoo-button mauwoo-small-button"
					id="mauwoo-clear-fields"><?php esc_html_e( 'Clear', 'enhanced-woocommerce-mautic-integration' ); ?></a>
			</div>
			<div class="mauwoo-fields-select">

				<?php
				$mauwoo_groups = MauticWooContactProperties::get_instance()->_get( 'groups' );

				if ( is_array( $mauwoo_groups ) && count( $mauwoo_groups ) ) {

					foreach ( $mauwoo_groups as $key => $single_group ) {
						?>
				<div class="mauwoo_groups">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row" class="titledesc">
									<p class="mauwoo_group_name">
										<?php echo esc_html( $single_group['displayName'] ); ?>
									</p>
								</th>
								<td class="forminp forminp-text">
									<table>
										<?php
										$mauwoo_properties = MauticWooContactProperties::get_instance()->_get( 'properties', $single_group['name'] );

										$mauwoo_selected_properties = Mautic_Woo::mautic_woo_user_selected_fields();

										if ( is_array( $mauwoo_properties ) && count( $mauwoo_properties ) ) {

											foreach ( $mauwoo_properties as $single_property ) {

												if ( in_array( $single_property['alias'], $mauwoo_selected_properties, true ) ) {

													?>
										<tr>
											<td><?php echo esc_html( $single_property['label'] ); ?></td>
											<td><input data-id="<?php echo esc_attr( $key ); ?>"
													class="mauwoo_select_property" type="checkbox" checked
													name="selected_properties[]"
													value="<?php echo esc_attr( $single_property['alias'] ); ?>"></td>
										</tr>
													<?php
												} else {

													?>
										<tr>
											<td><?php echo esc_html( $single_property['label'] ); ?></td>
											<td><input data-id="<?php echo esc_attr( $key ); ?>"
													class="mauwoo_select_property" type="checkbox"
													name="selected_properties[]"
													value="<?php echo esc_attr( $single_property['alias'] ); ?>"></td>
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
			</div>
			<input type="button" class="mauwoo-button" name="mauwoo_create_selected_fields"
				value="<?php echo esc_html__( 'Start Setup', 'enhanced-woocommerce-mautic-integration' ); ?>" id="mauwoo2-create-selected-fields">
		</form>
	</div>
</div>
<?php else : ?>



<p class="text-center">
	<?php esc_html_e( 'Overview of fields available on your Mautic Account. You can create new field directly from here.', ' enhanced-woocommerce-mautic-integration' ); ?>
</p>
<div class="mauwoo-fields-created">
	<div class="mauwoo-fields-created-list mauwoo_groups">
		<table>
			<thead>
				<tr>
					<th><?php esc_html_e( 'Field Name', 'enhanced-woocommerce-mautic-integration' ); ?></th>
					<th><?php esc_html_e( 'Action', 'enhanced-woocommerce-mautic-integration' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( is_array( $final_properties ) && count( $final_properties ) ) {

					foreach ( $final_properties as $single_property ) {

						if ( isset( $single_property['detail'] ) && ! empty( $single_property['status'] ) && 'created' === $single_property['status'] ) {
							?>
				<tr>
					<td>
									<?php echo esc_html( $single_property['detail']['label'] ); ?>
					</td>
					<td>
						<div class="mauwoo-field-checked">
							<i class="fas fa-check-circle mauwoo-check"></i>
						</div>
					</td>
				</tr>
							<?php
						} else {
							?>
				<tr>
					<td>
									<?php echo esc_html( $single_property['detail']['label'] ); ?>
					</td>
					<td class="mauwoo-field-checked">
						<a href="javascript:void(0);" class="button button-primary mauwoo-create-single-field"
							data-alias="<?php echo esc_attr( $single_property['detail']['alias'] ); ?>"><?php esc_html_e( 'Create', 'enhanced-woocommerce-mautic-integration' ); ?></a>
						<i class="fas fa-circle-notch fa-spin mauwoo-hide mauwoo-spinner"></i>
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



<?php endif; ?>
