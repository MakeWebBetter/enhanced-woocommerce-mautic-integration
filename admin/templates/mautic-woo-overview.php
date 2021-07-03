<?php
/**
 * All mautic needed general settings.
 *
 * Template for showing/managing all the mautic general settings
 *
 * @since 1.0.0
 * @package  enhanced-woocommerce-mautic-integratio
 */

$GLOBALS['hide_save_button'] = true;
?>
<div class="mauwoo-overview-wrapper">
	<div class="mauwoo-overview-header mauwoo-common-header">
		<h2><?php esc_html_e( 'How our Integration works?', 'enhanced-woocommerce-mautic-integration' ); ?></h2>
	</div>
	<div class="mauwoo-overview-body">
		<div class="mauwoo-what-we-do mauwoo-overview-container">
			<h4><?php esc_html_e( 'What we create?', 'enhanced-woocommerce-mautic-integration' ); ?></h4>
			<div class="mauwoo-custom-fields">
				<a class="mauwoo-anchors" href="#"><?php esc_html_e( 'Contact Fields', 'enhanced-woocommerce-mautic-integration' ); ?></a>
			</div>
			<p class="mauwoo-desc-num">1</p>
		</div>
		<div class="mauwoo-how-easy-to-setup mauwoo-overview-container">
			<h4><?php esc_html_e( 'How easy is it?', 'enhanced-woocommerce-mautic-integration' ); ?></h4>
			<div class="mauwoo-setup">
				<a class="mauwoo-anchors" href="#"><?php esc_html_e( 'Just 2 steps to Go!', 'enhanced-woocommerce-mautic-integration' ); ?></a>
			</div>
			<p class="mauwoo-desc-num">2</p>
		</div>
		<div class="mauwoo-what-you-achieve mauwoo-overview-container">
			<h4><?php esc_html_e( 'What at the End?', 'enhanced-woocommerce-mautic-integration' ); ?></h4>
			<div class="mauwoo-automation">
				<a class="mauwoo-anchors" href="#"><?php esc_html_e( 'Automated Marketing', 'enhanced-woocommerce-mautic-integration' ); ?></a>
			</div>
			<p class="mauwoo-desc-num">3</p>
		</div>
	</div>
	<div class="mauwoo-overview-footer">
		<div class="mauwoo-overview-footer-content-2 mauwoo-footer-container">

			<?php
			if ( get_option( 'mautic_woo_get_started', false ) ) {
				?>
			<a href="<?php echo esc_url( '?page=mautic-woo&mauwoo_tab=mautic-woo-connect' ); ?>"
				class="mauwoo-button"><?php esc_html_e( 'Next', 'enhanced-woocommerce-mautic-integration' ); ?></a>
				<?php
			} else {
				?>
			<i class="fas fa-hand-point-right"></i>
			<a id="mauwoo-get-started" href="javascript:void(0)"
				class="mauwoo-button"><?php esc_html_e( 'Get Started', 'enhanced-woocommerce-mautic-integration' ); ?> <i
					class="fas fa-circle-notch fa-spin mauwoo-hide"></i> </a>
				<?php
			}
			?>
		</div>
	</div>
</div>
