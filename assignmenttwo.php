<?php

/* 
		Plugin Name: Assignment 2 Plugin
		Plugin URI: phoenix/sheridanc.on.ca/~ccit3674
		Description: Creates CPT, Widget, Shortocode
		Author: Don Puerto-Musni
		Author URL: phoenix/sheridanc.on.ca/~ccit3674
		Version: 1.0
	*/

function create_post_type() {
    register_post_type('project', array(
        'label' => __('Projects'),
        'supports' => array( 'title', 'editor', 'thumbnail'),
        'show_ui' => true,
    ));
}
add_action('init', 'create_post_type');

//create widget
class dpWidget extends WP_Widget {
//Initialize widget
	public function __construct(){
		$widget_ops = array(
			'classname' => 'dp_widget',
			'description' => 'Displays Projects CPT'
			);
		parent::__construct('cpt_widget','Latest Projects', $widget_ops);
	}
//Front-End of Widget
	public function widget($args, $instance){
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Latest Projects' : $instance['title'], $instance, $this->id_base);

		echo $args['before_widget'];
		
		if($title){
			echo $args['before_title'] . $title . $args['after_title'];

		}
//Displays cpt posts in the widget front end
		$args = array( 'post_type' => 'project', 'posts_per_page' => 4 );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
		the_title();
		the_post_thumbnail();
		endwhile;
	}

		

		public function form($instance){
			$instance = wp_parse_args((array) $instance, array('title'=>''));
			$title = strip_tags($instance['title']);
			?>

			<?php //Back End Form ?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>

			<?php }

//save and submit user content
			public function update($new_instance,$old_instance){

				$instance = $old_instance;
				$new_instance = wp_parse_args((array) $new_instance, array('title' => '', 'count' => 0, 'dropdown' => ''));
				$instance['title'] = strip_tags($new_instance['title']);
				$instance['count'] = $new_instance['count'] ? 1 : 0;
				$instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;

				return $instance;


			}

		}
//List this widget in list of widgets
		add_action('widgets_init',function(){ register_widget('dpWidget'); });


		/*function add_google_font() {

			wp_enqueue_style( 'Homemade Apple', 'https://fonts.googleapis.com/css?family=Homemade+Apple', false ); 
		}

		add_action( 'wp_enqueue_scripts', 'add_google_font' );*/


/*Create Shortcode that allows to change the font family of the content enclosed. An application for this would be to use a cursive font as a signature at the end of a post
*/
		function cursfont($atts, $content = null) {
			extract(shortcode_atts(array(
				'fontfam' => "cursive",
				), $atts));
			return '<head>
			<style>
							#ass-two-area{
				font-family: ' . $fontfam . ';
			}
		</style>
	</head>

	<div id="ass-two-area">' . do_shortcode($content) . '</div>'
	;
}
add_shortcode('cursfont', 'cursfont');