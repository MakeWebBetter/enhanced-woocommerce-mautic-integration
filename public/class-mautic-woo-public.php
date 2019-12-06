<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/public
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class Mautic_Woo_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Update key as soon as user data is updated.
	 *
	 * @since 1.0.0
	 * @param string $user_id User Id.
	 */
	public function mautic_woo_save_account_details( $user_id ) {

		update_user_meta( $user_id, 'mautic_woo_user_data_change', 'yes' );
	}


	/**
	 * Running instant update of contacts profile changed data on Mautic
	 *
	 * @since 1.0.0
	 * @param string $user_id User Id.
	 */
	public function mauwoo_run_instant_update( $user_id ) {

		if ( ! empty( $user_id ) ) {

			$mauwoo_user_choice  = Mautic_Woo::mautic_woo_user_choice();
			$filtered_properties = array();
			$mauwoo_customer     = new MauticWooCustomer( $user_id );
			$properties          = $mauwoo_customer->get_contact_properties();

			if ( 'yes' === $mauwoo_user_choice ) {

				$mauwoo_selected_properties = Mautic_Woo::mautic_woo_user_selected_fields();

				if ( is_array( $mauwoo_selected_properties ) && count( $mauwoo_selected_properties ) && is_array( $properties ) && count( $properties ) ) {

					foreach ( $properties as $field => $single_property ) {

						if ( in_array( $field, $mauwoo_selected_properties, true ) ) {

							$filtered_properties[ $field ] = $single_property;
						}
					}
				}
			} else {

				$filtered_properties = $properties;
			}

			$phone = get_user_meta( $user_id, 'billing_phone', true );

			$filtered_properties['firstname'] = get_user_meta( $user_id, 'first_name', true );

			$filtered_properties['lastname'] = get_user_meta( $user_id, 'last_name', true );
			$filtered_properties['company']  = get_user_meta( $user_id, 'billing_company', true );
			$filtered_properties['mobile']   = $phone;
			$filtered_properties['phone']    = $phone;
			$filtered_properties['email']    = $mauwoo_customer->get_email();

			$filtered_properties = apply_filters( 'mauwoo_map_new_properties', $filtered_properties, $user_id );

			$filtered_properties = apply_filters( 'mauwoo_coupons_properties', $filtered_properties, $user_id );

			if ( is_array( $filtered_properties ) && count( $filtered_properties ) ) {

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

						MauticWooConnectionMananager::get_instance()->create_or_update_contacts( $filtered_properties );
					}
				}
			}
		}
	}


	/**
	 * Running instant update of contacts data on Mautic
	 *
	 * @since 1.0.0
	 * @param string $order_id order_id.
	 */
	public function mauwoo_run_instant_order_update( $order_id ) {

		$mauwoo_user_choice  = Mautic_Woo::mautic_woo_user_choice();
		$filtered_properties = array();

		if ( ! empty( $order_id ) ) {

			$customer_id = get_post_meta( $order_id, '_customer_user', true );

			$mauwoo_customer = new MauticWooCustomer( $customer_id );

			$properties = $mauwoo_customer->get_contact_properties();

			$filtered_properties = array();

			$mauwoo_properties = get_option( 'mautic-woo-fields-created', array() );

			if ( is_array( $properties ) && count( $properties ) ) {

				foreach ( $properties as $field => $single_property ) {

					if ( in_array( $field, $mauwoo_properties, true ) ) {

						$filtered_properties[ $field ] = $single_property;
					}
				}
			}

			$filtered_properties = apply_filters( 'mauwoo_coupons_properties', $filtered_properties, $customer_id );

			$filtered_properties = apply_filters( 'mauwoo_map_new_properties', $filtered_properties, $customer_id );

			$phone = get_user_meta( $customer_id, 'billing_phone', true );

			$filtered_properties['firstname'] = get_user_meta( $customer_id, 'first_name', true );
			$filtered_properties['lastname']  = get_user_meta( $customer_id, 'last_name', true );
			$filtered_properties['company']   = get_user_meta( $customer_id, 'billing_company', true );
			$filtered_properties['mobile']    = $phone;
			$filtered_properties['phone']     = $phone;
			$filtered_properties['email']     = $mauwoo_customer->get_email();

		}

		if ( is_array( $filtered_properties ) && count( $filtered_properties ) ) {

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

					MauticWooConnectionMananager::get_instance()->create_or_update_contacts( $filtered_properties );
				}
			}
		}
	}
}
