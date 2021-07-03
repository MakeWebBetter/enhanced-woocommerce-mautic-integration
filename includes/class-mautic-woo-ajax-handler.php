<?php
/**
 * Handles all admin ajax requests.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 */

/**
 * Handles all admin ajax requests.
 *
 * All the functions required for handling admin ajax requests
 * required by the plugin.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MauticWooAjaxHandler {

	/**
	 * Construct.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// check oauth access token.
		add_action( 'wp_ajax_mautic_woo_check_oauth_access_token', array( &$this, 'mautic_woo_check_oauth_access_token' ) );

		// get all groups request handler.
		add_action( 'wp_ajax_mautic_woo_get_groups', array( &$this, 'mautic_woo_get_groups' ) );

		// get group properties.
		add_action( 'wp_ajax_mautic_woo_get_group_properties', array( &$this, 'mautic_woo_get_group_properties' ) );

		// create property.
		add_action( 'wp_ajax_mautic_woo_create_group_property', array( &$this, 'mautic_woo_create_group_property' ) );

		// mark setup as completed.
		add_action( 'wp_ajax_mautic_woo_setup_completed', array( &$this, 'mautic_woo_setup_completed' ) );

		// start and go to connect tab.
		add_action( 'wp_ajax_mautic_woo_get_started_call', array( &$this, 'mautic_woo_get_started_call' ) );
		// mark the user choice for fields.
		add_action( 'wp_ajax_mautic_woo_save_user_choice', array( &$this, 'mautic_woo_save_user_choice' ) );
		// after connection ask for next step.
		add_action( 'wp_ajax_mautic_woo_move_to_custom_fields', array( &$this, 'mautic_woo_move_to_custom_fields' ) );
		// clear the user choice for fields.
		add_action( 'wp_ajax_mautic_woo_clear_user_choice', array( &$this, 'mautic_woo_clear_user_choice' ) );
		// woocommerce order stauses search.
		add_action( 'wp_ajax_mautic_woo_search_for_order_status', array( &$this, 'mautic_woo_search_for_order_status' ) );
		// allow reauthorization of mautic app.
		add_action( 'wp_ajax_mautic_woo_allow_reauth', array( &$this, 'mautic_woo_allow_reauth' ) );

		// send support request.
		add_action( 'wp_ajax_mautic_woo_support_development', array( &$this, 'mautic_woo_support_development' ) );

		add_action( 'wp_ajax_mautic_woo_search_for_custom_fields', array( &$this, 'mautic_woo_search_for_custom_fields' ) );

		// creates single field on admin call.
		add_action( 'wp_ajax_mautic_woo_create_single_field2', array( &$this, 'mautic_woo_create_single_field2' ) );
	}

	/**
	 * Checking access token validity.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_check_oauth_access_token() {

		$response = array(
			'status'  => true,
			'message' => __( 'Success', 'enhanced-woocommerce-mautic-integration' ),
		);

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );

		if ( Mautic_Woo::is_access_token_expired() ) {

			$keys    = Mautic_Woo::get_mautic_connection_keys();
			$mpubkey = $keys['client_id'];
			$mseckey = $keys['client_secret'];
			$status  = MauticWooConnectionMananager::get_instance()->mautic_woo_refresh_token( $mpubkey, $mseckey );

			if ( ! $status ) {

				$response['status']  = false;
				$response['message'] = __( 'Something went wrong, please check your Keys', 'enhanced-woocommerce-mautic-integration' );
			}
		}

		echo wp_json_encode( $response );

		wp_die();
	}

	/**
	 * Getting groups for fields.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_get_groups() {

		// check the nonce sercurity.
		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );

		$groups = MauticWooContactProperties::get_instance()->_get( 'groups' );

		echo wp_json_encode( $groups );

		wp_die();
	}

	/**
	 * Create an group property on ajax request.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_create_group_property() {
		// check the nonce sercurity.
		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );

		if ( isset( $_POST['groupName'] ) && isset( $_POST['propertyDetails'] ) ) {

			$property_details = sanitize_text_field( wp_unslash( $_POST['propertyDetails'] ) );

			$response = MauticWooConnectionMananager::get_instance()->create_property( $property_details );

			if ( ! empty( $response ) ) {

				if ( isset( $response['code'] ) && ( 201 === $response['code'] ) ) {

					$add_properties   = get_option( 'mautic-woo-fields-created', array() );
					$add_properties[] = $property_details['alias'];
					update_option( 'mautic-woo-fields-created', $add_properties );
				}
			}

			echo wp_json_encode( $response );
			wp_die();
		}
	}

	/**
	 * Get group properties by group name.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_get_group_properties() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );

		if ( isset( $_POST['groupName'] ) ) {

			$group_name = sanitize_text_field( wp_unslash( $_POST['groupName'] ) );
			$properties = MauticWooContactProperties::get_instance()->_get( 'properties', $group_name );
		}

		$mauwoo_select_fields = Mautic_Woo::mautic_woo_user_choice();

		$filtered_properties = array();

		if ( 'yes' === $mauwoo_select_fields ) {

			$mauwoo_selected_properties = Mautic_Woo::mautic_woo_user_selected_fields();

			if ( count( $mauwoo_selected_properties ) && ! empty( $properties ) ) {

				foreach ( $properties as $single_property ) {

					if ( isset( $single_property['alias'] ) ) {

						if ( in_array( $single_property['alias'], $mauwoo_selected_properties, true ) ) {

							$filtered_properties[] = $single_property;
						}
					}
				}

				echo wp_json_encode( $filtered_properties );
			}
		} else {

			echo wp_json_encode( $properties );
		}

		wp_die();
	}

	/**
	 * Mark setup is completed.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_setup_completed() {
		// check the nonce sercurity.
		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );
		update_option( 'mautic_woo_setup_completed', true );
		update_option( 'mautic_woo_version', MAUTIC_WOO_VERSION );
		return true;
	}
	/**
	 * Get started on admin call.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_get_started_call() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );
		update_option( 'mautic_woo_get_started', true );
		return true;
	}

	/**
	 * Save user choice for fields.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_save_user_choice() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );

		$choice = isset( $_POST['choice'] ) ? sanitize_text_field( wp_unslash( $_POST['choice'] ) ) : '';

		update_option( 'mautic_woo_select_fields', $choice );
		return true;
	}


	/**
	 * Move to custom fields.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_move_to_custom_fields() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );
		update_option( 'mautic_woo_move_to_custom_fields', true );
		return true;
	}

	/**
	 * Clearing user choice for fields selection.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_clear_user_choice() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );
		delete_option( 'mautic_woo_select_fields' );
		return true;
	}

	/**
	 * Creating selected single field.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_create_single_field2() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );

		$alias = isset( $_POST['alias'] ) ? sanitize_text_field( wp_unslash( $_POST['alias'] ) ) : '';

		$response = array(
			'code'    => 400,
			'message' => __( 'Something went wrong, Please check logs', 'enhanced-woocommerce-mautic-integration' ),
			'label'   => '',
		);

		$all_fields = Mautic_Woo::mautic_woo_get_final_fields();

		if ( is_array( $all_fields ) && count( $all_fields ) ) {

			foreach ( $all_fields as $single_property ) {

				if ( isset( $single_property['detail']['alias'] ) && $alias === $single_property['detail']['alias'] ) {

					if ( ! empty( $single_property['status'] ) && ( 'false' === $single_property['status'] || false === $single_property['status'] ) ) {

						$property_details = $single_property['detail'];

						$response = MauticWooConnectionMananager::get_instance()->create_property( $property_details );

						if ( ! empty( $response ) && ! is_wp_error( $response ) ) {

							if ( isset( $response['code'] ) && 201 === $response['code'] ) {

								$pre_created_fields = Mautic_Woo::mautic_woo_user_selected_fields();

								$pre_created_fields[] = $property_details['alias'];
								update_option( 'mautic_woo_selected_properties', $pre_created_fields );

								$add_properties = get_option( 'mautic-woo-fields-created', array() );

								$add_properties[] = $property_details['alias'];

								update_option( 'mautic-woo-fields-created', $add_properties );
							}
						}
						$response['label'] = $single_property['detail']['label'];
						echo wp_json_encode( $response );
						wp_die();
					} elseif ( 'created' === $single_property['status'] ) {

						$response['code']    = 201;
						$response['message'] = __( 'Field already exists', 'enhanced-woocommerce-mautic-integration' );
					}

					$response['label'] = $single_property['detail']['label'];
				}
			}
		}
		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Searching for order status.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_search_for_order_status() {

		$order_statuses = wc_get_order_statuses();

		$return = array();

		if ( ! empty( $order_statuses ) ) {

			foreach ( $order_statuses as $status_key => $single_status ) {

				$return[] = array( $status_key, $single_status );
			}
		}

		echo wp_json_encode( $return );

		wp_die();
	}

	/**
	 * Setting parameter to allow reauth from Mautic APP.
	 *
	 * @since 1.0.0
	 */
	public function mautic_woo_allow_reauth() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );
		delete_option( 'mautic_woo_oauth_success' );
		delete_option( 'mautic_woo_valid_client_ids_stored' );
		return true;
	}

	/**
	 * Send plugin development support request to makewebbetter.
	 *
	 * @since 1.0.3
	 */
	public function mautic_woo_support_development() {

		check_ajax_referer( 'mauwoo_security', 'mauwooSecurity' );
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

		update_option( 'mautic_woo_move_to_custom_fields', true );
		return true;
	}

	/**
	 * Send plugin created custom fields.
	 *
	 * @since 1.0.3
	 */
	public function mautic_woo_search_for_custom_fields() {

		$created_properties = get_option( 'mautic-woo-fields-created', array() );

		$properties_array = array();

		$all_properties = MauticWooContactProperties::get_instance()->_get( 'properties' );

		foreach ( $all_properties as $key => $properties ) {

			foreach ( $properties as $k => $v ) {

				if ( in_array( $v['alias'], $created_properties, true ) ) {

					$properties_array[] = array( $v['alias'], $v['label'] );
				}
			}
		}

		echo ( wp_json_encode( $properties_array ) );
		wp_die();
	}
}

new MauticWooAjaxHandler();
