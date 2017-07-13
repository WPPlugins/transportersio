<?php

function transporters_quoteform_styles(){
	//wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
	//wp_enqueue_style( 'jquery-ui' ); 
	//wp_enqueue_style( 'quoteform-style', plugin_dir_url( __FILE__ ) . '../css/quoteform_styles_front.css', false );
	wp_enqueue_style( 'quoteform-font-awesome', plugin_dir_url( __FILE__ ) . '../plugins/css/font-awesome.min.css', false );
	wp_enqueue_style( 'transporters-style', plugin_dir_url( __FILE__ ) . '../css/quoteform_style.css?v=1.0', false );
}

function transporters_quoteform_scripts(){

    wp_enqueue_script('jquery');
	wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . '../plugins/js/bootstrap.min.js', false );
	wp_enqueue_script( 'jquery-blockui', plugin_dir_url( __FILE__ ) . '../plugins/js/jquery.blockUI.js', false );
	wp_enqueue_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . '../plugins/js/jquery.validate.min.js', false );
	wp_enqueue_script( 'jquery-validate-methods', plugin_dir_url( __FILE__ ) . '../plugins/js/additional-methods.min.js', false );
	wp_enqueue_script( 'bootstrap-datepicker', plugin_dir_url( __FILE__ ) . '../plugins/js/bootstrap-datepicker.min.js', false );
	wp_enqueue_script( 'bootstrap-timepicker', plugin_dir_url( __FILE__ ) . '../plugins/js/bootstrap-timepicker.min.js', false );
	wp_enqueue_script( 'bootstrap-touchspin', plugin_dir_url( __FILE__ ) . '../plugins/js/jquery.bootstrap-touchspin.min.js', false );
	wp_enqueue_script( 'moment', plugin_dir_url( __FILE__ ) . '../plugins/js/moment.min.js', false );
	wp_enqueue_script( 'moment-timezone', plugin_dir_url( __FILE__ ) . '../plugins/js/moment-timezone-with-data-2010-2020.min.js', false );
	
	wp_enqueue_script( 'quoteform-fullscreencontrol-script', plugin_dir_url( __FILE__ ) . '../js/FullScreenControl.js', false );
	//[TODO - why do we have a broken link here?? ]
	wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ) . '../plugins/js/my-ajax-script.js', array('jquery') );
    wp_localize_script( 'ajax-script', 'transporters_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    
}

function transporters_quoteform_admin_scripts(){
    wp_enqueue_script( 'quoteform-script', plugin_dir_url( __FILE__ ) . '../js/quoteform_scripts.js', false );
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('wp-color-picker');
}

function transporters_quoteform_admin_styles(){
    wp_enqueue_style( 'quoteform-style', plugin_dir_url( __FILE__ ) . '../css/quoteform_style.css?v=1.2', false ); 
	wp_enqueue_style('transportersio-admin-ui-css',
                'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/base/jquery-ui.css',
                false,
                '1.0',
                false);
	wp_enqueue_style( 'wp-color-picker' ); 			
}


function transporters_deregister_scripts(){
	//WCP OpenWeather - prevent double maps code
	//wp_deregister_script('rpw-gm');
	//wp_register_script('rpw-gm',  plugin_dir_url( __FILE__ ) . '../plugins/js/my-ajax-script.js', false );
	//wp_enqueue_script( 'rpw-gm');
	
	//Prevent double google maps code - universal
	global $wp_scripts; $gmapsenqueued = false;
    foreach ($wp_scripts->registered as $key => $script) {
        if (preg_match('#maps\.google(?:\w+)?\.com/maps/api/js#', $script->src)) {
            wp_deregister_script($key);
			wp_register_script($key,  plugin_dir_url( __FILE__ ) . '../plugins/js/my-ajax-script.js', false );
			wp_enqueue_script( $key);
        }
        elseif (preg_match('#jquery\-1\.7#', $script->src)) {//Bootstrap requires jquery 1.9
            wp_deregister_script($key);
			wp_register_script($key,   'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false );
			wp_enqueue_script( $key);
        }
    }
	wp_deregister_script('google-maps');
	if(get_option('transporters_map_api_key')){
		wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key='.get_option('transporters_map_api_key'), false );
	}else{
		wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?libraries=geometry,places', false );
	}
	wp_enqueue_script('google-maps');
}

?>
