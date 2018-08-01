<?php
class RLJE_signup_page {

	private $api_helper;

	public function __construct() {
		$this->api_helper = new RLJE_api_helper();
		add_action( 'init', array( $this, 'add_signup_rewrite_rule' ) );
		add_action( 'template_redirect', array( $this, 'signup_template_redirect' ) );

		add_action( 'wp_ajax_initialize_account', [ $this, 'initialize_account' ] );
		add_action( 'wp_ajax_nopriv_initialize_account', [ $this, 'initialize_account' ] );

		add_action( 'wp_ajax_create_membership', [ $this, 'create_membership' ] );
		add_action( 'wp_ajax_nopriv_create_membership', [ $this, 'create_membership' ] );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		if ( in_array( get_query_var( 'pagename' ), [ 'signup' ] ) ) {
			wp_enqueue_style( 'signup-main-style', plugins_url( 'css/style.css', __FILE__ ) );
			wp_enqueue_script( 'stipe-js', 'https://js.stripe.com/v3/' );
			wp_enqueue_script( 'signup-script', plugins_url( 'js/signup-process.js', __FILE__ ) );
			wp_localize_script(
				'signup-script', 'signup_vars', [
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				]
			);
		}
	}

	public function add_signup_rewrite_rule() {
		add_rewrite_rule( '^signup/?', 'index.php?pagename=signup', 'top' );
	}

	public function create_membership() {
		$session_id         = strval( $_POST['session_id'] );
		$billing_first_name = strval( $_POST['billing_first_name'] );
		$billing_last_name  = strval( $_POST['billing_last_name'] );
		$name_on_card       = strval( $_POST['name_on_card'] );
		$stripe_token       = strval( $_POST['stripe_token'] );

		$params = [
			'Session'        => [
				'SessionID' => $session_id,
			],
			'Membership'     => [
				'Term'     => 30,
				'TermType' => 'DAY',
			],
			'BillingAddress' => [
				'FirstName' => $billing_first_name,
				'LastName'  => $billing_last_name,
			],
			'PaymentMethod'  => [
				'NameOnAccount' => $name_on_card,
				'StripeToken'   => $stripe_token,
			],
		];

		$response = [
			'success' => false,
			'error'   => '',
		];

		$api_response = $this->api_helper->hit_api( $params, 'membership', 'POST' );

		if ( isset( $api_response['error'] ) ) {
			$response['error'] = $api_response['error'];
		} elseif ( isset( $api_response['Membership'] ) ) {
			$response = [
				'success' => true,
			];
		}

		wp_send_json( $response );
	}

	// Initialize user account
	public function initialize_account() {
		$user_email    = strval( $_POST['email_address'] );
		$user_password = strval( $_POST['password'] );

		$params = [
			'App'         => [
				'AppVersion' => 'UMCTV.Version.2.0',
			],
			'Credentials' => [
				'Password' => $user_password,
				'Username' => $user_email,
			],
			'Request'     => [
				'OperationalScenario' => 'CREATE_ACCOUNT',
			],
		];

		$response = [
			'success' => false,
			'error'   => '',
		];

		$api_response = $this->api_helper->hit_api( $params, 'initializeapp', 'POST' );

		if ( isset( $api_response['error'] ) ) {
			$response['error'] = $api_response['error'];
		} elseif ( isset( $api_response['Session'] ) ) {
			$response = [
				'success'    => true,
				'session_id' => $api_response['Session']['SessionID'],
				'stripe_id'  => $api_response['Customer']['StripeCustomerID'],
			];
		}

		wp_send_json( $response );
	}

	public function signup_template_redirect() {
		global $wp_query;
		$pagename = get_query_var( 'pagename' );

		if ( 'signup' === $pagename ) {
			// Prevent internal 404 on custome search page because of template_redirect hook.
			status_header( 200 );
			$wp_query->is_404  = false;
			$wp_query->is_page = true;

			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/main.php';
			$html = ob_get_clean();
			echo $html;
			exit();
		}
	}
}

$rlje_signup_page = new RLJE_signup_page();
