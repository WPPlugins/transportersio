<?php

class Transporters_Quoteform_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'quoteform_widget', // Base ID
			__( 'Transporters Quote Form', 'transportersio' ), // Name
			array( 'description' => __( 'Transporters Quote Form', 'transportersio' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		
		global $first_cookie;

		echo $args['before_widget'];
		
		$widget_id = $instance['quoteform_id'];
		
		$widget_type = '_w_'.$widget_id;
		
		$fixed = false;
		
		if(get_option('ts_fixed_route_'.$widget_id) == 1) $fixed = true;
				
		$html = '';
		
		include plugin_dir_path( __FILE__ ) . 'quoteform_scripts_front.php';
		
		$keyword = '';
		$referer = '';
		$ip = '';
		
		
		if(isset($_COOKIE['transporters_referer'])){
			$referer_array = explode('***',$_COOKIE['transporters_referer']);
			if(is_array($referer_array)){
			if(isset($referer_array[1])) $keyword = $referer_array[1];
			if(isset($referer_array[0])) $referer = $referer_array[0];
			}
		}else if(isset($first_cookie)){
			$referer_array = explode('***',$first_cookie);
			if(is_array($referer_array)){
			if(isset($referer_array[1])) $keyword = $referer_array[1];
			if(isset($referer_array[0])) $referer = $referer_array[0];
			}
		}
		
		if($ip =='' && isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}elseif($ip =='' && isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif($ip =='' && isset($_SERVER['REMOTE_ADDR'])){
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$html .= '<script>
					jQuery( document ).ready(function() {
						getQuoteForm'.$widget_type.'("'.get_option('transporters_url_site_'.$widget_id).'","'.$widget_type.'");
					});
				</script>';
		$html .= '<style>'.stripslashes(get_option('transporters_custom_css_'.$widget_id)).'</style>';
		$html .= '<script>'.stripslashes(get_option('transporters_custom_js_'.$widget_id)).'</script>';
		
		$html .= '<style>
					#panel-quote-form'.$widget_type.'{
						'.(get_option('ts_custom_background_'.$widget_id) ? ((get_option('ts_custom_background_'.$widget_id) == 'none') ? 'background-color:transparent;' : 'background-color:'.get_option('ts_custom_background_'.$widget_id).';') : 'background-color:#ffffff;').'
					}
					
					#panel-quote-form'.$widget_type.',#panel-quote-form'.$widget_type.' h4,#panel-quote-form'.$widget_type.' small{
						'.(get_option('ts_custom_text_color_'.$widget_id) ? 'color:'.get_option('ts_custom_text_color_'.$widget_id).' !important;' : 'color:#003ffb !important;').'
					}
					
					#panel-quote-form'.$widget_type.' .panel-title{
						'.(get_option('ts_custom_title_color_'.$widget_id) ? 'color:'.get_option('ts_custom_title_color_'.$widget_id).';' : 'color:#ffffff;').'
					}
					
					#panel-quote-form'.$widget_type.' .btn{
						'.(get_option('ts_custom_title_color_'.$widget_id) ? 'color:'.get_option('ts_custom_title_color_'.$widget_id).';' : 'color:#ffffff;').'
						'.(get_option('ts_custom_border_color_'.$widget_id) ? ((get_option('ts_custom_border_color_'.$widget_id) == 'none') ? 'border-color:transparent;' : 'border-color:'.get_option('ts_custom_border_color_'.$widget_id).';') : 'border-color:#e3bc27;').'
						'.(get_option('ts_custom_button_color_'.$widget_id) ? 'background-color:'.get_option('ts_custom_button_color_'.$widget_id).';' : 'background-color:#e3bc27;').'
					}
					
					#panel-quote-form'.$widget_type.' .panel-heading{
						'.(get_option('ts_custom_button_color_'.$widget_id) ? 'background-color:'.get_option('ts_custom_button_color_'.$widget_id).';' : 'background-color:#e3bc27;').'
						'.(get_option('ts_custom_border_color_'.$widget_id) ? ((get_option('ts_custom_border_color_'.$widget_id) == 'none') ? 'border-bottom-color:transparent;' : 'border-bottom-color:'.get_option('ts_custom_border_color_'.$widget_id).';') : 'border-bottom-color:#e3bc27;').'
					}
					
					#panel-quote-form'.$widget_type.'{
						'.(get_option('ts_custom_border_color_'.$widget_id) ? ((get_option('ts_custom_border_color_'.$widget_id) == 'none') ? 'border-color:transparent;' : 'border-color:'.get_option('ts_custom_border_color_'.$widget_id).';') : 'border-color:#e3bc27;').'
					}
					
				</style>';
		
		$html .= '<div id="panel-quote-form'.$widget_type.'" class="transportersio-quote panel panel-primary" style="display: none;">
                <div class="panel-heading">
                    <h3 class="panel-title">'.__('Get a Quote','transportersio').'</h3>
                </div>
                <div id="panel-get-a-quote'.$widget_type.'" class="panel-body" style="display: block;">
                    <div id="alert-get-a-quote'.$widget_type.'"></div>
                    <form action="javascript:;" method="post" id="form-get-a-quote'.$widget_type.'">
                        <div class="form-group">
                            <label for="start-location">'.__('Start location','transportersio').'</label>';
		if($fixed == true){					
			$html .= '<select name="start_location" id="select-start-location'.$widget_type.'" class="form-control">
                                    <option data-endId="0" value="0">'.__('Select a location','transportersio').'</option>
                                </select>';
		}else{
			$html .= '<input tabindex="1" type="text" name="start_location" id="start-location'.$widget_type.'"
                                   class="form-control" value="" placeholder="'.__('Enter a location','transportersio').'"
                                   required>';
		}
                            
			$html .= '<input type="hidden" name="start_location_latitude">
                            <input type="hidden" name="start_location_longitude">	   
                        </div>
                        <div class="form-group">
                            <label for="end-location">'.__('Destination','transportersio').'</label>';
		if($fixed == true){
				$html .= '<select name="end_location" id="select-end-location'.$widget_type.'" class="form-control">
                                    <option value="0">'.__('Select a location','transportersio').'</option>
                                </select>';
		}else{
				$html .= '<input tabindex="2" type="text" name="end_location" id="end-location'.$widget_type.'"
                                   class="form-control" value="" placeholder="'.__('Enter a location','transportersio').'"
                                   required>';
		}
                            
				$html .= '<input type="hidden" name="end_location_latitude">
                            <input type="hidden" name="end_location_longitude">	   
                        </div>
                        <div class="form-group last">
                            <label>'.__('Pickup date & time','transportersio').'</label>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input tabindex="3" type="text" name="start_date" id="start-date'.$widget_type.'"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               data-date-start-date="+0d" onClick="jQuery(\'#start-date'.$widget_type.'\').blur();" required>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input tabindex="4" type="text" name="start_time" id="start-time'.$widget_type.'"
                                               class="form-control" onClick="jQuery(\'#start-time'.$widget_type.'\').blur();" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button tabindex="5" type="submit" id="btn-get-a-quote'.$widget_type.'"
                                class="btn btn-sm btn-block btn-primary" disabled>
                            '.__('Get Quote','transportersio').'
                        </button>
                    </form>
                </div>
                <div id="panel-quotation-request'.$widget_type.'" class="panel-body" style="display: none;">
                    <div id="alert-quotation-request'.$widget_type.'"></div>
                    <form action="javascript:;" method="post" id="form-quotation-request'.$widget_type.'">
                        <input type="hidden" name="start_map_location_latitude" id="start-map-location-latitude'.$widget_type.'">
                        <input type="hidden" name="start_map_location_longitude" id="start-map-location-longitude'.$widget_type.'">
                        <input type="hidden" name="end_map_location_latitude" id="end-map-location-latitude'.$widget_type.'">
                        <input type="hidden" name="end_map_location_longitude" id="end-map-location-longitude'.$widget_type.'">
						<input type="hidden" name="web_page" id="web_page'.$widget_type.'" value="'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" >
						<input type="hidden" name="web_referrer" id="web_referrer'.$widget_type.'" value="'.$referer.'">
						<input type="hidden" name="web_keyword" id="web_keyword'.$widget_type.'" value="'.$keyword.'">
						<input type="hidden" name="web_ip_address" id="web_ip_address'.$widget_type.'" value="'.$ip.'" >
						<input type="hidden" name="profile_id" id="profile_id'.$widget_type.'" value="'.(get_option('transporters_profile_id_'.$widget_id) ? get_option('transporters_profile_id_'.$widget_id) : 0).'">
                        <div class="form-group">
                            <label for="start-map-location">'.__('Confirm start location','transportersio').'</label>';
		if($fixed == true){
				$html .= '<div class="input-group" style="display: block;">
                                <select name="start_map_location" id="select-start-map-location'.$widget_type.'" class="form-control">
                                    <option data-endId="0" value="0">'.__('Select a location','transportersio').'</option>
                                </select>
                            </div>';
		}else{
			$html .= '<div class="input-group input-group-sm">
                                <input type="text" name="start_map_location" id="start-map-location'.$widget_type.'"
                                       class="form-control">
                                <div class="input-group-btn">
                                    <button type="button" id="btn-start-map-location'.$widget_type.'" class="btn btn-primary">
                                        <i class="fa fa-map-marker"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="start-mapdiv-location'.$widget_type.'" class="mapdiv" style="width: 99.8%; height: 200px;">
                                <img src="https://placehold.it/300x200" alt="300x200"
                                     style="width: 100%; height: 200px;">
                            </div>';
		}
                            
             $html .= '</div>
                        <div class="form-group">
                            <label for="end-map-location">'.__('Confirm destination','transportersio').'</label>';
							
		if($fixed == true){
				$html .= '<div class="input-group" style="display: block;">
                                <select name="end_map_location" id="select-end-map-location'.$widget_type.'" class="form-control">
                                    <option value="0">'.__('Select a location','transportersio').'</option>
                                </select>
                            </div>';
		}else{
				$html .= '<div class="input-group input-group-sm">
                                <input type="text" name="end_map_location" id="end-map-location'.$widget_type.'"
                                       class="form-control">

                                <div class="input-group-btn">
                                    <button type="button" id="btn-end-map-location'.$widget_type.'" class="btn btn-primary">
                                        <i class="fa fa-map-marker"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="end-mapdiv-location'.$widget_type.'" class="mapdiv" style="width: 99.8%; height: 200px;">
                                <img src="https://placehold.it/300x200" alt="300x200"
                                     style="width: 100%; height: 200px;">
                            </div>';
		}

                 $html .=  '</div>
                        <div class="form-group last">
                            <label>'.__('Pickup date & time','transportersio').'</label>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group last">
                                        <input tabindex="3" type="text" name="pickup_date" id="pickup-date'.$widget_type.'"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               data-date-start-date="+0d" required>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group last">
                                        <input tabindex="4" type="text" name="pickup_time" id="pickup-time'.$widget_type.'"
                                               class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group last">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="return_journey_needed"
                                                       id="return-journey-needed'.$widget_type.'" value="1">
                                                <span>'.__('Return journey needed?','transportersio').'</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="form-group-return'.$widget_type.'" class="form-group last" style="display: none;">
                            <label for="return-date-time">'.__('Return date & time','transportersio').'</label>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input tabindex="3" type="text" name="return_date" id="return-date'.$widget_type.'"
                                               class="form-control" data-date-format="dd-mm-yyyy"
                                               data-date-start-date="+0d">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input tabindex="4" type="text" name="return_time" id="return-time'.$widget_type.'"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="form-group-input'.$widget_type.'" class="form-group last"></div>
                        <div id="form-group-vehicleType'.$widget_type.'" class="form-group">
                            <label for="vehicleType">
                                '.__('Vehicle Type','transportersio').'
                                <span style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #959595; font-weight: 700;">
                                    <small style="font-size: 75%;"></small>
                                </span>
                            </label>
                            <select name="vehicleType" id="vehicleType'.$widget_type.'" class="form-control" required></select>
                        </div>
						<div id="form-group-journeyType'.$widget_type.'" class="form-group" style="display: none;">
							<label for="journeyType">'.__('Journey Type','transportersio').'</label>
							<select name="journeyType" id="journeyType'.$widget_type.'" class="form-control"></select>
						</div>
                        <div id="booking_details_box'.$widget_type.'" class="form-group" style="display: none;">
                            <label id="booking_details_subject'.$widget_type.'" for="booking_details">Booking details</label>
                            <textarea name="booking_details" id="booking_details'.$widget_type.'" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="contact-name">'.__('Contact name','transportersio').'</label>
                            <input type="text" name="contact_name" id="contact-name'.$widget_type.'" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email">'.__('Contact email','transportersio').'</label>
                            <input type="email" name="contact_email" id="contact-email'.$widget_type.'" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile-number">'.__('Mobile number','transportersio').'</label>
                            <input type="tel" name="mobile_number" id="mobile-number'.$widget_type.'" class="form-control" required>
                        </div>';
						
                if(get_option('ts_show_notes_'.$widget_id) == 1){
				$html .= '<div class="form-group">
                            <label for="note_message">'.__('Notes','transportersio').'</label>
							<textarea name="note_message" id="note_message'.$widget_type.'" style="min-height:70px;" class="form-control"></textarea>
                        </div>';
			}
				
				$html .= '<div class="row">
                            <div class="col-xs-4">
                                <button type="button" id="btn-back-get-quote'.$widget_type.'" class="btn btn-sm btn-block btn-primary">
                                    <i class="fa fa-reply"></i>
                                    <span class="hidden-xs hidden-sm">'.__('Back','transportersio').'</span>
                                </button>
                            </div>
                            <div class="col-xs-8">
                                <button type="submit" id="btn-quotation-request'.$widget_type.'"
                                        class="btn btn-sm btn-block btn-primary" disabled>
                                    '.__('Get Quote','transportersio').'
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="panel-thank-you'.$widget_type.'" class="panel-body" style="display: none;">
                    <div id="alert-thank-you'.$widget_type.'"></div>
                    <form action="javascript:;" method="post" id="form-thank-you'.$widget_type.'">
                    </form>

                    <div class="row">
                        <div id="html_quote_form_confirmation'.$widget_type.'" class="col-md-12">
                            <p>'.__('Thank you for requesting a quote','transportersio').'.</p>
                        </div>
                    </div>
                </div>
            </div>';
			
		if(get_option('transporters_url_site_'.$widget_id) == ''){
			$html = '<p>Please input URL Site.</p>';
		}	

		echo do_shortcode($html);

		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$quoteform_id = ! empty( $instance['quoteform_id'] ) ? $instance['quoteform_id'] : 1;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'quoteform_id' ); ?>"><?php _e( 'Select Quote Form :' ); ?></label> 
        <select class="widefat" name="<?php echo $this->get_field_name( 'quoteform_id' ); ?>" id="<?php echo $this->get_field_id( 'quoteform_id' ); ?>" >
        	<option value="1" <?php if($quoteform_id == 1) echo 'selected="selected"'; ?> >Quote Form 1</option>
            <option value="2" <?php if($quoteform_id == 2) echo 'selected="selected"'; ?> >Quote Form 2</option>
            <option value="3" <?php if($quoteform_id == 3) echo 'selected="selected"'; ?> >Quote Form 3</option>
        </select>
		</p>
        <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['quoteform_id'] = ( ! empty( $new_instance['quoteform_id'] ) ) ? strip_tags( $new_instance['quoteform_id'] ) : '';
		return $instance;
	}

}
