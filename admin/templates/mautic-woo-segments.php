<?php
/**
 * All mautic needed segment settings.
 *
 * Template for showing/managing all the mautic segment settings
 *
 * @since 1.0.0
 * @package  enhanced-woocommerce-mautic-integration
 */

?>
<div class="mauwoo-fields-header mauwoo-common-header text-center">
	<h2><?php esc_html_e( 'Segment your woocommerce users', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
	<a target="_blank" class="mauwoo-button" href=" <?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?> "><?php esc_html_e( 'Get this Feature Now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
</div>
<p class="mauwoo_go_pro text-center"><?php esc_html_e( 'Create Contact Segments in mautic on the basis of your WooCommerce store data.', 'enhanced-woocommerce-mautic-integration' ); ?>
</p>

<a  class="mauwoo-image-scroll" href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" target="_blank">
	<img style="width:70%;" src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/mautic_segments.png' ); ?>">
</a>
