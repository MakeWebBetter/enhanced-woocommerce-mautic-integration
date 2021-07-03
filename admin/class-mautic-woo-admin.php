<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package  enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/admin
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mautic_Woo_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		// let's modularize our codebase, all the admin actions in one function.
		$this->admin_actions();
	}

	/**
	 * All admin actions.
	 *
	 * @since 1.0.0
	 */
	public function admin_actions() {
		// add submenu mautic in woocommerce top menu.
		add_action( 'admin_menu', array( &$this, 'add_mautic_woo_submenu' ) );
	}

	/**
	 * Add mautic submenu in woocommerce menu.
	 *
	 * @since 1.0.0
	 */
	public function add_mautic_woo_submenu() {
		add_submenu_page( 'woocommerce', esc_html__( 'Mautic', 'mautic-woo' ), esc_html__( 'Mautic', 'mautic-woo' ), 'manage_woocommerce', 'mautic-woo', array( &$this, 'mautic_woo_configurations' ) );
	}

	/**
	 * All the configuration related fields and settings.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_configurations() {
		include_once MAUTIC_WOO_ABSPATH . 'admin/templates/mautic-woo-main-template.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'woocommerce_page_mautic-woo' === $screen->id ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mautic-woo-admin.min.css', array(), $this->version, 'all' );
			wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
			wp_enqueue_style( 'woocommerce_admin_menu_styles' );
			wp_enqueue_style( 'woocommerce_admin_styles' );
			wp_enqueue_style( 'thickbox' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'woocommerce_page_mautic-woo' === $screen->id ) {
			wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip', 'wc-enhanced-select' ), WC_VERSION, true );
			wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array( 'jquery' ), WC_VERSION, true );
			$locale  = localeconv();
			$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
			$params  = array(
				/* translators: %s: decimal */
				'i18n_decimal_error'               => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'enhanced-woocommerce-mautic-integration' ), $decimal ),
				/* translators: %s: price decimal separator */
				'i18n_mon_decimal_error'           => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'enhanced-woocommerce-mautic-integration' ), wc_get_price_decimal_separator() ),
				'i18n_country_iso_error'           => __( 'Please enter in country code with two capital letters.', 'enhanced-woocommerce-mautic-integration' ),
				'i18_sale_less_than_regular_error' => __( 'Please enter in a value less than the regular price.', 'enhanced-woocommerce-mautic-integration' ),
				'decimal_point'                    => $decimal,
				'mon_decimal_point'                => wc_get_price_decimal_separator(),
				'strings'                          => array(
					'import_products' => __( 'Import', 'enhanced-woocommerce-mautic-integration' ),
					'export_products' => __( 'Export', 'enhanced-woocommerce-mautic-integration' ),
				),
				'urls'                             => array(
					'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
					'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
				),
			);
			wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
			wp_enqueue_script( 'woocommerce_admin' );
			wp_register_script( 'mautic_woo_script', plugin_dir_url( __FILE__ ) . 'js/mautic-woo-admin.min.js', array( 'jquery' ), $this->version, true );
			wp_localize_script(
				'mautic_woo_script',
				'mauwooi18n',
				array(
					'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
					'mauwooSecurity'         => wp_create_nonce( 'mauwoo_security' ),
					'mauwooWentWrong'        => __( 'Something went wrong, please try again later!', 'enhanced-woocommerce-mautic-integration' ),
					'mauwooSuccess'          => __( 'Setup is completed successfully!', 'enhanced-woocommerce-mautic-integration' ),
					'mauwooCreatingProperty' => __( 'Field created successfully', 'enhanced-woocommerce-mautic-integration' ),
					'mauwooSetupCompleted'   => __( 'Setup completed!', 'enhanced-woocommerce-mautic-integration' ),
					'mauwooConnectTab'       => admin_url() . 'admin.php?page=mautic-woo&mauwoo_tab=mautic-woo-connect',
					'mauwooCustomFields'     => admin_url() . 'admin.php?page=mautic-woo&mauwoo_tab=mautic-woo-custom-fields',
					'mauwooNoFieldsFound'    => __( 'It seems that no fields are selected. Please select atleast one field for the setup to start', 'enhanced-woocommerce-mautic-integration' ),
				)
			);
				wp_enqueue_script( 'mautic_woo_script' );
				wp_enqueue_script( 'thickbox' );
		}
	}

	/**
	 * Update schedule data with custom time.
	 *
	 * @since    1.0.0
	 * @param      string $schedules       Schedule data.
	 */
	public function mautic_woo_set_cron_time( $schedules ) {
		if ( ! isset( $schedules['mautic-woo-5min-cron'] ) ) {
			$schedules['mautic-woo-5min-cron'] = array(
				'interval' => 5 * 60,
				'display'  => __( 'Once every 5 minutes', 'enhanced-woocommerce-mautic-integration' ),
			);
		}
		return $schedules;
	}
	
		/**
		 * Schedule Executes when user data is update.
		 *
		 * @since    1.0.0
		 */
	public function mautic_woo_cron_schedule() {
		$mautic_woo_user_choice = Mautic_Woo::mautic_woo_user_choice();
		// Sync users and orders only when cron method is selected.
		if ( Mautic_woo::mautic_woo_sync_method() === 'cron' ) {
			// sync users.
			$args['meta_query'] = array(
				array(
					'key'     => 'mautic_woo_user_data_change',
					'value'   => 'yes',
					'compare' => '==',
				),
			);
			$updated_users = get_users( $args );
			$mautic_woo_users = apply_filters( 'mautic_woo_users', $updated_users );
			$mautic_woo_unique_users = array();
			if ( is_array( $mautic_woo_users ) && count( $mautic_woo_users ) ) {
				foreach ( $mautic_woo_users as $key => $value ) {
					if ( in_array( $value->ID, $mautic_woo_unique_users, true ) ) {
						continue;
					} else {
						$mautic_woo_unique_users[] = $value->ID;
					}
				}
			}
			if ( isset( $mautic_woo_unique_users ) && null !== $mautic_woo_unique_users && count( $mautic_woo_unique_users ) ) {
				foreach ( $mautic_woo_unique_users as $key => $customer_id ) {
					$mautic_woo_customer = new MauticWooCustomer( $customer_id );
					$properties = $mautic_woo_customer->get_contact_properties();
					$filtered_properties = array();
					if ( 'yes' === $mautic_woo_user_choice ) {
						$selected_properties = Mautic_Woo::mautic_woo_user_selected_fields();
						if ( is_array( $selected_properties ) && count( $selected_properties ) && is_array( $properties ) && count( $properties ) ) {
							foreach ( $properties as $field => $single_property ) {
								if ( in_array( $field, $selected_properties, true ) ) {
									$filtered_properties[ $field ] = $single_property;
								}
							}
						}
					} else {
						$filtered_properties = $properties;
					}
					$fname   = get_user_meta( $customer_id, 'first_name', true );
					$lname   = get_user_meta( $customer_id, 'last_name', true );
					$company = get_user_meta( $customer_id, 'billing_company', true );
					$phone   = get_user_meta( $customer_id, 'billing_phone', true );
					$filtered_properties['firstname'] = $fname;
					$filtered_properties['lastname']  = $lname;
					$filtered_properties['company']   = $company;
					$filtered_properties['mobile']    = $phone;
					$filtered_properties['phone']     = $phone;
					$filtered_properties['email']     = $mautic_woo_customer->get_email();
					$properties = apply_filters( 'mautic_woo_map_new_properties', $filtered_properties, $customer_id );
					if ( Mautic_Woo::is_valid_client_id_stored() ) {
						$flag = true;
						if ( Mautic_Woo::is_access_token_expired() ) {
							$keys    = Mautic_Woo::get_mautic_connection_keys();
							$mpubkey = $keys['client_id'];
							$mseckey = $keys['client_secret'];
							$status = MauticWooConnectionMananager::get_instance()->mautic_woo_refresh_token( $mpubkey, $mseckey );
							if ( ! $status ) {
								$flag = false;
							}
						}
						if ( $flag ) {
							MauticWooConnectionMananager::get_instance()->create_or_update_contacts( $properties );
							update_user_meta( $customer_id, 'mautic_woo_user_data_change', 'no' );
						}
					}
				}
			}
		}
	}

	/**
	 * Generating access token
	 *
	 * @since    1.0.0
	 */
	public function mautic_woo_redirection() {
		// Can not use nonce as it is redirecte from mautic.
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['code'] ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$code    = sanitize_key( $_GET['code'] );
			$keys    = Mautic_Woo::get_mautic_connection_keys();
			$mpubkey = $keys['client_id'];
			$mseckey = $keys['client_secret'];
			if ( $mpubkey && $mseckey ) {
				if ( ! Mautic_Woo::is_valid_client_id_stored() && ! get_option( 'mautic_woo_oauth_success', false ) ) {
					$response = MauticWooConnectionMananager::get_instance()->mautic_woo_fetch_access_token_from_code( $mpubkey, $mseckey, $code );
				}
				wp_safe_redirect( admin_url( 'admin.php' ) . '?page=mautic-woo&mauwoo_tab=mautic-woo-connect' );
				exit();
			}
		}
		if ( isset( $_GET['runcron'] ) ) {
			$this->mautic_woo_cron_schedule();
		}
		if ( isset( $_GET['action'] ) && ( 'download_log' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) ) {
			if ( check_admin_referer( 'mautic-woo-get', 'mautic-woo-get' ) ) {
				$filename = WC_LOG_DIR . 'mautic-woo-logs.log';
				if ( is_readable( $filename ) && file_exists( $filename ) ) {
					header( 'Content-type: text/plain' );
					header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
					//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_readfile
					readfile( $filename );
					exit;
				} else {
					wp_safe_redirect( admin_url( 'admin.php?page=mautic-woo&mauwoo_tab=mautic-woo-log' ) );
					exit;
				}
			}
		}
		if ( isset( $_GET['action'] ) && ( 'clear_log' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) ) {
			if ( check_admin_referer( 'mautic-woo-get', 'mautic-woo-get' ) ) {
				$filename = WC_LOG_DIR . 'mautic-woo-logs.log';
				if ( file_exists( $filename ) ) {
					//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
					file_put_contents( $filename, '' );
				}
				wp_safe_redirect( admin_url( 'admin.php?page=mautic-woo&mauwoo_tab=mautic-woo-log' ) );
				exit;
			}
		}
	}

	/**
	 * Check that user has called for OAuth
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_check_oauth() {
		if ( isset( $_GET['action'] ) && 'authorize' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) && isset( $_GET['page'] ) && 'mautic-woo' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			if ( check_admin_referer( 'mautic-woo-get', 'mautic-woo-get' ) ) {
				$keys = Mautic_Woo::get_mautic_connection_keys();
				$url  = Mautic_Woo::get_client_mautic_base_url() . '/oauth/v2/authorize';
				$mautic_url = add_query_arg(
					array(
						'client_id'     => $keys['client_id'],
						'redirect_uri'  => admin_url() . 'admin.php',
						'response_type' => 'code',
					),
					$url
				);
				// Need to redirect to mautic url.
				//phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
				wp_redirect( $mautic_url );
				exit();
			}
		}
	}
	/**
	 * Updating users list to be updated on mautic on order status transition
	 *
	 * @since 1.0.0
	 * @param int|string $order_id order id.
	 */
	public function mautic_woo_update_changes( $order_id ) {
		if ( ! empty( $order_id ) ) {
			$user_id = (int) get_post_meta( $order_id, '_customer_user', true );
			if ( 0 !== $user_id && 0 < $user_id ) {
				update_user_meta( $user_id, 'mautic_woo_user_data_change', 'yes' );
			}
		}
	}

	/**
	 * GDPR privacy policy
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_add_privacy_message() {
		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			$content = '<p>' . esc_html__( 'We use your email to send your Orders related data over Mautic.', 'enhanced-woocommerce-mautic-integration' ) . '</p>';
			$content .= '<p>' . esc_html__( 'Mautic began with a single focus. Equality. The Mautic community believes in giving every person the power to understand, manage, and grow their business or organization. Mautic is focused on helping this belief become a reality by getting powerful marketing automation software into the hands of everyone.', 'enhanced-woocommerce-mautic-integration' ) . '</p>';
			$content .= '<p>' . esc_html__( 'Please see the ', 'enhanced-woocommerce-mautic-integration' ) . '<a href="https://mautic.com/help/general-data-protection-regulation-gdpr/" target="_blank" >' . __( 'Mautic Data Privacy', 'enhanced-woocommerce-mautic-integration' ) . '</a>' . __( ' for more details.', 'enhanced-woocommerce-mautic-integration' ) . '</p>';
			if ( $content ) {
				wp_add_privacy_policy_content( esc_html__( 'Integration with Mautic for WooCommerce', 'enhanced-woocommerce-mautic-integration' ), $content );
			}
		}
	}

	/**
	 * Callback function to prevent fields from syncing
	 *
	 * @since 1.0.3
	 * @param array $properties array of all properties.
	 */
	public function mautic_woo_filter_contact_properties_callback( $properties ) {
		$disabled_custom_fields = get_option( 'mauwoo-disabled-custom-fields', array() );
		if ( count( $disabled_custom_fields ) ) {
			foreach ( $properties as $key => $value ) {
				if ( in_array( $key, $disabled_custom_fields, true ) ) {
					unset( $properties[ $key ] );
				}
			}
		}
		// update in verison 2.0.3.
		$tags = array( 'woocommerce' );
		$custom_tags = get_option( 'mautic-woo-custom-tags', '' );
		if ( ! empty( $custom_tags ) ) {
			$custom_tags_array = explode( ',', $custom_tags );
			$custom_tags_array = array_values( $custom_tags_array );
			$tags = array_merge( $tags, $custom_tags_array );
		}
		if ( isset( $properties['tags'] ) ) {
			$properties['tags'][] = array_merge( $tags, $properties['tags'] );
		} else {
			$properties['tags'] = $tags;
		}
		return $properties;
	}
	
	/**
	 * Show sync notice.
	 *
	 * @since 1.0.3
	 */
	public function mauwoo_re_authorize_notice() {
		global $mautic_woo;
		if ( ! $mautic_woo->is_valid_client_id_stored() && $mautic_woo->is_setup_completed() ) {
			?>
			<div class="notice notice-error is-dismissible"> 
				<p class="mauwoo-acces-token-renewal">
					<strong ><?php echo _e( 'You have lost the connection with Mautic. Please click the button to reauthorize with Mautic.', 'enhanced-woocommerce-mautic-integration' ); ?></strong>
					<a href= "<?php echo admin_url( 'admin.php?page=mautic-woo&mauwoo_tab=mautic-woo-connect' ); ?>" class="mauwoo-reauthorize-app "><?php _e( 'Reauthorize with Mautic', 'enhanced-woocommerce-mautic-integration' ); ?><i class="fas fa-circle-notch fa-spin mauwoo-hide "></i></a>
				</p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</div>
			<?php
		}
	}
}
