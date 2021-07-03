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
	<h2><?php esc_html_e( 'Field to Field Sync', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
	<a target="_blank" class="mauwoo-button" href=" <?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?> "><?php esc_html_e( 'Get this Feature Now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
</div>
<p class="mauwoo_go_pro text-center"><?php esc_html_e( 'Easily Map existing mautic contact properties with the WordPress users’ fields (custom/default both). ', 'enhanced-woocommerce-mautic-integration' ); ?>
</p>

<a href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" target="_blank">
	<img class="mautic-woo-center-image" src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic_ftf.jpg' ); ?>">
</a>
