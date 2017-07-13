<?php
/*
 * Plugin Name: Transporters.io - Booking and business management solution
 * Version: 1.1.7
 * Plugin URI: http://transporters.io/
 * Description: Transporters quote form
 * Author: transporters.io
 * Author URI: https://transporters.io/
 * Text Domain: transportersio
 * Domain Path: /languages
 * Requires at least: 4.0.1
 * Tested up to: 4.7.3
 *
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $first_cookie;

function transporters_activate_quoteform() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quoteform-activator.php';
	Transporters_Quoteform_Activator::transporters_activate();
}


function transporters_deactivate_quoteform() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quoteform-deactivator.php';
	Transporters_Quoteform_Deactivator::transporters_deactivate();
}

function transporters_quoteform_register_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quoteform-widget.php';
	register_widget( 'Transporters_Quoteform_Widget' );
	transporters_check_referal_data();
}

register_activation_hook( __FILE__, 'transporters_activate_quoteform' );
register_deactivation_hook( __FILE__, 'transporters_deactivate_quoteform' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-quoteform-setting.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-quoteform-includes.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-quoteform-shortcode.php';

add_action( 'init', 'transporters_check_referal_data' );

add_action('admin_menu', 'transporters_quoteform_control_menu');
add_action( 'wp_enqueue_scripts','transporters_quoteform_styles' );
add_action( 'wp_enqueue_scripts','transporters_quoteform_scripts' );
add_action( 'wp_enqueue_scripts', 'transporters_deregister_scripts', 100 );
add_action( 'wp_print_scripts','transporters_quoteform_scripts' );
add_action( 'admin_enqueue_scripts', 'transporters_quoteform_admin_scripts' );
add_action( 'admin_enqueue_scripts', 'transporters_quoteform_admin_styles' );
add_action( 'admin_enqueue_scripts', 'transporters_deregister_scripts', 100 );
add_shortcode( 'transporters_quote_form', 'transporters_quoteform_shortcode' );

add_action( 'widgets_init', 'transporters_quoteform_register_widgets' );


add_action( 'wp_ajax_get_stage', 'get_stage_callback' );

add_action( 'wp_ajax_nopriv_get_stage', 'get_stage_callback' );

function transportersio_admin_notice() {
    if(!get_option( 'transporters_map_api_key')){
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e( 'You must setup your <a href="'.admin_url().'admin.php?page=transporters_quoteform">Google Maps API key</a> for Transporters.io to work', 'transportersio' ); ?></p>
    </div>
    <?php
    }
}
add_action( 'admin_notices', 'transportersio_admin_notice' );

function get_stage_callback() {
	global $wpdb;
		
	$script = get_option('transporters_custom_js_'.$_REQUEST['stage'].'_'.$_REQUEST['widget_id']);
	
	if(strpos(stripslashes($script),'<script') !== false){
		echo stripslashes($script);			
	}else if($script){
		echo '<script>'.stripslashes($script).'</script>';
	}
	
	wp_die();
}

function transporters_check_referal_data(){

	global $first_cookie;
	if(isset($_COOKIE['transporters_referer'])) return;
	if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],get_site_url()) === false){

		preg_match("/[\&\?](q|query|wd|search_word|qs|encquery|terms|rdata|text|szukaj|p|s|k|words)=([^&]*)/", $_SERVER['HTTP_REFERER'], $matches);
		
		$keyword = false;
		
		if(isset($matches) && is_array($matches) && isset($matches[2])){
			$keyword = $matches[2];
		}
		
		if(!$keyword){
			if(isset($_GET['keyword']) && $_GET['keyword'] !=''){//standard adwords/analytics format
				$keyword = $_GET['keyword'];
				if(isset($_GET['matchtype']) && $_GET['matchtype'] !=''){$keyword .= '~'.$_GET['matchtype']; }
				if(isset($_GET['device']) && $_GET['device'] !=''){$keyword .= '~'.$_GET['device']; }
			}elseif(isset($_GET['q'])){
				$keyword = $_GET['q'];	
			}
			
		}
		
		setcookie('transporters_referer',$_SERVER['HTTP_REFERER']."***".$keyword,time()+(3600*24*7));
		
		$first_cookie = $_SERVER['HTTP_REFERER']."***".$keyword;
		
	}	
}

function transporters_load_plugin_textdomain() {
    if (load_plugin_textdomain( 'transportersio', FALSE, dirname(plugin_basename(__FILE__)).'/languages/')) {
		//
	} 
}
add_action( 'plugins_loaded', 'transporters_load_plugin_textdomain' );

?>
