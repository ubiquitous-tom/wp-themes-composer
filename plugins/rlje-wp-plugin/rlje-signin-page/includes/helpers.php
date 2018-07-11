<?php
// TODO: These constants should be loaded from wp_options
define("API_ENDPOINT", "https://api.rlje.net/umc/initializeapp");
define("API_KEY", "LCmkd93u2kcLCkdmacmmc8dkDe");
define("API_APP_VERSION", "UMCTV.Version.2.0");
define("TIME_REFRESH_CACHE", 900); //seconds

function cacheUserProfile($user_profile) {
    $session_id = $user_profile['Session']['SessionID'];
    // Cache user status
    // TODO: Maybe remove since we cache the whole user profile?
    wp_cache_set('userStatus_'.md5($session_id), 'active', 'userStatus', TIME_REFRESH_CACHE);
    // Ask Transient to cache user profile
    set_transient('atv_userProfile_'.md5($session_id), $user_profile, TIME_REFRESH_CACHE);
}

function encodeHash($data, $api_key = API_KEY) {
    $hash = json_encode($data) . $api_key;
    return base64_encode($hash);
}

// Hits the API and normalizes the response
function hitApi($request) {
    $raw_response = wp_remote_post(API_ENDPOINT, [
        "headers" => [
            "x-atv-hash" => encodeHash($request),
            "Accept" => "application/json"
        ],
        "body" => json_encode($request)
    ]);

    if(is_wp_error($raw_response)) {
        error_log( "Error hiting API " . $raw_response->get_error_message() );
        return false;
    }
    return json_decode( wp_remote_retrieve_body( $raw_response ), true );

}

// this function authenticates the user
function loginUser($email_address, $password) {
    $request_body = [
        "App" => [
            "AppVersion" => API_APP_VERSION,
        ],
        "Credentials" => [
            "Username" => $email_address,
            "Password" => $password
        ],
        "Request" => [
            "OperationalScenario" => "SIGNIN"
        ]
    ];

    $response = hitApi($request_body);
    $success = false;
    if(isset($response['Membership'])) {
        $success = true;
        $session_id = $response['Session']['SessionID'];
        // Set ATVSessionCookie for the authenticated user
        setcookie("ATVSessionCookie", $session_id, time() + (2 * 7 * 24 * 60 * 60));
        // Ask Transients to cache user data
        cacheUserProfile($response);
    }

    return $success;
} 
?>