<?php
class RLJE_api_helper {

	private $api_base;
	private $api_key         = 'LCmkd93u2kcLCkdmacmmc8dkDe';
	private $api_app_version = 'UMCTV.Version.2.0';

	public function __construct() {
		add_action( 'init', [ $this, 'setup_api_helper' ] );
	}

	public function setup_api_helper() {
		$this->api_base = constant( 'RLJE_BASE_URL' );
	}

	private function encode_hash( $data, $api_key = '' ) {
		$hash = json_encode( $data ) . $api_key;
		return base64_encode( $hash );
	}

	public function hit_api( $params, $method, $verb = 'GET' ) {
		$url = $this->api_base . '/' . $method;
		switch ( $verb ) {
			case 'GET':
				if ( empty( $params ) ) {
					$raw_response = wp_remote_get( $url );
				} else {
					$raw_response = wp_remote_get( $url . '?' . http_build_query( $params ) );
				}

				break;

			case 'POST':
				$raw_response = wp_remote_post(
					$url, [
						'headers' => [
							'x-atv-hash' => $this->encode_hash( $params, $this->api_key ),
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

			case 'DELETE':
				$raw_response = wp_remote_request(
					$url, [
						'method'  => 'DELETE',
						'headers' => [
							'x-atv-hash' => $this->encode_hash( $params, $this->api_key ),
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
}
