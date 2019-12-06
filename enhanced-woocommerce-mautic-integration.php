<?php
/**
 * Base file of plugin
 *
 * Base plugin file which checks all the dependecies for the plugin.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 * @author     <webmaster@makewebbetter.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name:         Integration with Mautic for WooCommerce - Open Source Marketing Automation
 * Description:         A very powerful plugin to integrate your WooCommerce store with Mautic seamlessly.
 * Version:             2.0.6
 * Requires at least:   4.4
 * Tested up to:        5.3
 * WC requires at least:    3.0.0
 * WC tested up to:         3.8.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       mautic-woo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$mautic_woo_wc_activated  = false;
$mautic_woo_pro_activated = false;
$error_notice             = 0;

/**
* Checking if WooCommerce is active
*/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) || class_exists( 'WooCommerce' ) ) {
	$mautic_woo_wc_activated = true;
	$error_notice            = 1;
}

if ( in_array( 'mautic-woocommerce-marketing-automation/mautic-woocommerce-marketing-automation.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

	$mautic_woo_pro_activated = true;
	$error_notice             = 2;

}

if ( $mautic_woo_wc_activated && ! $mautic_woo_pro_activated ) {

	/**
	* Activates the plugin
	*
	* @since 1.0.0
	*/
	if ( ! function_exists( 'activate_mauwoo' ) ) {

		/**
		 * This function activate the plugin
		 */
		function activate_mauwoo() {
			include_once plugin_dir_path( __FILE__ ) . 'includes/class-mautic-woo-activator.php';
			Mautic_Woo_Activator::activate();
		}
	}

	/**
	* Deactivates the plguin
	*
	* @since 1.0.0
	*/
	if ( ! function_exists( 'deactivate_mauwoo' ) ) {

		/**
		 * This function deactivate the plugin
		 */
		function deactivate_mauwoo() {
			include_once plugin_dir_path( __FILE__ ) . 'includes/class-mautic-woo-deactivator.php';
			Mautic_Woo_Deactivator::deactivate();
		}
	}

	register_activation_hook( __FILE__, 'activate_mauwoo' );
	register_deactivation_hook( __FILE__, 'deactivate_mauwoo' );

	/**
	* The core plugin class that is used to define internationalization,
	* admin-specific hooks, and public-facing site hooks.
	*/

	include plugin_dir_path( __FILE__ ) . 'includes/class-mautic-woo.php';

	/**
	 * Define constants.
	 *
	 * @since 1.0.0
	 */
	function mauwoo_define_constants() {
		mauwoo_define( 'MAUTIC_WOO_ABSPATH', dirname( __FILE__ ) . '/' );
		mauwoo_define( 'MAUTIC_WOO_URL', plugin_dir_url( __FILE__ ) . '/' );
		mauwoo_define( 'MAUTIC_WOO_VERSION', '2.0.6' );
		mauwoo_define( 'MAUTIC_WOO_INTEGRATION_EMAIL', 'integrations@makewebbetter.com' );
		mauwoo_define( 'MAUTIC_WOO_SYNC_LIMIT', 100 );
		mauwoo_define( 'MAUTIC_WOO_PRO_LINK', 'https://bit.ly/2nPdpkh' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name this parameter define the name.
	 * @param string|bool $value this parameter define the value.
	 * @since 1.0.0
	 */
	function mauwoo_define( $name, $value ) {

		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Setting Page Link
	 *
	 * @since  1.0.0
	 * @author MakeWebBetter
	 * @link   https://makewebbetter.com/
	 * @param  array  $actions this parameter define the action of Plugin.
	 * @param  string $plugin_file  this parameter define the plugin files.
	 */
	function mauwoo_admin_settings( $actions, $plugin_file ) {

		static $plugin;

		if ( ! isset( $plugin ) ) {

			$plugin = plugin_basename( __FILE__ );
		}

		if ( $plugin === $plugin_file ) {

			$settings = array(
				'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=mautic-woo' ) ) . '">' . esc_html__( 'Settings', 'mautic-woo' ) . '</a>',
			);

			$actions = array_merge( $settings, $actions );
		}

		return $actions;
	}

	// add link for settings.
	add_filter( 'plugin_action_links', 'mauwoo_admin_settings', 10, 5 );

	/**
	 * Adds plugin row meta
	 *
	 * @since 1.0.0
	 * @param array  $links array of links.
	 * @param string $file file path.
	 */
	function mautic_woo_plugin_row_meta( $links, $file ) {

		if ( strpos( $file, 'enhanced-woocommerce-mautic-integration.php' ) !== false ) {

			$row_meta = array(
				'docs'  => '<a href="' . esc_url( 'https://docs.makewebbetter.com/enhanced-woocommerce-mautic-integration/' ) . '">' . esc_html__( 'Docs', 'mautic-woo' ) . '</a>',
				'goPro' => '<a style="color:#06fd11" href="' . esc_url( MAUTIC_WOO_PRO_LINK ) . '">' . esc_html__( 'Go Premium', 'mautic-woo' ) . '</a>',
			);
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	add_filter( 'plugin_row_meta', 'mautic_woo_plugin_row_meta', 10, 2 );
	add_action( 'activated_plugin', 'mautic_woo_activation_redirect' );
	/**
	 * Redirect after activation
	 *
	 * @since 1.0.0
	 * @param string $plugin path of plugin file.
	 */
	function mautic_woo_activation_redirect( $plugin ) {
		if ( 'enhanced-woocommerce-mautic-integration/enhanced-woocommerce-mautic-integration.php' === $plugin ) {
			wp_safe_redirect( esc_url( admin_url( 'admin.php?page=mautic-woo' ) ) );
			exit();
		}
	}
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since 1.0.0
	 */
	function run_mauwoo() {
		// define contants if not defined..
		mauwoo_define_constants();
		$mautic_woo = new Mautic_Woo();
		$mautic_woo->run();
		$GLOBALS['mautic_woo'] = $mautic_woo;
	}
	run_mauwoo();
} else {
	/**
	 * Show warning message if woocommerce is not install.
	 *
	 * @since  1.0.0
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link   https://www.makewebbetter.com/
	 */
	function mauwoo_plugin_error_notice() {
		global $error_notice;
		if ( 1 === $error_notice ) {
			$notice_message = esc_html__( 'WooCommerce is not activated, Please activate WooCommerce first to install Integration with Mautic for WooCommerce.', 'mautic-woo' );
		}
		if ( 2 === $error_notice ) {
			$notice_message = esc_html__( 'Mautic WooCommerce Marketing Automation is activated, Please de-activate that first to install Integration with Mautic for WooCommerce.', 'mautic-woo' );
		}
		?>
		<div class="error notice is-dismissible">
		<p><?php esc_html_e( 'WooCommerce is not activated, Please activate WooCommerce first to install Integration with Mautic for WooCommerce.', 'mautic-woo' ); ?>
		</p>
		</div>
		<style>
		#message {
			display: none;
		}
		</style>
		<?php
	}
	add_action( 'admin_init', 'mauwoo_plugin_deactivate' );
	/**
	 * Call Admin notices
	 *
	 * @author MakeWebBetter<webmaster@makewebbetter.com>
	 * @link   https://www.makewebbetter.com/
	 */
	function mauwoo_plugin_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'mauwoo_plugin_error_notice' );
	}
}
