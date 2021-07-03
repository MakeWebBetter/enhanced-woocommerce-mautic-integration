<?php
/**
 * Provide a admin settings page
 *
 * This file is used to markup the admin settings of the plugin.
 *
 * @package enhanced-woocommerce-mautic-integration
 */

?>
<div class="mauwoo-fields-header mauwoo-common-header text-center">
	<h2><?php esc_attr_e( 'Sync Old Orders and Customers with One Click Sync feature', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
	<a target="_blank" class="mauwoo-button" href=" <?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?> "><?php esc_html_e( 'Get this Feature Now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
</div>
<p class="mauwoo_go_pro text-center"><?php esc_attr_e( 'Date wise syncs all your previous store orders to Mautic with just a single click. ', 'enhanced-woocommerce-mautic-integration' ); ?>
<p class="mauwoo_go_pro text-center"><?php esc_attr_e( 'Syncs all previous store users according to WordPress roles to Mautic with just a single click.. ', 'enhanced-woocommerce-mautic-integration' ); ?>
</p>

<a href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" target="_blank">
	<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic_ocs1.png' ); ?>" >
</a>
<a href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" target="_blank">
	<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic_ocs2.png' ); ?>" >
</a>

