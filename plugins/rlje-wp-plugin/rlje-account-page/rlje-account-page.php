<?php
class RLJE_Account_Page {

	private $account_action = 'status';
	private $user_profile;
	private $api_base;

	public function __construct() {
		add_action( 'init', array( $this, 'add_browse_rewrite_rules' ) );
		add_action( 'init', array( $this, 'setup_profile' ) );
		add_action( 'template_redirect', array( $this, 'browse_template_redirect' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// add_filter( 'body_class', array( $this, 'browse_body_class' ) );
	}

	public function enqueue_scripts() {
		if ( in_array( get_query_var( 'pagename' ), [ 'account' ] ) ) {
			wp_enqueue_style( 'account-main-style', plugins_url( 'css/style.css', __FILE__ ) );
		}
	}

	public function setup_profile() {
		$this->api_base = constant( 'RLJE_BASE_URL' );
	}

	public function browse_body_class( $classes ) {

	}

	// Figures out the memebership term
	public function get_user_term() {
		if ( $this->user_profile['Membership']['Term'] == 30 && strtolower( $this->user_profile['Membership']['TermType'] ) == 'day' ) {
			return 'monthly';
		} elseif ( $this->user_profile['Membership']['Term'] == 12 && strtolower( $this->user_profile['Membership']['TermType'] ) == 'month' ) {
			return 'yearly';
		} else {
			return 'unknown';
		}
	}

	// If account is active. we'll get NextBillingDateAsLong
	// If account was cancelled, we don't get that.
	public function get_next_billing_date() {
		if ( isset( $this->user_profile['Membership']['NextBillingDateAsLong'] ) ) {
			return $this->user_profile['Membership']['NextBillingDate'];
		} else {
			return 'N/A';
		}
	}

	// If account is active. we'll get NextBillingAmount
	// If account was cancelled, we don't get that.
	public function get_next_billing_amount() {
		if ( isset( $this->user_profile['Membership']['NextBillingAmount'] ) ) {
			return $this->user_profile['Membership']['NextBillingAmount'];
		} else {
			return 'N/A';
		}
	}

	public function get_user_name() {
		return $this->user_profile['Customer']['FirstName'] . ' ' . $this->user_profile['Customer']['LastName'];
	}

	public function get_user_email() {
		return $this->user_profile['Customer']['Email'];
	}

	public function get_user_join_date() {
		return $this->user_profile['Customer']['OriginalMembershipJoinDate'];
	}

	function encodeHash( $data, $api_key = API_KEY ) {
		$hash = json_encode( $data ) . $api_key;
		return base64_encode( $hash );
	}

	public function add_browse_rewrite_rules() {
		add_rewrite_rule( '^account/([^/]*)/?', 'index.php?pagename=account&action=$matches[1]', 'top' );
		add_rewrite_tag( '%action%', '([^&]+)' );
	}

	function hitApi( $params, $method, $verb = 'GET' ) {
		$url = $this->api_base . '/' . $method;
		switch ( $verb ) {
			case 'GET':
				$raw_response = wp_remote_get( $url . '?' . http_build_query( $params ) );
				break;

			case 'POST':
				$raw_response = wp_remote_post(
					$url, [
						'headers' => [
							'x-atv-hash' => $this->encodeHash( $params ),
							'Accept'     => 'application/json',
						],
						'body'    => json_encode( $params ),
					]
				);
				if ( is_wp_error( $raw_response ) ) {
					error_log( 'Error hiting API ' . $raw_response->get_error_message() );
					$response = false;
				}
				break;

			default:
				// code...
				break;
		}
		$response = json_decode( wp_remote_retrieve_body( $raw_response ), true );
		return $response;
	}

	function getUserProfile( $session_id, $email_address ) {
		if ( ! empty( $session_id ) ) {
			$response = $this->hitApi( [ 'SessionID' => $session_id ], 'profile' );
		} elseif ( ! empty( $email_address ) ) {
			$response = $this->hitApi( [ 'Email' => $session_id ], 'profile' );

		}
		return $response;
	}

	function updateUserEmail( $session_id, $new_email ) {
		$params   = [
			'SessionID' => $session_id,
			'NewEmail'  => $new_email,
		];
		$response = $this->hitApi( $params, 'changeemail', 'POST' );
		return $response;
	}

	function logUserOut( $session_id ) {
		// Mark the session as inactive in WP object cache
		wp_cache_set( 'userStatus_' . md5( $session_id ), 'inactive', 'userStatus' );
		// Ask transient to delete userprofile for that session
		delete_transient( 'atv_userProfile_' . md5( $session_id ) );
	}

	public function show_subsection() {
		switch ( $this->account_action ) {
			case 'status':
				$partial_url = plugin_dir_path( __FILE__ ) . 'partials/status.php';
				break;

			case 'editEmail':
				$partial_url = plugin_dir_path( __FILE__ ) . 'partials/edit-email.php';
				break;

			case 'editPasword':
				$partial_url = plugin_dir_path( __FILE__ ) . 'partials/edit-password.php';
				break;
			
			case 'editBilling':
				$partial_url = plugin_dir_path( __FILE__ ) . 'partials/edit-billing.php';
				break;
			default:
				$partial_url = plugin_dir_path( __FILE__ ) . 'partials/status.php';
		}
		return $partial_url;
	}

	public function browse_template_redirect() {
		global $wp_query;
		$pagename = get_query_var( 'pagename' );
		$action   = get_query_var( 'action' );

		if ( 'account' === $pagename ) {
			if ( ! empty( $action ) ) {
				$this->account_action = $action;
			}
			if ( in_array( $this->account_action, ( [ 'status', 'editEmail', 'editPassword', 'editBilling', 'cancelMembership', 'applyCode', 'logout' ] ) ) ) {
				if ( ! isset( $_COOKIE['ATVSessionCookie'] ) || ! rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
					wp_redirect( home_url( 'signin' ), 303 );
					exit();
				}
				if ( 'logout' === $action ) {
					$this->logUserOut( $_COOKIE['ATVSessionCookie'] );
					setcookie( 'ATVSessionCookie', '', time() - 3600, '/' );
					wp_redirect( home_url(), 303 );
					exit();
				} elseif ( 'editEmail' === $action ) {
					if ( ! empty( $_POST ) ) {
						if ( empty( $_POST['new-email'] ) || empty( $_POST['new-email-confirm'] ) || $_POST['new-email'] !== $_POST['new-email-confirm'] ) {
							$message_error = 'The two email address do not match';

						} else {
							$email_response = $this->updateUserEmail( $_COOKIE['ATVSessionCookie'], $_POST['new-email'] );
							if ( isset( $email_response['error'] ) ) {
								$message_error = $email_response['error'];

							} elseif ( isset( $email_response['Email'] ) ) {
								$message_sucess = 'E-Mail address was successfully updated';
							} else {
								$message_error = 'There was an error updating E-mail address.';
							}
						}
					}
				} elseif ( 'editBilling' === $action ) {
					$this->user_profile = $this->getUserProfile( $_COOKIE['ATVSessionCookie'], null );
					// Prevent internal 404 on custome search page because of template_redirect hook.
					status_header( 200 );
					$wp_query->is_404  = false;
					$wp_query->is_page = true;
					// $wp_query->is_archive = true;
					ob_start();
					require_once plugin_dir_path( __FILE__ ) . 'templates/updatecard.php';
					$html = ob_get_clean();
					echo $html;
					exit();
				}
				$this->user_profile = $this->getUserProfile( $_COOKIE['ATVSessionCookie'], null );
				// Prevent internal 404 on custome search page because of template_redirect hook.
				status_header( 200 );
				$wp_query->is_404  = false;
				$wp_query->is_page = true;
				// $wp_query->is_archive = true;
				ob_start();
				require_once plugin_dir_path( __FILE__ ) . 'templates/main.php';
				$html = ob_get_clean();
				echo $html;
				exit();
			}
		}
	}
}

$rlje_account_page = new RLJE_Account_Page();
