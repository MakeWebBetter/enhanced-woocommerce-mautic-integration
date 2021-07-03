<?php
/**
 * All property callbacks.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 */

/**
 * Manage all property callbacks.
 *
 * Provide a list of functions to manage all the information
 * about contacts properties and there callback functions to
 * get value of that property.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MauticWooPropertyCallbacks {

	/**
	 * Contact id.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	protected $_contact_id;

	/**
	 * WP user.
	 *
	 * @since 1.0.0
	 * @var WP_User
	 */
	protected $_user;

	/**
	 * Cache values.
	 *
	 * @var array.
	 */
	protected $_cache = array();

	/**
	 * Properties and there callbacks.
	 *
	 * @since 1.0.0
	 * @var Associated_array
	 */
	protected $_property_callbacks = array(

		'mwb_customer_group'       => 'get_contact_group',
		'mwb_newsletter_subs'      => 'mauwoo_user_meta',
		'mwb_customer_cart_id'     => 'mauwoo_user_meta',

		'mwb_last_order_stat'      => 'mauwoo_user_meta',
		'mwb_last_order_ff_stat'   => 'mauwoo_user_meta',
		'mwb_last_order_track_num' => 'mauwoo_user_meta',
		'mwb_last_order_track_url' => 'mauwoo_user_meta',
		'mwb_last_order_ship_date' => 'mauwoo_user_meta',
		'mwb_last_order_num'       => 'mauwoo_user_meta',
		'mwb_last_pay_method'      => 'mauwoo_user_meta',
		'mwb_current_orders'       => 'mauwoo_user_meta',

		'mwb_total_val_of_orders'  => 'mauwoo_user_meta',
		'mwb_avg_order_value'      => 'mauwoo_user_meta',
		'mwb_total_orders'         => 'mauwoo_user_meta',
		'mwb_first_order_date'     => 'mauwoo_user_meta',
		'mwb_first_order_val'      => 'mauwoo_user_meta',
		'mwb_last_order_date'      => 'mauwoo_user_meta',
		'mwb_last_order_val'       => 'mauwoo_user_meta',
		'mwb_avg_days_bt_orders'   => 'mauwoo_user_meta',
		'mwb_acc_creation_date'    => 'mauwoo_user_meta',
		'mwb_order_monetary'       => 'mauwoo_user_meta',
		'mwb_order_frequency'      => 'mauwoo_user_meta',
		'mwb_order_recency'        => 'mauwoo_user_meta',
	);

	/**
	 * Constructor.
	 *
	 * @param int $contact_id    contact id to get property values of.
	 */
	public function __construct( $contact_id ) {

		$this->_contact_id = $contact_id;

		$this->_user = get_user_by( 'id', $this->_contact_id );
	}

	/**
	 * Property value.
	 *
	 * @param  string $property_name    name of the contact property.
	 * @since 1.0.0
	 */
	public function _get_property_value( $property_name ) {

		$value = '';

		if ( ! empty( $property_name ) ) {
			// get the callback.
			$callback_function = $this->_get_property_callback( $property_name );

			if ( ! empty( $callback_function ) ) {

				// get the value by calling respective callback.
				$value = $this->$callback_function( $property_name );
			}
		}

		$value = apply_filters( 'mautic_woo_contact_property_value', $value, $property_name, $this->_contact_id );

		return $value;
	}

	/**
	 * Filter the property callback to get value of.
	 *
	 * @param  strig $property_name   name of the property.
	 * @return string/false             callback function name or false.
	 */
	private function _get_property_callback( $property_name ) {
		// check if the property name exists in the array.
		if ( array_key_exists( $property_name, $this->_property_callbacks ) ) {
			// if exists then get the callback name.
			$callback = $this->_property_callbacks[ $property_name ];

			return $callback;
		}

		return false;
	}

	/**
	 * Get contact user role.
	 *
	 * @return string    user role of the current contact.
	 * @since 1.0.0
	 */
	public function get_contact_group() {
		// get roles from user object.
		$user_roles = isset( $this->_user->roles ) ? $this->_user->roles : '';

		return $user_roles;
	}

	/**
	 * User email
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function _get_mail() {
		// get it from user object.
		if( !empty( $this->_user) ) {
			$data ="";
			$data = $this->_user->data;
			if( !empty( $data ) ) {
				$user_email = "";
				$user_email = $data->user_email;
			}
		}
		return $user_email;
	}


	/**
	 * User details with mauwoo_ prefix.
	 *
	 * @since 1.0.0
	 * @return string|array
	 * @param string $key key of meta.
	 */
	public function mauwoo_user_meta( $key ) {

		// check if the property value is already in cache.
		if ( array_key_exists( $key, $this->_cache ) ) {
			// return the cache value.
			return $this->_cache[ $key ];
		}

		$selected_order_statuses = get_option( 'mautic-woo-selected-order-status', array() );

		if ( empty( $selected_order_statuses ) ) {
			$selected_order_statuses = array_keys( wc_get_order_statuses() );
		}

		// get all the orders of the contact.
		$customer_orders = get_posts(
			array(
				'numberposts' => -1,
				'meta_key'    => '_customer_user',
				'meta_value'  => $this->_contact_id,
				'post_type'   => wc_get_order_types(),
				'post_status' => $selected_order_statuses,
				'order'       => 'DESC', // last order first.
			)
		);

		$customer = new WP_User( $this->_contact_id );

		$order_frequency = 0;

		$account_creation = isset( $customer->data->user_registered ) ? $customer->data->user_registered : '';

		$account_creation = strtotime( $account_creation );

		$this->_cache['mwb_acc_creation_date'] = date( 'Y/m/d', $account_creation );

		$this->_cache['mwb_current_orders'] = 0;

		$this->_cache['mwb_order_recency'] = 1;

		$this->_cache['mwb_order_frequency'] = 1;

		$this->_cache['mwb_order_monetary'] = 1;

		$contact_newsletter_subscription = 'no';

		$contact_newsletter_subscription = apply_filters( 'mautic_woo_user_newsletter_subscription', $contact_newsletter_subscription, $this->_contact_id );

		$this->_cache['mwb_newsletter_subs'] = $contact_newsletter_subscription;

		$this->_cache['mwb_total_val_of_orders'] = 0;

		// if customer have orders.
		if ( is_array( $customer_orders ) && count( $customer_orders ) ) {
			// total number of customer orders.
			$this->_cache['mwb_total_orders'] = count( $customer_orders );

			$order_frequency = $this->_cache['mwb_total_orders'];

			$counter = 0;

			$products_count = 0;

			foreach ( $customer_orders as $order_details ) {

				// get the order id.
				$order_id = isset( $order_details->ID ) ? intval( $order_details->ID ) : 0;

				// if order id not found let's check for another order.
				if ( ! $order_id ) {
					continue;
				}

				// order date.
				$order_date = get_post_time( 'U', true, $order_id );

				// get order.
				$order = new WC_Order( $order_id );

				// check for WP_Error object.
				if ( empty( $order ) || is_wp_error( $order ) ) {
					continue;
				}

				$order_status = $order->get_status();

				$order_total = $order->get_total();

				$this->_cache['mwb_total_val_of_orders'] += floatval( $order_total );

				if ( $order_status !== 'failed' && $order_status !== 'cancelled' && $order_status !== 'refunded' && $order_status !== 'completed' ) {

					$this->_cache['mwb_current_orders'] += 1;
				}
				
				// Check for last order and finish all last order calculations.
				if ( ! $counter ) {
					// last order date.
					$this->_cache['mwb_last_order_date'] = date( 'Y/m/d', get_post_time( 'U', true, $order_id ) );

					$last_order_date = get_post_time( 'U', true, $order_id );

					$this->_cache['mwb_last_order_val'] = $order_total;

					$this->_cache['mwb_last_order_num'] = $order_id;

					$this->_cache['mwb_last_order_ff_stat'] = 'wc-' . $order->get_status();

					$this->_cache['mwb_last_order_ship_date'] = apply_filters( 'mauwoo_order_shipment_date', '', $order_id );

					$this->_cache['mwb_last_order_track_num'] = $order->get_order_number();

					$this->_cache['mwb_last_order_track_url'] = apply_filters( 'mauwoo_order_tracking_url', '', $order_id );

					$this->_cache['mwb_last_order_stat'] = 'wc-' . $order->get_status();

					$this->_cache['mwb_last_pay_method'] = $order->get_payment_method_title();
				}

				// check for first order.
				if ( ( count( $customer_orders ) - 1 ) === $counter ) {
					// first order based calculation here..
					$this->_cache['mwb_first_order_date'] = date( 'Y/m/d', get_post_time( 'U', true, $order_id ) );
					$this->_cache['mwb_first_order_val']  = $order_total;
				}

				$counter++;
			}

			// rest calculations here.
			$this->_cache['mwb_avg_order_value'] = floatval( $this->_cache['mwb_total_val_of_orders'] / $this->_cache['mwb_total_orders'] );

			$mauwoo_rfm_at_5 = array(
				0 => 30,
				1 => 20,
				2 => 1000,
			);

			$mauwoo_from_rfm_4 = array(
				0 => 31,
				1 => 10,
				2 => 750,
			);

			$mauwoo_to_rfm_4 = array(
				0 => 90,
				1 => 20,
				2 => 1000,
			);

			$mauwoo_from_rfm_3 = array(
				0 => 91,
				1 => 5,
				2 => 500,
			);

			$mauwoo_to_rfm_3 = array(
				0 => 180,
				1 => 10,
				2 => 750,
			);

			$mauwoo_from_rfm_2 = array(
				0 => 181,
				1 => 2,
				2 => 250,
			);

			$mauwoo_to_rfm_2 = array(
				0 => 365,
				1 => 5,
				2 => 500,
			);

			$mauwoo_rfm_at_1 = array(
				0 => 365,
				1 => 2,
				2 => 250,
			);

			$order_monetary = $this->_cache['mwb_total_val_of_orders'];

			$current_date    = gmdate( 'Y-m-d H:i:s', time() );
			$current_date    = new DateTime( $current_date );
			$last_order_date = gmdate( 'Y-m-d H:i:s', $last_order_date );
			$last_order_date = new DateTime( $last_order_date );
			$order_recency   = date_diff( $current_date, $last_order_date, true );

			$order_recency = $order_recency->days;

			if ( $order_recency <= $mauwoo_rfm_at_5[0] ) {
				$this->_cache['mwb_order_recency'] = 5;
			} elseif ( $order_recency > $mauwoo_from_rfm_4[0] && $order_recency <= $mauwoo_to_rfm_4[0] ) {
				$this->_cache['mwb_order_recency'] = 4;
			} elseif ( $order_recency > $mauwoo_from_rfm_3[0] && $order_recency <= $mauwoo_to_rfm_3[0] ) {
				$this->_cache['mwb_order_recency'] = 3;
			} elseif ( $order_recency > $mauwoo_from_rfm_2[0] && $order_recency <= $mauwoo_to_rfm_2[0] ) {
				$this->_cache['mwb_order_recency'] = 2;
			} elseif ( $order_recency > $mauwoo_rfm_at_1[0] ) {
				$this->_cache['mwb_order_recency'] = 1;
			}

			if ( $order_frequency >= $mauwoo_rfm_at_5[1] ) {
				$this->_cache['mwb_order_frequency'] = 5;
			} elseif ( $order_frequency >= $mauwoo_from_rfm_4[1] && $order_frequency < $mauwoo_to_rfm_4[1] ) {
				$this->_cache['mwb_order_frequency'] = 4;
			} elseif ( $order_frequency >= $mauwoo_from_rfm_3[1] && $order_frequency < $mauwoo_to_rfm_3[1] ) {
				$this->_cache['mwb_order_frequency'] = 3;
			} elseif ( $order_frequency >= $mauwoo_from_rfm_2[1] && $order_frequency < $mauwoo_to_rfm_2[1] ) {
				$this->_cache['mwb_order_frequency'] = 2;
			} elseif ( $order_frequency < $mauwoo_rfm_at_1[1] ) {
				$this->_cache['mwb_order_frequency'] = 1;
			}

			if ( $order_monetary >= $mauwoo_rfm_at_5[2] ) {
				$this->_cache['mwb_order_monetary'] = 5;
			} elseif ( $order_monetary >= $mauwoo_from_rfm_4[2] && $order_monetary < $mauwoo_to_rfm_4[2] ) {
				$this->_cache['mwb_order_monetary'] = 4;
			} elseif ( $order_monetary >= $mauwoo_from_rfm_3[2] && $order_monetary < $mauwoo_to_rfm_3[2] ) {
				$this->_cache['mwb_order_monetary'] = 3;
			} elseif ( $order_monetary >= $mauwoo_from_rfm_2[2] && $order_monetary < $mauwoo_to_rfm_2[2] ) {
				$this->_cache['mwb_order_monetary'] = 2;
			} elseif ( $order_monetary < $mauwoo_rfm_at_1[2] ) {
				$this->_cache['mwb_order_monetary'] = 1;
			}
		}

		if ( isset( $this->_cache[ $key ] ) ) {

			return $this->_cache[ $key ];
		}
	}
}

