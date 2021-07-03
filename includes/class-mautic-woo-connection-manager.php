<?php
/**
 * All api GET/POST functionalities.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 */

/**
 * Handles all mautic api reqests/response related functionalities of the plugin.
 *
 * Provide a list of functions to manage all the requests
 * that needs in our integration to get/fetch data
 * from/to mautic.
 *
 * @package    enhanced-woocommerce-mautic-integration
 * @subpackage enhanced-woocommerce-mautic-integration/includes
 * @author     MakeWebBetter <webmaster@makewebbetter.com>
 */
class MauticWooConnectionMananager {

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var MauticWooConnectionMananager    The single instance of the MauticWooConnectionMananager
	 */
	protected static $instance = null;


	/**
	 * Main MauticWooConnectionMananager Instance.
	 *
	 * Ensures only one instance of MauticWooConnectionMananager is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @return MauticWooConnectionMananager - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Refreshing access token from refresh token.
	 *
	 * @since 1.0.0
	 * @return bool
	 * @param string $mpubkey mautic public key.
	 * @param string $mseckey mautic secret key.
	 */
	public function mautic_woo_refresh_token( $mpubkey, $mseckey ) {

		$endpoint      = '/oauth/v2/token';
		$refresh_token = get_option( 'mautic_woo_refresh_token', '' );

		$data = array(
			'grant_type'    => 'refresh_token',
			'client_id'     => $mpubkey,
			'client_secret' => $mseckey,
			'refresh_token' => $refresh_token,
			'redirect_uri'  => admin_url() . 'admin.php',
		);

		$body = http_build_query( $data );

		return $this->mautic_woo_oauth_post_api( $endpoint, $body, 'refresh' );
	}

	/**
	 * Fetching access token from code.
	 *
	 * @since 1.0.0
	 * @return bool
	 * @param string $mpubkey mautic public key.
	 * @param string $mseckey mautic secret key.
	 * @param string $code oauth code.
	 */
	public function mautic_woo_fetch_access_token_from_code( $mpubkey, $mseckey, $code ) {

		$endpoint = '/oauth/v2/token';
		$data     = array(
			'client_id'     => $mpubkey,
			'client_secret' => $mseckey,
			'grant_type'    => 'authorization_code',
			'redirect_uri'  => admin_url( 'admin.php' ),
			'code'          => $code,
		);

		$post_body = http_build_query( $data );

		return $this->mautic_woo_oauth_post_api( $endpoint, $post_body, 'access' );
	}

	/**
	 * Returning saved access token.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function mautic_woo_get_access_token() {

		return get_option( 'mautic_woo_access_token', false );
	}

	/**
	 * Post api for oauth access and refresh token.
	 *
	 * @since 1.0.0
	 * @return bool
	 * @param string $endpoint endpoint of api.
	 * @param array  $body body params.
	 * @param string $action action to be performed.
	 */
	public function mautic_woo_oauth_post_api( $endpoint, $body, $action ) {

		$headers  = array(
			'Accept: application/json',
		);
		$url      = Mautic_Woo::get_client_mautic_base_url() . $endpoint;
		$response = wp_remote_post(
			$url,
			array(
				'body'    => $body,
				'headers' => $headers,
			)
		);

		if ( ! is_wp_error( $response ) ) {

			$status_code = wp_remote_retrieve_response_code( $response );
			$res_message = wp_remote_retrieve_response_message( $response );

			if ( 200 === $status_code ) {

				$api_body = wp_remote_retrieve_body( $response );
				if ( $api_body ) {

					$api_body = json_decode( $api_body );

				}

				if ( ! empty( $api_body->refresh_token ) && ! empty( $api_body->access_token ) && ! empty( $api_body->expires_in ) ) {

					update_option( 'mautic_woo_access_token', $api_body->access_token );
					update_option( 'mautic_woo_refresh_token', $api_body->refresh_token );
					update_option( 'mautic_woo_token_expiry', time() + $api_body->expires_in );
					update_option( 'mautic_woo_valid_client_ids_stored', true );
					$message = __( 'Fetching and refreshing access token', 'enhanced-woocommerce-mautic-integration' );
					$this->mautic_woo_create_log( $message, $endpoint, $response );
					update_option( 'mautic_woo_oauth_success', true );
					return true;

				} else {

					$message = __( 'Something went wrong!. Please try again.', 'enhanced-woocommerce-mautic-integration' );
					update_option( 'mautic_woo_api_validation_error_message', $message );
					update_option( 'mautic_woo_valid_client_ids_stored', false );
					$this->mautic_woo_create_log( $message, $endpoint, $response );
				}
			} else {

				$message = __( 'Something went wrong.', 'enhanced-woocommerce-mautic-integration' );
				update_option( 'mautic_woo_api_validation_error_message', $message );
				update_option( 'mautic_woo_valid_client_ids_stored', false );
			}
		}

			return false;
	}

		/**
		 * Create property on mautic.
		 *
		 * @since 1.0.0
		 * @return array
		 * @param array $prop_details proeprty details.
		 */
	public function create_property( $prop_details ) {

		$response = array(
			'code'    => 400,
			'message' => __( 'Failed in creating field', 'enhanced-woocommerce-mautic-integration' ),
		);

		if ( is_array( $prop_details ) ) {

			if ( isset( $prop_details['alias'] ) ) {

				$url = '/api/fields/contact/new';

				$access_token = $this->mautic_woo_get_access_token();

				$url                          = Mautic_Woo::get_client_mautic_base_url() . $url;
				$method                       = 'POST';
				$prop_details['access_token'] = $access_token;

				$response = wp_remote_post(
					$url,
					array(
						'method'      => $method,
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => array(),
						'body'        => $prop_details,
						'cookies'     => array(),
					)
				);
			}
		}

			$code    = '';
			$message = '';

		if ( ! is_wp_error( $response ) ) {

			$body_response = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $body_response ) ) {

				if ( ! empty( $body_response->errors ) ) {

					foreach ( $body_response->errors as $single_error ) {

						if ( ! empty( $single_error->code ) ) {

							$code = $single_error->code;
						}
						if ( ! empty( $single_error->message ) ) {

							$message = $single_error->message;
						}
					}
					//phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					if ( 400 === $code && 'alias: Another field is already using this alias. Please choose another or leave blank to have it autogenerated.' == $message ) {

						$code    = 201;
						$message = __( 'Success', 'enhanced-woocommerce-mautic-integration' );
					}
				} elseif ( ! empty( $body_response->field ) ) {

					$field_info = $body_response->field;
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					if ( ! empty( $field_info->isPublished ) && $field_info->isPublished && ! empty( $field_info->id ) && ! empty( $field_info->alias ) ) {

						$mautic_field_ids = get_option( 'mautic_custom_field_ids', array() );

						$mautic_field_ids[ $field_info->alias ] = $field_info->id;

						update_option( 'mautic_custom_field_ids', $mautic_field_ids );
						$code    = 201;
						$message = __( 'Success', 'enhanced-woocommerce-mautic-integration' );
					}
					//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					if ( empty( $field_info->isPublished ) && ! empty( $field_info->id ) && ! empty( $field_info->alias ) && ( 'mwb_customer_group' === $field_info->alias ) ) {

						$mautic_field_ids = get_option( 'mautic_custom_field_ids', array() );

						$mautic_field_ids[ $field_info->alias ] = $field_info->id;

						update_option( 'mautic_custom_field_ids', $mautic_field_ids );
						$code    = 201;
						$message = __( 'Success', 'enhanced-woocommerce-mautic-integration' );
					}
				}
			}
		}

		if ( ! empty( $code ) && ! empty( $message ) ) {

			$response = array(
				'code'    => $code,
				'message' => $message,
			);
		}

			$message = __( 'Creating Custom Fields', 'enhanced-woocommerce-mautic-integration' );

			$this->mautic_woo_create_log( $message, $url, $response );

			return $response;
	}

			/**
			 * Create or update contacts.
			 *
			 * @since 1.0.0
			 * @return array
			 * @param  array $details    mautic acceptable contacts array.
			 */
	public function create_or_update_contacts( $details ) {

		$response = array(
			'code'    => 400,
			'message' => __( 'Failed to create or update contact.', 'enhanced-woocommerce-mautic-integration' ),
		);

		$email = $details['email'];

		if ( is_array( $details ) ) {

			$url                     = '/api/contacts/new';
			$url                     = Mautic_Woo::get_client_mautic_base_url() . $url;
			$access_token            = $this->mautic_woo_get_access_token();
			$method                  = 'POST';
			$details                 = apply_filters( 'mautic_woo_filter_contact_properties', $details );
			$details['access_token'] = $access_token;
			$response                = wp_remote_post(
				$url,
				array(
					'method'      => $method,
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => $details,
					'cookies'     => array(),
				)
			);
		}

			$code    = '';
			$message = '';

		if ( ! is_wp_error( $response ) ) {

				$body_response = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $body_response ) ) {

				if ( ! empty( $body_response->errors ) ) {

					foreach ( $body_response->errors as $single_error ) {

						if ( ! empty( $single_error->code ) ) {

								$code = $single_error->code;
						}
						if ( ! empty( $single_error->message ) ) {

									$message = $single_error->message;
						}
					}
				} elseif ( ! empty( $body_response->contact ) ) {

							$contact_info = $body_response->contact;
							//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					if ( ! empty( $contact_info->isPublished ) && $contact_info->isPublished && ! empty( $contact_info->id ) ) {

						$code    = 201;
						$message = __( 'Success', 'enhanced-woocommerce-mautic-integration' );
					}
				}
			}
		}

		if ( ! empty( $code ) && ! empty( $message ) ) {
					$response = array(
						'code'    => $code,
						'message' => $message,
					);
		}

						$message = __( 'Updating or Creating users data', 'enhanced-woocommerce-mautic-integration' );

						$this->mautic_woo_create_log( $message, $url, $response, $email );

						return $response;
	}

				/**
				 * Getting user info api call.
				 *
				 * @since 1.0.0
				 * @return array
				 */
	public function get_mautic_self_user_info() {

		$url                     = '/api/users/self';
		$url                     = Mautic_Woo::get_client_mautic_base_url() . $url;
		$access_token            = $this->mautic_woo_get_access_token();
		$method                  = 'GET';
		$details                 = array();
		$details['access_token'] = $access_token;
		$response                = wp_remote_request(
			$url,
			array(
				'method'      => $method,
				'timeout'     => 5,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'body'        => $details,
				'cookies'     => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
					$response = array();
		} elseif ( ! empty( $response['body'] ) ) {
						$response = json_decode( $response['body'] );
		} else {
			$response = array();
		}

								return $response;
	}

					/**
					 * Create log of requests.
					 *
					 * @param  string $message     mautic log message.
					 * @param  string $url         mautic acceptable url.
					 * @param  array  $response    mautic response array.
					 *  @param  array  $email    mautic email ..
					 * @access public
					 * @since 1.0.0
					 */
	public function mautic_woo_create_log( $message, $url, $response, $email = '' ) {

		$log_dir = WC_LOG_DIR . 'mautic-woo-logs.log';

		if ( ! is_dir( $log_dir ) ) {

			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged , WordPress.WP.AlternativeFunctions.file_system_read_fopen
			@fopen( WC_LOG_DIR . 'mauwoo-pro-logs.log', 'a' );

		}

		if ( '' === $email ) {

			$log = 'Time: ' . current_time( 'F j, Y  g:i a' ) . PHP_EOL .
			'Process: ' . $message . PHP_EOL .
			'URL: ' . $url . PHP_EOL .
			'Response: ' . wp_json_encode( $response ) . PHP_EOL .
			'-----------------------------------' . PHP_EOL;

		} else {

			$log = 'Time: ' . current_time( 'F j, Y  g:i a' ) . PHP_EOL .
			'Process: ' . $message . PHP_EOL .
			'URL: ' . $url . PHP_EOL .
			'Email: ' . $email . PHP_EOL .
			'Response: ' . wp_json_encode( $response ) . PHP_EOL .
			'-----------------------------------' . PHP_EOL;

		}
		//phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
		file_put_contents( $log_dir, $log, FILE_APPEND );
	}
}
