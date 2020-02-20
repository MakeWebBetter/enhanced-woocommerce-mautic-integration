<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mautic_Woo_Activator {

	/**
	 * Create log file in the WC_LOG directory.
	 *
	 * Create a log file in the WooCommerce defined log directory
	 * and use the same for the logging purpose of our plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged , WordPress.WP.AlternativeFunctions.file_system_read_fopen
		@fopen( WC_LOG_DIR . 'mautic-woo-logs.log', 'a' );

		if ( ! wp_next_scheduled( 'mautic_woo_cron_schedule' ) ) {
			wp_schedule_event( time(), 'mautic-woo-5min-cron', 'mautic_woo_cron_schedule' );
		}

		update_option( 'mautic_woo_activation_time', time() );

	}
}
