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
	<h2><?php esc_html_e( 'Get Started', 'mautic-woo' ); ?></h2>
</div>
<p class="mauwoo_go_pro">
	<?php esc_html_e( 'Integration with Mautic for WooCommerce plugin allows you to automate your email marketing system to reduce the manual labour involved in tedious marketing masks. It helps sellers to integrate their WooCommerce store with Mautic in just a single click.', 'mautic-woo' ); ?>
</p>

<div class=" mautic_woo_get_started mauwoo_getstarted">
	<table>
		<tr>
			<th><?php esc_html_e( 'Feature', 'mautic-woo' ); ?></th>
			<th><?php esc_html_e( 'Free ', 'mautic-woo' ); ?></th>
			<th><?php esc_html_e( 'Pro', 'mautic-woo' ); ?></th>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Contacts Sync', 'mautic-woo' ); ?></td>
			<td><?php esc_html_e( 'Registered Users', 'mautic-woo' ); ?></td>
			<td><?php esc_html_e( 'Registered, Guest Users and Cart Abandoners', 'mautic-woo' ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Custom Fields', 'mautic-woo' ); ?></td>
			<td><?php esc_html_e( 'Only 20 Fields', 'mautic-woo' ); ?></td>
			<td><?php esc_html_e( '70+ Fields', 'mautic-woo' ); ?></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Segments', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td><?php esc_html_e( '18 Best Practice Segments', 'mautic-woo' ); ?>

			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'RFM Ratings', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Dynamic Copoun Code Generation', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Copoun Code for Segments', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close " aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Field to Field Mapping', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'One-Click Sync for Historical data', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Add tags based on user activity', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		
		<tr>
			<td><?php esc_html_e( 'Abandoned Cart Tracking', 'mautic-woo' ); ?></td>
			<td><i class="fa fa-times mauwoo_close" aria-hidden="true"></i></span></td>
			<td>
				<div class="mauwoo-field-checked-getstarted ">
					<i class="fas fa-check-circle mauwoo-check"></i>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Site Tracking', 'mautic-woo' ); ?></td>
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
					value="<?php echo esc_attr_e( 'BUY PREMIUM NOW ', 'mautic-woo' ); ?>">
		</p>
		<?php wp_nonce_field( 'mautic-woo-settings' ); ?>
	</div>
</div>
