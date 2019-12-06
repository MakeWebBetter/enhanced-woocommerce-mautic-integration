<?php
/**
 * All customer details.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 */

/**
 * Stores all customer data that needs to be updated on mautic.
 *
 * Provide a list of properties and associated data for customer
 * so that at the time of updating a customer on mautic we can
 * simply create an instance of this class and get everything
 * managed.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MauticWooCustomer {

	/**
	 * Contact in the form of acceptable by mautic.
	 *
	 * @since 1.0.0
	 * @var json
	 */
	public $contact;

	/**
	 * WooCommerce Customer ID
	 *
	 * @since 1.0.0
	 * @var json
	 */
	public $_contact_id;

	/**
	 * Contact Properties.
	 *
	 * @since 1.0.0
	 * @var Array
	 */
	private $_properties = array();

	/**
	 * Instance of MauticWooPropertyCallbacks class.
	 *
	 * @since 1.0.0
	 * @var MauticWooPropertyCallbacks
	 */
	private $_callback_instance = null;

	/**
	 * Load the modified customer properties.
	 *
	 * Set all the modified customer properties so that they will be
	 * ready in the form of directly acceptable by mautic api.
	 *
	 * @param string $contact_id This variable contain contact id.
	 * @since    1.0.0
	 */
	public function __construct( $contact_id ) {

		// load the contact id in the class property.
		$this->_contact_id = $contact_id;

		// store the instance of property callback.
		$this->_callback_instance = new MauticWooPropertyCallbacks( $this->_contact_id );

		// Prepare the modified fields data and store it in the contact.
		$this->prepare_modified_fields();
	}

	/**
	 * Get user email.
	 *
	 * @since 1.0.0
	 */
	public function get_email() {

		return $this->_callback_instance->_get_mail();
	}

	/**
	 * Contacts all properties.
	 *
	 * @return array    and key value pair array of properties.
	 * @since 1.0.0
	 */
	public function get_contact_properties() {

		// let others decide if they have modified fields in there integration.
		$this->_properties = apply_filters( 'mautic_woo_contact_modified_fields', $this->_properties, $this->_contact_id );

		return $this->_properties;
	}

	/**
	 * Format modified fields of customer.
	 *
	 * Check for all the modified fields till the last update
	 * and prepare them in the mautic api acceptable form.
	 *
	 * @since 1.0.0
	 */
	private function prepare_modified_fields() {

		$modified_fields = MauticWooContactProperties::get_instance()->_get( 'properties' );

		// if some data are updated after last update with mautic.
		if ( is_array( $modified_fields ) && count( $modified_fields ) ) {
			// loop them all, as they are in the form of group and field.
			foreach ( $modified_fields as $group_fields ) {
				// check if fields are there in the group field.
				if ( is_array( $group_fields ) ) {
					// let's loop each field.
					foreach ( $group_fields as $field ) {
						// Store the property value.
						$property                             = $this->_prepare_property( $field['alias'] );
						$this->_properties[ $field['alias'] ] = $property;
					}
				}
			}
		}
	}

	/**
	 * Prepare property in the form of key value accepted by mautic.
	 *
	 * @param  array $property     array of the property details to validate the value.
	 * @return array               formatted key value pair.
	 */
	public function _prepare_property( $property ) {

		// if property name is not empty.
		if ( ! empty( $property ) ) {
			// get property value.
			$property_val = $this->_callback_instance->_get_property_value( $property, $this->_contact_id );
			// format the property name and value.
			return $property_val;
		}
	}
}
