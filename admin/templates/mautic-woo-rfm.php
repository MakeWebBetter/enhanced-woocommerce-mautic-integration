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
<h2><?php esc_html_e( 'Target your customers using RFM settings', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
<a target="_blank" class="mauwoo-button" href=" <?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?> "><?php esc_html_e( 'Get this Feature Now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
</div>
<p class="mauwoo_go_pro text-center">
<?php esc_html_e( 'Create different types of customer segments with RFM analysis, Instead of reaching out to 100% of your audience, you need to identify and target only specific customer groups that will turn out to be most profitable for your business.', 'enhanced-woocommerce-mautic-integration' ); ?>
</p>

<a class="mauwoo-image-scroll"
href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>"
target="_blank">
<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic_rfm.png' ); ?>">
</a>
