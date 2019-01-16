<?php

class RLJE_Theme_Menu_Links_Settings {

	private $theme_menu_links;

	public function __construct() {
		add_action( 'admin_init', array( $this, 'display_options' ) );
	}

	public function display_options() {
		register_setting( 'rlje_theme_section', 'rlje_theme_menu_links_settings' );

		add_settings_section( 'rlje_theme_menu_links_section', 'Menu Links Options', array( $this, 'display_rlje_menu_links_options_content' ), 'rlje-theme-settings' );
		add_settings_field( 'rlje_logged_in_links', 'Logged In Links', array( $this, 'display_logged_in_links' ), 'rlje-theme-settings', 'rlje_theme_menu_links_section' );
	}

	public function display_rlje_menu_links_options_content() {
		echo 'Menu links for logged in users';
		// delete_option( 'rlje_theme_menu_links_settings' );
		$this->theme_menu_links = get_option( 'rlje_theme_menu_links_settings' );
		var_dump( $this->theme_menu_links );
	}

	public function display_logged_in_links() {
		$theme_menu_links      = (! empty($this->theme_menu_links['logged_in_links'])) ? $this->theme_menu_links['logged_in_links'] : array();
		$recently_watched_text = (! empty($theme_menu_links['recently_watched_text'])) ? $theme_menu_links['recently_watched_text'] : 'Recently Watched';
		$recently_watched_link = (! empty($theme_menu_links['recently_watched_link'])) ? $theme_menu_links['recently_watched_link'] : home_url('/browse/recentlywatched/');
		$my_watchlist_text     = (! empty($theme_menu_links['my_watchlist_text'])) ? $theme_menu_links['my_watchlist_text'] : 'My Watchlist';
		$my_watchlist_link     = (! empty($theme_menu_links['my_watchlist_link'])) ? $theme_menu_links['my_watchlist_link'] : home_url('/browse/yourwatchlist/');
		$manage_account_text   = (! empty($theme_menu_links['manage_account_text'])) ? $theme_menu_links['manage_account_text'] : 'Manage Account';
		$manage_account_link   = (! empty($theme_menu_links['manage_account_link'])) ? $theme_menu_links['manage_account_link'] : 'https://account.acorn.tv/#accountStatus';
		$change_password_text  = (! empty($theme_menu_links['change_password_text'])) ? $theme_menu_links['change_password_text'] : 'Change Password';
		$change_password_link  = (! empty($theme_menu_links['change_password_link'])) ? $theme_menu_links['change_password_link'] : 'https://account.acorn.tv/#editPassword';
		$change_email_text     = (! empty($theme_menu_links['change_email_text'])) ? $theme_menu_links['change_email_text'] : 'Change Email';
		$change_email_link     = (! empty($theme_menu_links['change_email_link'])) ? $theme_menu_links['change_email_link'] : 'https://account.acorn.tv/#editEmail';
		$log_out_text          = (! empty($theme_menu_links['log_out_text'])) ? $theme_menu_links['log_out_text'] : 'Log Out';
		$log_out_link          = (! empty($theme_menu_links['log_out_link'])) ? $theme_menu_links['log_out_link'] : 'https://account.acorn.tv/#logout';
		$log_in_text           = (! empty($theme_menu_links['log_in_text'])) ? $theme_menu_links['log_in_text'] : 'Log In';
		$log_in_link           = (! empty($theme_menu_links['log_in_link'])) ? $theme_menu_links['log_in_link'] : 'https://signup.acorn.tv/signin.html';
		$sign_up_text          = (! empty($theme_menu_links['sign_up_text'])) ? $theme_menu_links['sign_up_text'] : 'Sign Up';
		$sign_up_link          = (! empty($theme_menu_links['sign_up_link'])) ? $theme_menu_links['sign_up_link'] : 'https://signup.acorn.tv/';
		$start_free_trial_text = (! empty($theme_menu_links['start_free_trial_text'])) ? $theme_menu_links['start_free_trial_text'] : 'Start Free Trial';
		$start_free_trial_link = (! empty($theme_menu_links['start_free_trial_link'])) ? $theme_menu_links['start_free_trial_link'] : 'https://signup.acorn.tv/';
		?>
		<p>
			<label for="recently-watched-link"><strong>Recently Watched URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][recently_watched_link]" class="widefat" id="recently-watched-link" value="<?php echo esc_attr( $recently_watched_link ); ?>" placeholder="<?php echo esc_url( home_url( '/browse/recentlywatched/' ) ); ?>">
		</p>
		<p class="description">Recently Watched URL. Default: (<?php echo esc_url( home_url( '/browse/recentlywatched/' ) ); ?>)</p>
		<hr>
		<p>
			<label for="recently-watched-link-text"><strong>Recently Watched Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][recently_watched_text]" class="regular-text" id="recently-watched-link-text" value="<?php echo esc_attr( $recently_watched_text ); ?>" placeholder="Recently Watched">
		</p>
		<p class="description">Recently Watched text. Default: (Recently Watched)</p>
		<hr>

		<p>
			<label for="my-watchlist-link"><strong>My Watchlist URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][my_watchlist_link]" class="widefat" id="my-watchlist-link" value="<?php echo esc_attr( $my_watchlist_link ); ?>" placeholder="<?php echo esc_url( home_url( '/browse/yourwatchlist/' ) ); ?>">
		</p>
		<p class="description">My Watchlist URL. Default: (<?php echo esc_url( home_url( '/browse/yourwatchlist/' ) ); ?>)</p>
		<hr>
		<p>
			<label for="my-watchlist-link-text"><strong>My Watchlist Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][my_watchlist_text]" class="regular-text" id="my-watchlist-link-text" value="<?php echo esc_attr( $my_watchlist_text ); ?>" placeholder="My Watchlist">
		</p>
		<p class="description">My Watchlist text. Default: (My Watchlist)</p>
		<hr>

		<p>
			<label for="manage-account-link"><strong>Manage Account URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][manage_account_link]" class="widefat" id="manage-account-link" value="<?php echo esc_attr( $manage_account_link ); ?>" placeholder="https://account.acorn.tv/#accountStatus">
		</p>
		<p class="description">Manage Account URL. Default: (https://account.acorn.tv/#accountStatus)</p>
		<hr>
		<p>
			<label for="manage-account-link-text"><strong>Manage Account Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][manage_account_text]" class="regular-text" id="manage-account-link-text" value="<?php echo esc_attr( $manage_account_text ); ?>" placeholder="Manage Account">
		</p>
		<p class="description">Manage Account text. Default: (Manage Account)</p>
		<hr>

		<p>
			<label for="change-password-link"><strong>Change Password URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][change_password_link]" class="widefat" id="change-password-link" value="<?php echo esc_attr( $change_password_link ); ?>" placeholder="https://account.acorn.tv/#editPassword">
		</p>
		<p class="description">Change Password URL. Default: (https://account.acorn.tv/#editPassword)</p>
		<hr>
		<p>
			<label for="change-password-link-text"><strong>Change Password Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][change_password_text]" class="regular-text" id="change-password-link-text" value="<?php echo esc_attr( $change_password_text ); ?>" placeholder="Change Password">
		</p>
		<p class="description">Change Password text. Default: (Change Password)</p>
		<hr>

		<p>
			<label for="change-email-link"><strong>Change Email URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][change_email_link]" class="widefat" id="change-email-link" value="<?php echo esc_attr( $change_email_link ); ?>" placeholder="https://account.acorn.tv/#editEmail">
		</p>
		<p class="description">Change Email URL. Default: (https://account.acorn.tv/#editEmail)</p>
		<hr>
		<p>
			<label for="change-email-link-text"><strong>Change Email Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][change_email_text]" class="regular-text" id="change-email-link-text" value="<?php echo esc_attr( $change_email_text ); ?>" placeholder="Change Email">
		</p>
		<p class="description">Change Email text. Default: (Change Email)</p>
		<hr>

		<p>
			<label for="log-out-link"><strong>Log Out URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][log_out_link]" class="widefat" id="log-out-link" value="<?php echo esc_attr( $log_out_link ); ?>" placeholder="https://account.acorn.tv/#logout">
		</p>
		<p class="description">Log Out URL. Default: (https://account.acorn.tv/#logout)</p>
		<hr>
		<p>
			<label for="log-out-link-text"><strong>Log Out Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][log_out_text]" class="regular-text" id="log-out-link-text" value="<?php echo esc_attr( $log_out_text ); ?>" placeholder="Log Out">
		</p>
		<p class="description">Log Out text. Default: (Log Out)</p>
		<hr>

		<p>
			<label for="log-in-link"><strong>Log In URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][log_in_link]" class="widefat" id="log-in-link" value="<?php echo esc_attr( $log_in_link ); ?>" placeholder="https://signup.acorn.tv/signin.html">
		</p>
		<p class="description">Log In URL. Default: (https://signup.acorn.tv/signin.html)</p>
		<hr>
		<p>
			<label for="log-in-link-text"><strong>Log In Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][log_in_text]" class="regular-text" id="log-in-link-text" value="<?php echo esc_attr( $log_in_text ); ?>" placeholder="Log In">
		</p>
		<p class="description">Log In text. Default: (Log In)</p>
		<hr>

		<p>
			<label for="sign-up-link"><strong>Sign Up URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][sign_up_link]" class="widefat" id="sign-up-link" value="<?php echo esc_attr( $sign_up_link ); ?>" placeholder="https://signup.acorn.tv/">
		</p>
		<p class="description">Sign Up URL. Default: (https://signup.acorn.tv/)</p>
		<hr>
		<p>
			<label for="sign-up-link-text"><strong>Sign Up Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][sign_up_text]" class="regular-text" id="sign-up-link-text" value="<?php echo esc_attr( $sign_up_text ); ?>" placeholder="Sign Up">
		</p>
		<p class="description">Sign Up text. Default: (Sign Up)</p>
		<hr>

		<p>
			<label for="start-free-trial-link"><strong>Start Free Trial URL:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][start_free_trial_link]" class="widefat" id="start-free-trial-link" value="<?php echo esc_attr( $start_free_trial_link ); ?>" placeholder="https://signup.acorn.tv/">
		</p>
		<p class="description">Start Free Trial URL. Default: (https://signup.acorn.tv/)</p>
		<hr>
		<p>
			<label for="start-free-trial-link-text"><strong>Start Free Trial Text:</strong></label><br>
			<input type="text" name="rlje_theme_menu_links_settings[logged_in_links][start_free_trial_text]" class="regular-text" id="start-free-trial-link-text" value="<?php echo esc_attr( $start_free_trial_text ); ?>" placeholder="Start Free Trial">
		</p>
		<p class="description">Start Free Trial text. Default: (Start Free Trial)</p>
		<hr>
		<?php
	}
}

$rlje_theme_menu_links_settings = new RLJE_Theme_Menu_Links_Settings();
