<?php
/**
 * Provide a admin settings page
 *
 * This file is used to markup the admin settings of the plugin.
 *
 * @package enhanced-woocommerce-mautic-integration
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

define( 'ONBOARD_PLUGIN_NAME', 'Integration with Mautic for WooCommerce - Open Source Marketing' );

if ( class_exists( 'Makewebbetter_Onboarding_Helper' ) ) {
	$this->onboard = new Makewebbetter_Onboarding_Helper();
}
?>

<?php
	global $mautic_woo;

	// phpcs:ignore WordPress.Security.NonceVerification
	$active_tab   = isset( $_GET['mauwoo_tab'] ) ? sanitize_text_field( wp_unslash( $_GET['mauwoo_tab'] ) ) : 'mautic-woo-getstarted';
	$default_tabs = $mautic_woo->mautic_woo_default_tabs();
?>
<div class="mauwoo-admin-wrap">
	<div class="mauwoo-go-pro">
		<div class="mauwoo-go-pro-banner">
			<div class="mauwoo-inner-container">
				<div class="mauwoo-name-wrapper">
					<p><?php esc_html_e( 'Integration with Mautic for WooCommerce', 'enhanced-woocommerce-mautic-integration' ); ?></p>
				</div>
				<div class="mauwoo-static-menu">
					<ul>
						<li><a href="<?php echo esc_url( 'https://makewebbetter.com/contact-us/' ); ?>"
								target="_blank"><?php esc_html_e( 'Contact US', 'enhanced-woocommerce-mautic-integration' ); ?></a></li>
						<li><a href="<?php echo esc_url( 'https://docs.makewebbetter.com/mautic-woocommerce-integration-troubleshooting-guide/' ); ?>"
								target="_blank"><?php esc_html_e( 'Troubleshooting Guide', 'enhanced-woocommerce-mautic-integration' ); ?></a></li>
						<li class="mauwoo-main-menu-button"><a id="mauwoo-go-pro-link"
								href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" class="" title=""
								target="_blank"><?php esc_html_e( 'Go pro now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
						</li>
						<li class="mauwoo-main-menu-button"><a id="mauwoo-go-mautic-email-templates-link"
								href="<?php echo esc_url( 'https://makewebbetter.com/mautic-email-templates/' ); ?>"
								class="" title=""
								target="_blank"><?php esc_html_e( 'Mautic Email Templates', 'enhanced-woocommerce-mautic-integration' ); ?></a></li>
						<li class="mauwoo-main-menu-button"><a id="mauwoo-skype-link"
								href="<?php echo esc_url( 'https://join.skype.com/invite/IKVeNkLHebpC' ); ?>" class=""
								title="" target="_blank"><i
									class="fab fa-skype"></i><?php esc_html_e( 'Chat Now', 'enhanced-woocommerce-mautic-integration' ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="mauwoo-main-template">

		<!-- v.1.0.3 -->
		<?php
		if ( $mautic_woo->is_setup_completed() && wp_next_scheduled( 'mautic_woo_cron_schedule' ) ) {
			?>
		<div class="mauwoo-header-cron-notice">
			<div class="updated">
				<p>
					<?php
					$date = date_i18n( wc_date_format(), wp_next_scheduled( 'mautic_woo_cron_schedule' ) );
					$time = date_i18n( wc_time_format(), wp_next_scheduled( 'mautic_woo_cron_schedule' ) );
					/* translators: 1: Time 2: Date  */
					$txt = sprintf( __( 'Next Mautic Woocommerce sync :  %1$s at %2$s ', 'enhanced-woocommerce-mautic-integration' ), $date, $time );
					echo esc_html( $txt );
					?>
				</p>
			</div>
		</div>
		<?php } ?>
		<!-- v.1.0.3 -->
		<div class="mauwoo-body-template">
			<div class="mauwoo-navigator-template">
				<div class="mauwoo-navigations">
					<?php
					if ( is_array( $default_tabs ) && count( $default_tabs ) ) {

						foreach ( $default_tabs as $tab_key => $single_tab ) {

							$tab_classes = 'mauwoo-nav-tab ';

							$dependency = $single_tab['dependency'];

							if ( ! empty( $active_tab ) && $tab_key === $active_tab ) {

								$tab_classes .= 'nav-tab-active';
							}

							if ( in_array( $tab_key, array( 'mautic-woo-activity', 'mautic-woo-segments', 'mautic-woo-coupon', 'mautic-woo-sync', 'mautic-woo-rfm', 'mautic-woo-one-click-sync', 'mautic-woo-tracking', 'mautic-woo-abdn-cart' ), true ) ) {

								if ( ! empty( $dependency ) && ! $mautic_woo->check_dependencies( $dependency ) ) {


									$tab_classes .= ' mauwoo-tab-disabled';
									$tab_classes .= ' mauwoo-lock';
									?>
					<div class="mauwoo-tabs">
						<a class="<?php echo esc_attr( $tab_classes ); ?>" id="<?php echo esc_attr( $tab_key ); ?>"
							href="javascript:void(0);">
							<i class="<?php echo esc_attr( $single_tab['icon'] ); ?>">
							</i>
							<span>
									<?php echo esc_html( $single_tab['name'] ); ?>
							</span>
							<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/lock.png' ); ?>">
						</a>
					</div>
									<?php
								} else {

									$tab_classes .= ' mauwoo-lock';
									?>
					<div class="mauwoo-tabs">
						<a class="<?php echo esc_attr( $tab_classes ); ?>" id="<?php echo esc_attr( $tab_key ); ?>"
							href="<?php echo esc_url( admin_url( 'admin.php?page=mautic-woo' ) . '&mauwoo_tab=' . $tab_key ); ?>">
							<i class="<?php echo esc_attr( $single_tab['icon'] ); ?>">
							</i>
							<span>
									<?php echo esc_html( $single_tab['name'] ); ?>
							</span>
							<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/lock.png' ); ?>">
						</a>
					</div>
									<?php
								}
							} else {

								if ( ! empty( $dependency ) && ! $mautic_woo->check_dependencies( $dependency ) ) {

									$tab_classes .= ' mauwoo-tab-disabled';
									?>
					<div class="mauwoo-tabs">
						<a class="<?php echo esc_attr( $tab_classes ); ?>" id="<?php echo esc_attr( $tab_key ); ?>"
							href="javascript:void(0);">
							<i class="<?php echo esc_attr( $single_tab['icon'] ); ?>">
							</i>
							<span>
									<?php echo esc_html( $single_tab['name'] ); ?>
							</span>
						</a>
					</div>
									<?php
								} else {

									?>
					<div class="mauwoo-tabs">
						<a class="<?php echo esc_attr( $tab_classes ); ?>" id="<?php echo esc_attr( $tab_key ); ?>"
							href="<?php echo esc_url( admin_url( 'admin.php?page=mautic-woo' ) . '&mauwoo_tab=' . $tab_key ); ?>">
							<i class="<?php echo esc_attr( $single_tab['icon'] ); ?>">
							</i>
							<span>
									<?php echo esc_html( $single_tab['name'] ); ?>
							</span>
						</a>
					</div>
									<?php
								}
							}
						}
					}
					?>
				</div>
			</div>
			<div class="mauwoo-content-template">
				<div class="mauwoo-content-container">
					<?php
					if ( empty( $active_tab ) ) {

						$active_tab = 'mauwoo_overview';
					}
					$tab_content_path = 'admin/templates/' . $active_tab . '.php';
					$mautic_woo->load_template_view( $tab_content_path );
					?>
				</div>
			</div>
		</div>
		<div style="display: none;" class="loading-style-bg" id="mauwoo_loader">
			<img src="<?php echo esc_url( MAUTIC_WOO_URL . 'admin/images/loader.gif' ); ?>">
		</div>
		<!-- Pop Up -->
		<div class="mauwoo_pop_up_wrap" style="display:none">
			<div class="pop_up_sub_wrap">
				<p class="updated">

					<a id="mautic_woo_close_popup" href="#">&times</a>

					<p>
						<?php esc_html_e( 'You can sync all your previous customers and orders data over mautic by using "One Click Sync" feature of our pro plugin. Please upgrade to pro.', 'enhanced-woocommerce-mautic-integration' ); ?>
					</p>
					<p>
						<a href="<?php echo esc_url( MAUTIC_WOO_PRO_LINK ); ?>" class="mauwoo-button" target="_blank"
							style="margin-top: 35px;"><?php esc_html_e( 'Upgrade now', 'enhanced-woocommerce-mautic-integration' ); ?></a>
					</p>
				</p>

				<div>
				</div>
			</div>
		</div>
	</div>
</div>
