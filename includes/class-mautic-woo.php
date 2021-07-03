<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 * @author     MakeWebBetter
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @license    GPL-3.0+
 */

/**
 * Maintains all the plugin hooks.
 *
 * All the hooks which are used in plugin.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mautic_Woo {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Mautic_Woo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'MAUTIC_WOO_VERSION' ) ) {
			$this->version = MAUTIC_WOO_VERSION;
		} else {
			$this->version = '2.0.7';
		}

		$this->plugin_name = 'mautic-woo';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mautic_Woo_Loader. Orchestrates the hooks of the plugin.
	 * - Mautic_Woo_i18n. Defines internationalization functionality.
	 * - Mautic_Woo_Admin. Defines all hooks for the admin area.
	 * - Mautic_Woo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mautic-woo-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mautic-woo-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mautic-woo-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mautic-woo-public.php';

		/**
		 * The class responsible for all api actions with mautic.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mautic-woo-connection-manager.php';

		/**
		 * The class contains all the information related to customer groups and properties.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mautic-woo-contact-properties.php';

		/**
		 * The class contains are readymade contact details to send it to
		 * mautic.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mautic-woo-customer.php';

		/**
		 * The class responsible for property values.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mautic-woo-property-callbacks.php';

		/**
		 * The class responsible for handling ajax requests.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mautic-woo-ajax-handler.php';

		/**
		 * The class responsible for defining all actions that occur in the onboarding the site data
		 * in the admin side of the site.
		 */
		if ( ! class_exists( 'Makewebbetter_Onboarding_Helper' ) ) {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-makewebbetter-onboarding-helper.php';
		}

		if ( class_exists( 'Makewebbetter_Onboarding_Helper' ) ) {

			$this->onboard = new Makewebbetter_Onboarding_Helper();
		}

		$this->loader = new Mautic_Woo_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mautic_Woo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {

		$plugin_i18n = new Mautic_Woo_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mautic_Woo_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mautic_woo_redirection' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mautic_woo_add_privacy_message' );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'mautic_woo_set_cron_time' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mautic_woo_check_oauth' );

		if ( $this->is_setup_completed() ) {

			$this->loader->add_action( 'admin_notices', $plugin_admin, 'mauwoo_re_authorize_notice' );

			if ( $this->mautic_woo_sync_method() === 'cron' ) {

				$this->loader->add_action( 'profile_update', $plugin_admin, 'mautic_woo_update_changes' );

				$this->loader->add_action( 'mautic_woo_cron_schedule', $plugin_admin, 'mautic_woo_cron_schedule' );
			}

			$this->loader->add_filter( 'mautic_woo_filter_contact_properties', $plugin_admin, 'mautic_woo_filter_contact_properties_callback' );

		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_public_hooks() {

		$plugin_public = new Mautic_Woo_Public( $this->get_plugin_name(), $this->get_version() );
		if ( self::mautic_woo_sync_method() === 'cron' ) {

			$this->loader->add_action( 'profile_update', $plugin_public, 'mautic_woo_save_account_details' );
			$this->loader->add_action( 'user_register', $plugin_public, 'mautic_woo_save_account_details' );
			$this->loader->add_action( 'woocommerce_checkout_update_user_meta', $plugin_public, 'mautic_woo_save_account_details' );
		}

		if ( self::mautic_woo_sync_method() === 'instant' ) {

			$this->loader->add_action( 'profile_update', $plugin_public, 'mauwoo_run_instant_update' );
			$this->loader->add_action( 'user_register', $plugin_public, 'mauwoo_run_instant_update' );
			$this->loader->add_action( 'woocommerce_new_order', $plugin_public, 'mauwoo_run_instant_order_update' );
			$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'mauwoo_run_instant_order_update' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mautic_Woo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mauwoo tabs.
	 *
	 * @return  Array       An key=>value pair of mautic tabs.
	 */
	public function mautic_woo_default_tabs() {

		$default_tabs = array();

		$default_tabs['mautic-woo-getstarted'] = array(
			'name'       => __( 'Get Started', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => '',
			'icon'       => 'fa fa-th-large',
		);

		$default_tabs['mautic-woo-overview'] = array(
			'name'       => __( 'Overview', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => '',
			'icon'       => 'fa fa-life-ring',
		);

		$default_tabs['mautic-woo-connect'] = array(
			'name'       => __( 'Connect', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => array( 'mautic_woo_get_started' ),
			'icon'       => 'fas fa-link',
		);

		$common_dependency = array( 'is_setup_completed', 'is_valid_client_id_stored' );

		$default_tabs['mautic-woo-custom-fields'] = array(
			'name'       => __( 'Custom Fields', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => array( 'is_oauth_success', 'is_valid_client_id_stored' ),
			'icon'       => 'fa fa-list',
		);

		$default_tabs['mautic-woo-segments'] = array(
			'name'       => __( 'Segments', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-chart-pie',
		);

		$default_tabs['mautic-woo-rfm'] = array(
			'name'       => __( 'RFM Settings', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-star',
		);

		$default_tabs['mautic-woo-coupon'] = array(
			'name'       => __( 'Coupon Codes', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-tags',
		);

		$default_tabs['mautic-woo-sync'] = array(
			'name'       => __( 'Field Sync', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-exchange-alt',
		);

		$default_tabs['mautic-woo-one-click-sync'] = array(
			'name'       => __( 'One-Click Sync', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-sync',
		);

		$default_tabs['mautic-woo-abdn-cart'] = array(
			'name'       => __( 'Abandoned Carts', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-shopping-cart',
		);

		$default_tabs['mautic-woo-activity'] = array(
			'name'       => __( 'Activity Sync', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fa fa-tasks',
		);

		$default_tabs['mautic-woo-tracking'] = array(
			'name'       => __( 'Site Tracking', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-chart-line',
		);

		$default_tabs['mautic-woo-settings'] = array(
			'name'       => __( 'Settings', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fa fa-cogs',
		);

		$default_tabs['mautic-woo-log'] = array(
			'name'       => __( 'Sync Log', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => '',
			'icon'       => 'fa fa-exclamation-triangle',
		);

		$default_tabs['mautic-woo-themes'] = array(
			'name'       => __( 'Email Templates', 'enhanced-woocommerce-mautic-integration' ),
			'dependency' => $common_dependency,
			'icon'       => 'fas fa-newspaper',
		);

		return $default_tabs;
	}

	/**
	 * Checking dependencies for tabs.
	 *
	 * @since     1.0.0
	 * @param array $dependency dependecies on a tab.
	 * @return bool
	 */
	public function check_dependencies( $dependency = array() ) {

		$flag = true;

		global $mautic_woo;

		if ( count( $dependency ) ) {

			foreach ( $dependency as $single_dependency ) {

				$flag = $mautic_woo->$single_dependency();
			}
		}

		return $flag;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path This is path of template.
	 * @param array  $params params contain the array.
	 */
	public function load_template_view( $path, $params = array() ) {

		$file_path = MAUTIC_WOO_ABSPATH . $path;

		if ( file_exists( $file_path ) ) {

			include $file_path;

		} else {

			/* translators: %s: file path */
			$notice = sprintf( __( 'Unable to locate file path at location "%s". Some features may not work properly in Integration with Mautic for WooCommerce, please contact us!', 'mautic-woo' ), $file_path );

			$this->mautic_woo_notice( $notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0.
	 */
	public static function mautic_woo_notice( $message, $type = 'error' ) {

		$classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$classes .= 'updated';
				break;

			case 'update-nag':
				$classes .= 'update-nag';
				break;
			case 'success':
				$classes .= 'notice-success is-dismissible';
				break;

			default:
				$classes .= 'error';
		}

		$notice  = '<div class="' . esc_attr( $classes ) . '">';
		$notice .= '<p>' . esc_html( $message ) . '</p>';
		$notice .= '</div>';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Check set option for get started.
	 *
	 * @return boolean
	 */
	public static function mautic_woo_get_started() {

		return get_option( 'mautic_woo_get_started', false );
	}

	/**
	 * Check if access token is expired.
	 *
	 * @return boolean
	 */
	public static function is_access_token_expired() {

		$get_expiry = get_option( 'mautic_woo_token_expiry', false );

		if ( $get_expiry ) {

			$current_time = time();

			if ( $current_time > $get_expiry ) {

				return true;
			}
		}

		return false;
	}

	/**
	 * Getting whether any fields selected to be created or not.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_fields_to_create() {

		$choice = self::mautic_woo_user_choice();
		$status = false;

		if ( 'no' === $choice ) {

			$status = true;
		} elseif ( 'yes' === $choice ) {

			$selected_properties = self::mautic_woo_user_selected_fields();

			if ( is_array( $selected_properties ) && count( $selected_properties ) ) {

				$status = true;
			}
		}

		return $status;
	}


	/**
	 * Getting final custom fields
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function mautic_woo_get_final_fields() {

		$final_properties = array();

		$groups = MauticWooContactProperties::get_instance()->_get( 'groups' );

		$existing_fields = get_option( 'mautic-woo-fields-created', array() );

		if ( self::mautic_woo_user_choice() === 'no' ) {

			if ( is_array( $groups ) && count( $groups ) ) {

				foreach ( $groups as $single_group ) {

					$properties = MauticWooContactProperties::get_instance()->_get( 'properties', $single_group['name'] );

					if ( is_array( $properties ) && count( $properties ) ) {

						foreach ( $properties as $single_property ) {

							if ( in_array( $single_property['alias'], $existing_fields, true ) ) {

								$final_properties[] = array(
									'detail' => $single_property,
									'status' => 'created',
								);
							} else {

								$final_properties[] = array(
									'detail' => $single_property,
									'status' => 'false',
								);
							}
						}
					}
				}
			}
		} else {

			$selected_properties = self::mautic_woo_user_selected_fields();

			if ( is_array( $groups ) && count( $groups ) ) {

				foreach ( $groups as $single_group ) {

					$properties = MauticWooContactProperties::get_instance()->_get( 'properties', $single_group['name'] );

					if ( is_array( $properties ) && count( $properties ) ) {

						foreach ( $properties as $single_property ) {

							if ( in_array( $single_property['alias'], $selected_properties, true ) ) {

								if ( in_array( $single_property['alias'], $existing_fields, true ) ) {

									$final_properties[] = array(
										'detail' => $single_property,
										'status' => 'created',
									);
								} else {

									$final_properties[] = array(
										'detail' => $single_property,
										'status' => 'false',
									);
								}
							} else {

								$final_properties[] = array(
									'detail' => $single_property,
									'status' => 'false',
								);
							}
						}
					}
				}
			}
		}

		return $final_properties;
	}

	/**
	 * Checking whether oauth is done or not
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_oauth_success() {

		return get_option( 'mautic_woo_oauth_success', false );
	}


	/**
	 * Checking for valid client id
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_valid_client_id_stored() {

		return get_option( 'mautic_woo_valid_client_ids_stored', false );
	}

	/**
	 * Verify if the mautic setup is completed.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_setup_completed() {

		return get_option( 'mautic_woo_setup_completed', false );
	}


	/**
	 * Checking/Retreving mautic required keys
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function is_valid_keys_provided() {

		$status        = '';
		$client_id     = get_option( 'mautic_woo_client_id', false );
		$client_secret = get_option( 'mautic_woo_secret_id', false );
		$base_url      = get_option( 'mautic_woo_base_url', false );
		$base_url      = filter_var( $base_url, FILTER_VALIDATE_URL );

		if ( ! $base_url ) {

			$status = 'invalid_url';
		} elseif ( empty( $client_id ) || empty( $client_secret ) ) {

			$status = 'empty_keys';
		} else {

			$status = 'ok';
		}

		return $status;
	}

	/**
	 * Retreving mautic base url.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function get_client_mautic_base_url() {

		$base_url = get_option( 'mautic_woo_base_url', '' );
		return rtrim( $base_url, '/' );
	}


	/**
	 * Checking/Retreving mautic required keys.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_mautic_connection_keys() {

		$client_id     = get_option( 'mautic_woo_client_id', '' );
		$client_secret = get_option( 'mautic_woo_secret_id', '' );
		$keys          = array(
			'client_id'     => $client_id,
			'client_secret' => $client_secret,
		);

		return $keys;
	}

	/**
	 * Account Email Information.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function mautic_woo_account_email_info() {

		$mautic_woo_acc_email = get_option( 'mautic_woo_acc_email', '' );

		if ( empty( $mautic_woo_acc_email ) ) {

			if ( self::is_valid_client_id_stored() ) {

				$flag = true;

				if ( self::is_access_token_expired() ) {

					$keys    = self::get_mautic_connection_keys();
					$mpubkey = $keys['client_id'];
					$mseckey = $keys['client_secret'];

					$status = MauticWooConnectionMananager::get_instance()->mautic_woo_refresh_token( $mpubkey, $mseckey );

					if ( ! $status ) {

						$flag = false;
					}
				}

				if ( $flag ) {

					$admin_user_info = MauticWooConnectionMananager::get_instance()->get_mautic_self_user_info();
				}
			}

			if ( ! empty( $admin_user_info ) ) {

				$mautic_woo_acc_email = isset( $admin_user_info->email ) ? $admin_user_info->email : '';
				update_option( 'mautic_woo_acc_email', $mautic_woo_acc_email );
			}
		}

		return $mautic_woo_acc_email;
	}

	/**
	 * Check the user choice for fields setup
	 *
	 * @since 1.0.0
	 * @return yes/no
	 */
	public static function mautic_woo_user_choice() {

		return get_option( 'mautic_woo_select_fields', '' );
	}

	/**
	 * Returns the user selected fields for setup.
	 *
	 * @since 1.0.0
	 * @return array of selected contact properties
	 */
	public static function mautic_woo_user_selected_fields() {

		return get_option( 'mautic_woo_selected_properties', array() );
	}

	/**
	 * Reset mautic connection
	 *
	 * @since 1.0.3
	 */
	public function mautic_woo_reset_account_settings() {

		delete_option( 'mautic_woo_get_started' );
		delete_option( 'mautic_woo_secret_id' );
		delete_option( 'mautic_woo_base_url' );
		delete_option( 'mautic_woo_client_id' );
		delete_option( 'mautic_woo_access_token' );
		delete_option( 'mautic_woo_refresh_token' );
		delete_option( 'mautic_woo_token_expiry' );
		delete_option( 'mautic_woo_valid_client_ids_stored' );
		delete_option( 'mautic_woo_oauth_success' );
		delete_option( 'mautic_woo_acc_email' );
		delete_option( 'mautic_woo_move_to_custom_fields' );
		delete_option( 'mautic_woo_select_fields' );
		delete_option( 'mautic_woo_selected_properties' );
		delete_option( 'mautic_custom_field_ids' );
		delete_option( 'mautic_woo_setup_completed' );
		delete_option( 'mautic-woo-fields-created' );
		wp_safe_redirect( admin_url( 'admin.php' ) . '?page=mautic-woo' );
		exit();
	}

	/**
	 * Send Support Request
	 *
	 * @since 1.0.3
	 */
	public function mautic_woo_send_support_request() {

		$to       = MAUTIC_WOO_INTEGRATION_EMAIL;
		$name     = site_url();
		$content  = 'Plugin Development Support Request' . PHP_EOL;
		$content .= 'Site Url : ' . $name . PHP_EOL;
		$content .= 'Admin Email : ' . get_option( 'admin_email' ) . PHP_EOL;
		if ( is_user_logged_in() ) {
			$user     = wp_get_current_user();
			$content .= 'Admin Name : ' . $user->display_name . PHP_EOL;
		}

		$date = date_i18n( wc_date_format(), get_option( 'mautic_woo_activation_time', time() ) );
		$time = date_i18n( wc_time_format(), get_option( 'mautic_woo_activation_time', time() ) );

		$activation_time = $date . '@' . $time;

		$content .= 'Activation Time : ' . $activation_time . PHP_EOL;

		$subject = 'Plugin Development Support Request [Mautic]';

		$sent = wp_mail( $to, $subject, $content );

		if ( $sent ) {

			update_option( 'mautic_woo_support_request', true );

		}
		wp_safe_redirect( admin_url( 'admin.php' ) . '?page=mautic-woo' );
		exit();
	}

	/**
	 * Get all guest orders.
	 *
	 * @since 1.0.3
	 */
	public function mauwoo_guest_all_order() {
		$args2 = array(
			'post_type'      => 'shop_order',
			'posts_per_page' => '-1',
			'post_status'    => 'any',
			'meta_key'       => '_customer_user',
			'meta_value'     => 0,
		);

		$order = get_posts( $args2 );
		$count = count( $order );
		update_option( 'mauwoo_guest_all_order', $count );
	}


	/**
	 * Getting sync method for mautic
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function mautic_woo_sync_method() {

		return get_option( 'mautic_woo_sync_method', 'instant' );
	}
}
