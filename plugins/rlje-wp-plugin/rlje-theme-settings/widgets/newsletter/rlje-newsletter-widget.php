<?php

class RLJE_Newsletter_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct( false, 'Signup Newsletter Widget' );

		add_action( 'widgets_init', array( $this, 'register_newsletter_widget' ) );
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		ob_start();
		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<input id="signupEmail" onfocus="clearPlaceholder(this)" onblur="emailPlaceholder(this)" type="text" value="Enter Your Email Address" style="border: medium none;border-radius: 0;color: #666;height: 40px;padding: 5px;width: 80%;font-size:13.5px;min-width:225px">
		<button onclick="signupNewsletter(this)" style="background: #222 none repeat scroll 0 0;display: inline;height: 42px;margin-left: 2px;padding: 9px 10px;width: 45px;">
			<img src="https://api.rlje.net/acorn/artwork/size/right-arrow?t=Icons" width="25" />
		</button>
		<div id="formMessage"></div>
		<?php
		echo $args['after_widget'];
		$html = ob_get_clean();

		echo $html;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
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
}

$rlje_newsletter_widget = new RLJE_Newsletter_Widget();