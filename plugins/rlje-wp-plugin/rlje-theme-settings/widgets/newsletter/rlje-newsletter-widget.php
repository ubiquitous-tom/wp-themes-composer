<?php

class RLJE_Newsletter_Widget extends WP_Widget {

	private $widget_area = array(
		'name'          => 'Signup Newsletter',
		'id'            => 'signup-newsletter',
		'description'   => 'Newsletter signup area for the footer',
		'class'         => '',
		'before_widget' => '<div id="signup-newsletter-widget" class="visible-lg col-lg-4">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>',
	);

	public function __construct() {
		parent::__construct( false, 'Signup Newsletter Widget' );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'wp_ajax_rlje_newsletter_signup', array( $this, 'newsletter_signup' ) );

		add_filter( 'rlje_widget_footer_areas', array( $this, 'add_widget' ) );
	}

	public function widgets_init() {
		register_widget( $this );
	}

	public function add_widget( $widgets ) {
		$widgets[] = $this->widget_area;

		return $widgets;
	}

	public function enqueue_scripts( $hook ) {
		$css_ver = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'css/style.css' ) );
		$js_ver  = date( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . 'js/script.js' ) );

		wp_enqueue_style( 'rlje-newsletter-widget', plugins_url( 'css/style.css', __FILE__ ), array( 'main_style_css' ), $css_ver );
		wp_enqueue_script( 'rlje-newsletter-widget', plugins_url( 'js/script.js', __FILE__ ), array( 'main-js' ), $js_ver, true );
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		ob_start();
		echo wp_kses_post( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		?>
		<input id="signup-newsletter-email" type="email" placeholder="Enter Your Email Address" value="">
		<input id="signup-newsletter-button" type="submit" value="submit">
		<div id="signup-newsletter-message"></div>
		<?php
		echo wp_kses_post( $args['after_widget'] );
		$html = ob_get_clean();

		echo $html;
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

	public function form( $instance ) {
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : 'Sign Up For Our Newsletter';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Title:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	public function newsletter_signup() {
		$email = sanitize_email( stripslashes( $_POST['email'] ) );
		$data  = array(
			'type'    => 'alert-error',
			'message' => 'Invalid email address.',
		);

		if ( is_email( $email ) ) {
			$is_subscribed = rljeApiWP_signupNewsletter( $email );
			if ( $is_subscribed ) {
				$data['type']    = 'alert-success';
				$data['message'] = 'Thank you for subscribing!';
			} else {
				$data['type']    = 'alert-error';
				$data['message'] = 'There was a problem with your submission, please try again.';
			}
		}

		wp_send_json( $data );
	}
}

$rlje_newsletter_widget = new RLJE_Newsletter_Widget();
