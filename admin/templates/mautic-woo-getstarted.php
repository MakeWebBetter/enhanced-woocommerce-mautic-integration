<?php
/**
 * All mautic needed general settings.
 *
 * Template for showing/managing all the mautic general settings
 *
 * @since 1.0.0
 * @package  enhanced-woocommerce-mautic-integration
 */

?>

<div class="mauwoo-fields-header mauwoo-common-header">
	<h2><?php esc_html_e( 'Get Started', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
</div>
<p class="mauwoo_go_pro">
	<?php esc_html_e( 'Integration with Mautic for WooCommerce plugin allows you to automate your email marketing system to reduce the manual labour involved in tedious marketing masks. It helps sellers to integrate their WooCommerce store with Mautic in just a single click.', 'enhanced-woocommerce-mautic-integration' ); ?>
</p>

<div class=" mautic_woo_get_started mauwoo_getstarted">
	<table>
		<tr>
			<th><?php esc_html_e( 'Feature', 'enhanced-woocommerce-mautic-integration' ); ?></th>
			<th><?php esc_html_e( 'Free ', 'enhanced-woocommerce-mautic-integration' ); ?></th>
			<th><?php esc_html_e( 'Pro', 'enhanced-woocommerce-mautic-integration' ); ?></th>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Contacts Sync', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><?php esc_html_e( 'Registered Users', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><?php esc_html_e( 'Registered, Guest Users and Cart Abandoners', 'enhanced-woocommerce-mautic-integration' ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Custom Fields', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><?php esc_html_e( 'Only 20 Fields', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><?php esc_html_e( '70+ Fields', 'enhanced-woocommerce-mautic-integration' ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Segments', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td><?php esc_html_e( '18 Best Practice Segments', 'enhanced-woocommerce-mautic-integration' ); ?>

			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'RFM Ratings', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Dynamic Copoun Code Generation', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Copoun Code for Segments', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close " aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Field to Field Mapping', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'One-Click Sync for Historical data', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Add tags based on user activity', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		
		<tr>
			<td><?php esc_html_e( 'Abandoned Cart Tracking', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Site Tracking', 'enhanced-woocommerce-mautic-integration' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
	</table>
	<div>
		<p>
			<a id="mauwoo-go-pro-link"
				href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>"
				class="" title="" target="_blank">
				<input type="Button" class="mauwoo-go-pro-now mauwoo-button" name="mautic_woo_save_gensttings"
					value="<?php echo esc_attr_e( 'BUY PREMIUM NOW ', 'enhanced-woocommerce-mautic-integration' ); ?>">
		</p>
		<?php wp_nonce_field( 'mautic-woo-settings' ); ?>
	</div>
</div>
