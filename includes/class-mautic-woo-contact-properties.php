<?php
/**
 * Manage all contact properties.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 */

/**
 * Manage all contact properties.
 *
 * Provide a list of functions to manage all the information
 * about contacts properties and lists along with option to
 * change/update the mapping field on mautic.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MauticWooContactProperties {

	/**
	 * Contact Property Groups.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $groups;

	/**
	 * Contact Properties.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $properties;

	/**
	 * MauticWooContactProperties Instance.
	 *
	 * @since 1.0.0
	 * @var $instance
	 */
	protected static $instance = null;

	/**
	 * Main MauticWooContactProperties Instance.
	 *
	 * Ensures only one instance of MauticWooContactProperties is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @return MauticWooContactProperties - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Define the contact prooperties related functionality.
	 *
	 * Set the contact groups and properties that we are going to use
	 * for creating/updating the contact information for our tacking purpose
	 * and providing other developers to add there field and group for tracking
	 * too by simply using our hooks.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->groups     = $this->_set( 'groups' );
		$this->properties = $this->_set( 'properties' );
	}

	/**
	 * Get groups/properties..
	 *
	 * @param  string $option      groups/properties.
	 * @param  string $group_name      group name.
	 * @return array          Array of groups/properties information.
	 */
	public function _get( $option, $group_name = '' ) {

		if ( 'groups' === $option ) {

			return $this->groups;
		} elseif ( 'properties' === $option ) {

			if ( ! empty( $group_name ) && isset( $this->properties[ $group_name ] ) ) {

				return $this->properties[ $group_name ];
			}

			return $this->properties;
		}
	}

	/**
	 * Get an array of required option.
	 *
	 * @param  String $option         the identifier.
	 * @return Array        An array of values.
	 * @since 1.0.0
	 */
	private function _set( $option ) {

		$values = array();

		// if we are looking for groups, let us add our predefined groups.
		if ( 'groups' === $option ) {

			// customer details.
			$values[] = array(
				'name'        => 'customer_group',
				'displayName' => __( 'Customer Group', 'enhanced-woocommerce-mautic-integration' ),
			);
			// order details.
			$values[] = array(
				'name'        => 'order',
				'displayName' => __( 'Order', 'enhanced-woocommerce-mautic-integration' ),
			);
			// RFM details.
			$values[] = array(
				'name'        => 'rfm_fields',
				'displayName' => __( 'RFM Information', 'enhanced-woocommerce-mautic-integration' ),
			);
		} elseif ( 'properties' === $option ) {

			// let's check for all active tracking groups and get there associated properties.
			$values = $this->get_all_active_groups_properties();
		}

		// add your values to the either groups or properties or segments.
		return apply_filters( 'mautic_woo_contact_' . $option, $values );
	}


	/**
	 * Check for the active groups and get there properties.
	 *
	 * @return Array Properties array with there associated group.
	 * @since 1.0.0
	 */
	private function get_all_active_groups_properties() {

		$active_groups_properties = array();
		// get all the active groups.
		$active_groups = $this->get_active_groups();

		// check if we get active groups in the form of array, and has groups.
		if ( is_array( $active_groups ) && count( $active_groups ) ) {

			foreach ( $active_groups as $active_group ) {

				if ( ! empty( $active_group ) && ! is_array( $active_group ) ) {

					$active_groups_properties[ $active_group ] = $this->_get_group_properties( $active_group );
				}
			}
		}
		// add your active group properties if you want.
		return apply_filters( 'mautic_woo_active_groups_properties', $active_groups_properties );
	}


	/**
	 * Filter for active groups only.
	 *
	 * @return Array active group names.
	 * @since 1.0.0
	 */
	private function get_active_groups() {

		$active_groups = array();

		$all_groups = $this->_get( 'groups' );

		if ( is_array( $all_groups ) && count( $all_groups ) ) {

			foreach ( $all_groups as $group_details ) {

				$group_name = isset( $group_details['name'] ) ? $group_details['name'] : '';

				if ( ! empty( $group_name ) ) {

					$is_active = get_option( 'mautic_woo_active_group' . $group_name, true );

					if ( $is_active ) {

						$active_groups[] = $group_name;
					}
				}
			}
		}
		// let's developer manage there groups seperately if they want.
		return apply_filters( 'mautic_woo_active_groups', $active_groups );
	}


	/**
	 * Get all the groups properties.
	 *
	 * @param   string $group_name     name of the existed valid mautic contact properties group.
	 * @return  Array      Properties array.
	 * @since 1.0.0
	 */
	private function _get_group_properties( $group_name ) {

		$group_properties = array();
		// if the name is not empty.
		if ( ! empty( $group_name ) ) {

			if ( 'customer_group' === $group_name ) {

				$group_properties[] = array(
					'alias'          => 'mwb_customer_group',
					'label'          => __( 'Customer Group/ User role', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'multiselect',
					'isVisible'      => false,
					'isShortVisible' => false,
					'defaultValue'   => 'null',
					'isPublished'    => false,
					'properties'     => array( 'list' => $this->get_user_roles() ),
				);
			} elseif ( 'order' === $group_name ) {

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_stat',
					'label'          => __( 'Last Order Status', 'enhanced-woocommerce-mautic-integration' ),
					'isVisible'      => false,
					'isShortVisible' => false,
					'type'           => 'select',
					'defaultValue'   => 'null',
					'properties'     => array( 'list' => $this->get_order_statuses() ),
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_ff_stat',
					'label'          => __( 'Last Order Fulfillment Status', 'enhanced-woocommerce-mautic-integration' ),
					'isVisible'      => false,
					'isShortVisible' => false,
					'type'           => 'select',
					'defaultValue'   => 'null',
					'properties'     => array( 'list' => $this->get_order_statuses() ),
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_track_num',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Last Order Tracking Number', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_track_url',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Last Order Tracking URL', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'text',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_ship_date',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Last Order Shipment Date', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'date',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_num',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Last Order Number', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_pay_method',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Last Order Payment Method', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'text',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_current_orders',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Total Number of Current Orders', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

			} elseif ( 'rfm_fields' === $group_name ) {

				$group_properties[] = array(
					'alias'          => 'mwb_total_val_of_orders',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Total Value of Orders', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_avg_order_value',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Average Order Value', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_total_orders',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Total Number of Orders', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_first_order_val',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'First Order Value', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_first_order_date',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'First Order Date', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'date',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_val',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Last Order Value', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'number',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_last_order_date',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Last Order Date', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'date',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_acc_creation_date',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Account Creation Date', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'date',
				);

				$group_properties[] = array(
					'alias'          => 'mwb_order_monetary',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Order Monetary Rating', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'select',
					'defaultValue'   => 'null',
					'properties'     => array( 'list' => $this->get_rfm_rating() ),
				);

				$group_properties[] = array(
					'alias'          => 'mwb_order_frequency',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Order Frequency Rating', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'select',
					'defaultValue'   => 'null',
					'properties'     => array( 'list' => $this->get_rfm_rating() ),
				);

				$group_properties[] = array(
					'alias'          => 'mwb_order_recency',
					'isVisible'      => false,
					'isShortVisible' => false,
					'label'          => __( 'Order Recency Rating', 'enhanced-woocommerce-mautic-integration' ),
					'type'           => 'select',
					'defaultValue'   => 'null',
					'properties'     => array( 'list' => $this->get_rfm_rating() ),
				);
			}
		}

		return apply_filters( 'mautic_woo_group_properties', $group_properties, $group_name );
	}

	/**
	 * Formatted options for user role enumaration.
	 *
	 * @return JSON    formatted json encoded array of user role options.
	 * @since 1.0.0
	 */
	public static function get_user_roles() {

		$exiting_user_roles = array();

		if ( ! function_exists( 'get_editable_roles' ) ) {

			require_once ABSPATH . 'wp-admin/includes/user.php';
		}

		global $wp_roles;

		$user_roles = ! empty( $wp_roles->role_names ) ? $wp_roles->role_names : array();

		if ( is_array( $user_roles ) && count( $user_roles ) ) {

			foreach ( $user_roles as $role => $role_info ) {

				$role_label = ! empty( $role_info ) ? $role_info : $role;

				$exiting_user_roles[] = array(
					'label' => $role_label,
					'value' => $role,
				);
			}
		}

		$exiting_user_roles[] = array(
			'label' => 'Nil',
			'value' => 'null',
		);
		$exiting_user_roles   = apply_filters( 'mautic_woo_user_role_options', $exiting_user_roles );

		return $exiting_user_roles;
	}


	/**
	 * Get all available woocommerce order statuses
	 *
	 * @return JSON Order statuses in the form of enumaration options.
	 * @since 1.0.0
	 */
	public static function get_order_statuses() {

		$all_wc_statuses = array();

		// get all statuses.
		$all_status = wc_get_order_statuses();

		// if status available.
		if ( is_array( $all_status ) && count( $all_status ) ) {

			foreach ( $all_status as $status_id => $status_label ) {

				$all_wc_statuses[] = array(
					'label' => $status_label,
					'value' => $status_id,
				);
			}
		}
		$all_wc_statuses[] = array(
			'label' => 'Nil',
			'value' => 'null',
		);
		$all_wc_statuses   = apply_filters( 'mautic_woo_order_status_options', $all_wc_statuses );

		return $all_wc_statuses;
	}

	/**
	 * Get ratings for RFM analysis
	 *
	 * @return ratings for RFM analysis
	 * @since 1.0.0
	 */
	public function get_rfm_rating() {

		$rating = array();

		$rating[] = array(
			'label' => __( '5', 'enhanced-woocommerce-mautic-integration' ),
			'value' => 5,
		);
		$rating[] = array(
			'label' => __( '4', 'enhanced-woocommerce-mautic-integration' ),
			'value' => 4,
		);
		$rating[] = array(
			'label' => __( '3', 'enhanced-woocommerce-mautic-integration' ),
			'value' => 3,
		);
		$rating[] = array(
			'label' => __( '2', 'enhanced-woocommerce-mautic-integration' ),
			'value' => 2,
		);
		$rating[] = array(
			'label' => __( '1', 'enhanced-woocommerce-mautic-integration' ),
			'value' => 1,
		);
		$rating[] = array(
			'label' => 'Nil',
			'value' => 'null',
		);
		$rating   = apply_filters( 'mautic_woo_rfm_ratings', $rating );

		return $rating;
	}

	/**
	 * Get user actions for marketing
	 *
	 * @return array  marketing actions for users
	 * @since 1.0.0
	 */
	public function get_user_marketing_action() {

		$user_actions = array();

		$user_actions[] = array(
			'label' => __( 'Yes', 'enhanced-woocommerce-mautic-integration' ),
			'value' => 'yes',
		);
		$user_actions[] = array(
			'label' => __( 'No', 'enhanced-woocommerce-mautic-integration' ),
			'value' => 'no',
		);
		$user_actions   = apply_filters( 'mautic_woo_user_marketing_actions', $user_actions );

		return $user_actions;
	}
}
