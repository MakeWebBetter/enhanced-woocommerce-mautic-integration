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
	<h2><?php esc_html_e( 'Capture Abandoned Cart Data', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
	<a target="_blank" class="mauwoo-button" href=" <?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?> "><?php esc_html_e( 'Get this Feature Now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
</div>
<p class="mauwoo_go_pro text-center"><?php esc_html_e( 'Sync abandoned cart data to mautic for your exsisting customer and guest users', 'enhanced-woocommerce-mautic-integration' ); ?>
<p class="mauwoo_go_pro text-center"><?php esc_html_e( 'Convert your visitors into your customers, send them personalised emails', 'enhanced-woocommerce-mautic-integration' ); ?>
</p>

<a href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" target="_blank">
	<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/abn1.png' ); ?>" >
</a>
<a href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" target="_blank">
	<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/abn2.png' ); ?>" >
</a>

