<?php
class RLJE_Account_Page {

    private $account_action;

    public function __construct() {
		add_action( 'init', array( $this, 'add_browse_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'browse_template_redirect' ) );

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_action('rlje_user_settings_page', array($this, 'show_subsection'));
		//add_filter( 'body_class', array( $this, 'browse_body_class' ) );
    }
    
    public function enqueue_scripts() {
		if(in_array(get_query_var( 'pagename' ), ['account'])) {
            wp_enqueue_style( 'account-main-style', plugins_url( 'css/style.css', __FILE__ ));
		}
    }

    public function browse_body_class( $classes ) {
		
    }

    public function show_subsection() {
        switch($this->account_action) {
            case "status" :
                $partial_url = plugin_dir_path( __FILE__ ) . 'partials/status.php';
                break;

            case "editEmail" :
                $partial_url = plugin_dir_path( __FILE__ ) . 'partials/edit-email.php';
                break;

            case  "editPasword" :
                $partial_url = plugin_dir_path( __FILE__ ) . 'partials/edit-password.php';
                break;
            default:
                $partial_url = plugin_dir_path( __FILE__ ) . 'partials/status.php';
        }
        ob_start();
        require_once $partial_url;
		$html = ob_get_clean();

		echo $html;
    }
    
    public function add_browse_rewrite_rules() {
        add_rewrite_rule( '^account/?', 'index.php?pagename=account', 'top' );
        add_rewrite_rule( '^account/status/?', 'index.php?pagename=account&action=status', 'top' );
        add_rewrite_rule( '^account/editEmail/?', 'index.php?pagename=account&action=editEmail', 'top' );
        add_rewrite_rule( '^account/editPassword/?', 'index.php?pagename=account&action=editPassword', 'top' );
        add_rewrite_tag('%action%', '([^&]+)');
    }
    
    public function browse_template_redirect() {
        global $wp_query;
        $pagename = get_query_var( 'pagename' );
        $action = get_query_var('action');
        if(!empty($action)) {
            $this->account_action = $action;
        }
        
        if("account" === $pagename) {
            if ( !isset( $_COOKIE['ATVSessionCookie'] ) || !rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
				wp_redirect( home_url("signin"), 303 );
            }
            // Prevent internal 404 on custome search page because of template_redirect hook.
			$wp_query->is_404     = false;
			$wp_query->is_page    = true;
			// $wp_query->is_archive = true;
			ob_start();
			require_once plugin_dir_path( __FILE__ ) . 'templates/main.php';
			$html = ob_get_clean();
			echo $html;
			exit();
        }
    }
}

$rlje_account_page = new RLJE_Account_Page();