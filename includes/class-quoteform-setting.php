<?php

function transporters_quoteform_control_menu() {
	add_menu_page( 'Transporters.io', 'Transporters.io', 'manage_options', 'transporters_quoteform', 'transporters_dashboard', plugins_url( '../images/transporters-icon.png', __FILE__ ), 6 );
}


function transporters_dashboard(){
	
	$quote_number = 3;
	
	for($i=1;$i<=$quote_number;$i++){
		if(isset($_POST['url_site_'.$i])){
			if(substr($_POST['url_site_'.$i], -1) != '/') update_option( 'transporters_url_site_'.$i, $_POST['url_site_'.$i].'/' );
			else update_option( 'transporters_url_site_'.$i, $_POST['url_site_'.$i] );
		}
		
		if(isset($_POST['custom_css_'.$i])){
			update_option( 'transporters_custom_css_'.$i, $_POST['custom_css_'.$i] );
		}
		
		if(isset($_POST['custom_js_'.$i])){
			update_option( 'transporters_custom_js_'.$i, $_POST['custom_js_'.$i] );
		}
		
		if(isset($_POST['custom_js_after1_'.$i])){
			update_option( 'transporters_custom_js_after1_'.$i, $_POST['custom_js_after1_'.$i] );
		}
		
		if(isset($_POST['custom_js_after2_'.$i])){
			update_option( 'transporters_custom_js_after2_'.$i, $_POST['custom_js_after2_'.$i] );
		}
		
		if(isset($_POST['custom_js_back_'.$i])){
			update_option( 'transporters_custom_js_back_'.$i, $_POST['custom_js_back_'.$i] );
		}
		
		if(isset($_POST['ts_custom_background_'.$i])){
			if(isset($_POST['ts_onoff_switch_background_'.$i]) && $_POST['ts_onoff_switch_background_'.$i] == 1){
				update_option( 'ts_custom_background_'.$i, $_POST['ts_custom_background_'.$i] );
			}else{
				update_option( 'ts_custom_background_'.$i, 'none');
			}
		}
		
		if(isset($_POST['ts_custom_text_color_'.$i])){
			update_option( 'ts_custom_text_color_'.$i, $_POST['ts_custom_text_color_'.$i] );
		}
		
		if(isset($_POST['ts_custom_title_color_'.$i])){
			update_option( 'ts_custom_title_color_'.$i, $_POST['ts_custom_title_color_'.$i] );
		}
		
		if(isset($_POST['ts_custom_border_color_'.$i])){
			if(isset($_POST['ts_onoff_switch_border_color_'.$i]) && $_POST['ts_onoff_switch_border_color_'.$i] == 1){
				update_option( 'ts_custom_border_color_'.$i, $_POST['ts_custom_border_color_'.$i] );
			}else{
				update_option( 'ts_custom_border_color_'.$i, 'none');
			}
		}
		
		if(isset($_POST['ts_custom_button_color_'.$i])){
			update_option( 'ts_custom_button_color_'.$i, $_POST['ts_custom_button_color_'.$i] );
		}
		
		if(isset($_POST['update_form_'.$i])){
			if(isset($_POST['return_journey_'.$i])){
				update_option( 'transporters_return_journey_'.$i, $_POST['return_journey_'.$i]);
			}else{
				update_option( 'transporters_return_journey_'.$i, 0);
			}
			
			if(isset($_POST['ts_fixed_route_'.$i])){
				update_option( 'ts_fixed_route_'.$i, $_POST['ts_fixed_route_'.$i]);
			}else{
				update_option( 'ts_fixed_route_'.$i, 0);
			}
			
			if(isset($_POST['ts_show_notes_'.$i])){
				update_option( 'ts_show_notes_'.$i, $_POST['ts_show_notes_'.$i]);
			}else{
				update_option( 'ts_show_notes_'.$i, 0);
			}
			
			if(isset($_POST['profile_id_'.$i])){
				update_option( 'transporters_profile_id_'.$i, $_POST['profile_id_'.$i]);
			}else{
				update_option( 'transporters_profile_id_'.$i, 0);
			}
			
		}
	}
	
	if(isset($_POST['map_api_key'])){
		update_option( 'transporters_map_api_key', $_POST['map_api_key']);
	}
	
	
	echo '<div class="wrap">';
	echo '<h2>'.__('Dashboard','transportersio').'</h2>';
	echo '<img src="'.plugins_url( '../images/transporters-logo.png', __FILE__ ).'" style="position: absolute;right: 10px;top: 10px;" />';
	echo '<p>'.__('Welcome to the Transporters.io Wordpress Plugin','transportersio').'</p>';
	echo '<p>'.__('This plugin allows you to style and place up to 3 <a href="https://transporters.io/wordpress/">Transporters quote forms</a> on your website','transportersio').'</p>';
	echo '<p>'.__('Simply enter your details on the quote form pages and then insert as either a widget or shortcode.','transportersio').'</p>';
	echo '<p>'.__('For support please visit','transportersio').' <a href="https://helpdesk.transporters.io/">https://helpdesk.transporters.io</a></p>';
	echo '<div id="ts-tabs">
			  <ul>';
			  for($i=1;$i<=$quote_number;$i++){
				  echo '<li><a href="#ts-quote-form-'.$i.'">Quote Form '.$i.'</a></li>';
			  }
	echo '<li><a href="#ts-setting">Settings'.(get_option('transporters_map_api_key') ? '' : ' <span class="dashicons dashicons-warning" style="color:#c60600;"></span>' ).'</a></li>
			  </ul>';
			  
	for($i=1;$i<=$quote_number;$i++){
		echo '<div id="ts-quote-form-'.$i.'">
				'.transporters_quote_form_X($i).'
			  </div>';
	}

	echo '<div id="ts-setting">
			    <div class="wrap"><p>'.transporters_setting().'</p></div>
			  </div>
			</div>';
	echo '</div>';
	echo '<script>
			  jQuery( function() {
				jQuery( "#ts-tabs" ).tabs();
			  } );
			  
			  function switchBackground(value,id){
				  if(value === false){
					  jQuery("#ts_box_custom_background_"+id).hide();
					  jQuery("#ts-preview-"+id+" > .ts-container").css("background-color","transparent");
				  }else{
					  jQuery("#ts_box_custom_background_"+id).show();
					  jQuery("#ts-preview-"+id+" > .ts-container").css("background-color",jQuery("#ts_custom_background_"+id).val());
					  
				  }
			  }
			  
			  function switchBorder(value,id){
				  if(value === false){
					  jQuery("#ts_box_custom_border_color_"+id).hide();
					  jQuery("#ts-preview-"+id+" .preview-border-color").css("border","1px solid transparent");
					  jQuery("#ts-preview-"+id+" .preview-border-color-bottom").css("border-bottom","1px solid transparent");
				  }else{
					  jQuery("#ts_box_custom_border_color_"+id).show();
					  jQuery("#ts-preview-"+id+" .preview-border-color").css("border","1px solid "+jQuery("#ts_custom_border_color_"+id).val());
					  jQuery("#ts-preview-"+id+" .preview-border-color-bottom").css("border-bottom","1px solid "+jQuery("#ts_custom_border_color_"+id).val());
				  }
			  }
			  
			  </script>';	    
}


function transporters_quote_form_X($id){
	
	$html = '';
	
	$html .= '<style>
				#ts-preview-'.$id.'{
					float:left;
					width:35%;
				}
				
				#ts-preview-'.$id.' label{
					font-weight: 600;
    				line-height: 30px;
					color: #000;
				}
				
				#ts-preview-'.$id.' h1,#ts-preview-'.$id.' h2,#ts-preview-'.$id.' h3,#ts-preview-'.$id.' p{
					font-family: "Source Sans Pro",Calibri,Candara,Arial,sans-serif;
					font-weight: 300;
					line-height: 1.1;
				}
				
				#ts-preview-'.$id.' h1{
					font-size: 39px;
				}
				
				#ts-preview-'.$id.' h2{
					font-size: 32px;
					margin-bottom: 10.5px;
				}
				
				#ts-preview-'.$id.' p{
					margin: 0 0 10.5px;
					font-size: 15px;
				}
				
				#ts-preview-'.$id.' .ts-lead{
					font-size: 22.5px;
				}
				
				#ts-preview-'.$id.' .ts-container{
					border: 1px solid #e3e3e3;
    				padding: 0px 10px;
					border-radius: 4px;
				}
				
				#ts-preview-'.$id.' .ts-page-header{
					padding-bottom: 10px;
					margin: 0px 0 20px;
					border-bottom: 1px solid #e6e6e6;
				}
				
				#ts-preview-'.$id.' .ts-component{
					border: 1px solid #e3bc27;
				}
				
				#ts-preview-'.$id.' .ts-panel-heading{
					color: #7e4040;
					border-color: #e3bc27;
					background-color: #e3ac27;
					padding: 10px 15px;
    				border-bottom: 1px solid transparent;
				}
				
				#ts-preview-'.$id.' .ts-panel-title{
					margin-top: 0;
					margin-bottom: 0;
					font-size: 17px;
					color: inherit;
				}
				
				#ts-preview-'.$id.' #Panels-'.$id.'{
					color: inherit;
					font-size: 39px;
				}
				
				#ts-preview-'.$id.' .ts-panel-body{
					padding: 15px;
				}
				
				#ts-preview-'.$id.' .ts-btn-primary {
					color: #7e4040;
					border-color: #e3bc27;
					background-color: #e3ac27;
				}
				
				#ts-preview-'.$id.' .ts-btn {
					display: inline-block;
					font-weight: normal;
					text-align: center;
					vertical-align: middle;
					-ms-touch-action: manipulation;
					touch-action: manipulation;
					cursor: pointer;
					background-image: none;
					border: 1px solid transparent;
					white-space: nowrap;
					padding: 10px 18px;
					font-size: 15px;
					line-height: 1.42857143;
					border-radius: 0;
					-webkit-user-select: none;
					-moz-user-select: none;
					-ms-user-select: none;
					user-select: none;
					margin-bottom: 5px;
					text-decoration: none;
				}
				
				#ts-preview-'.$id.' .ts-btn-xs, #ts-preview-'.$id.' .ts-btn-group-xs>.ts-btn {
					padding: 1px 5px;
					font-size: 13px;
					line-height: 1.5;
					border-radius: 0;
				}
				
				#ts-preview-'.$id.' > .ts-container{
					background-color:'.((get_option('ts_custom_background_'.$id)) ? ((get_option('ts_custom_background_'.$id) == 'none') ? 'transparent' : get_option('ts_custom_background_'.$id)) : '#ffffff').';
				}
				
				#ts-preview-'.$id.'{
					color:'.((get_option('ts_custom_text_color_'.$id)) ? ((get_option('ts_custom_text_color_'.$id) == 'none') ? 'transparent' : get_option('ts_custom_text_color_'.$id)) : '#003ffb').';
				}
				
				#ts-preview-'.$id.' .preview-text-color{
					color:'.((get_option('ts_custom_text_color_'.$id)) ? ((get_option('ts_custom_text_color_'.$id) == 'none') ? 'transparent' : get_option('ts_custom_text_color_'.$id)) : '#003ffb').';
				}
				
				#ts-preview-'.$id.' .preview-title-color{
					color:'.((get_option('ts_custom_title_color_'.$id)) ? ((get_option('ts_custom_title_color_'.$id) == 'none') ? 'transparent' : get_option('ts_custom_title_color_'.$id)) : '#ffffff').';
				}
				
				#ts-preview-'.$id.' .preview-border-color{
					border:1px solid '.((get_option('ts_custom_border_color_'.$id)) ? ((get_option('ts_custom_border_color_'.$id) == 'none') ? 'transparent' : get_option('ts_custom_border_color_'.$id)) : '#e3bc27').';
				}
				
				#ts-preview-'.$id.' .preview-border-color-bottom{
					border-bottom:1px solid '.((get_option('ts_custom_border_color_'.$id)) ? ((get_option('ts_custom_border_color_'.$id) == 'none') ? 'transparent' : get_option('ts_custom_border_color_'.$id)) : '#e3bc27').';
				}
				
				#ts-preview-'.$id.' .preview-button-color{
					background-color:'.((get_option('ts_custom_button_color_'.$id)) ? ((get_option('ts_custom_button_color_'.$id) == 'none') ? 'transparent' : get_option('ts_custom_button_color_'.$id)) : '#e3ac27').';
				}
				</style>';
				
	$html .= '<script>
			  jQuery( function() {
				jQuery("#ts_custom_background_'.$id.'").wpColorPicker({
					change: function(event, ui){
							var element = event.target;
							var color = ui.color.toString();
							jQuery("#ts-preview-'.$id.' > .ts-container").css("background-color",color);
						}
					});
				
				jQuery("#ts_custom_text_color_'.$id.'").wpColorPicker({
					change: function(event, ui){
							var element = event.target;
							var color = ui.color.toString();
							jQuery("#ts-preview-'.$id.'").css("color",color);
							jQuery("#ts-preview-'.$id.' .preview-text-color").css("color",color);
						}
					});
					
					
				jQuery("#ts_custom_title_color_'.$id.'").wpColorPicker({
					change: function(event, ui){
							var element = event.target;
							var color = ui.color.toString();
							jQuery("#ts-preview-'.$id.' .preview-title-color").css("color",color);
						}
					});	
					
					
				jQuery("#ts_custom_border_color_'.$id.'").wpColorPicker({
					change: function(event, ui){
							var element = event.target;
							var color = ui.color.toString();
							jQuery("#ts-preview-'.$id.' .preview-border-color").css("border","1px solid "+color);
							jQuery("#ts-preview-'.$id.' .preview-border-color-bottom").css("border-bottom","1px solid "+color);
						}
					});		
					
				jQuery("#ts_custom_button_color_'.$id.'").wpColorPicker({
					change: function(event, ui){
							var element = event.target;
							var color = ui.color.toString();
							jQuery("#ts-preview-'.$id.' .preview-button-color").css("background-color",color);
						}
					});		
				
			  });
			  </script>';			

	$html .= '<div class="wrap">';
	$html .= '<form id="quoteform_setting" class="ts-form" method="post" action="'.admin_url( 'admin.php?page=transporters_quoteform').'#ts-quote-form-'.$id.'">';
	$html .= '<div class="ts-left">
				<p>'.__('Short code','transportersio').' : [transporters_quote_form id='.$id.']</p>
				<div class="ts-row">
					<div class="title-label">
						<label for="url_site_'.$id.'">'.__('Transporters System URL','transportersio').' <span class="description">('.__('required','transportersio').')</span></label>
					</div>
					<div class="ts-input-box">
						<input name="url_site_'.$id.'" type="text" id="url_site_'.$id.'" value="'.get_option('transporters_url_site_'.$id).'" placeholder="http://example.transporters.io/">
					</div>
				</div>
				
				<div class="ts-row">
					<div class="title-label">
						<label for="return_journey_'.$id.'">'.__('Default to return journey','transportersio').' </label>
					</div>
					<div class="ts-input-box">
						<input type="checkbox" name="return_journey_'.$id.'" id="return_journey_'.$id.'" style="width:auto;" value="1" '.(get_option('transporters_return_journey_'.$id) == 1 ? 'checked="checked"' : '').' />
					</div>
				</div>
				
				<div class="ts-row">
					<div class="title-label" title="'.__('Restrict start and destination locations by adding Fixed Locations or Fixed Routes inside your Transporters Settings','transportersio').'">
						<label for="ts_fixed_route_'.$id.'">'.__('Fixed locations','transportersio').' <span class="dashicons dashicons-editor-help"></span></label>
					</div>
					<div class="ts-input-box">
						<input type="checkbox" name="ts_fixed_route_'.$id.'" id="ts_fixed_route_'.$id.'" style="width:auto;" value="1" '.(get_option('ts_fixed_route_'.$id) == 1 ? 'checked="checked"' : '').' />
					</div>
				</div>
				
				<div class="ts-row">
					<div class="title-label">
						<label for="ts_show_notes_'.$id.'">'.__('Show Notes','transportersio').'</label>
					</div>
					<div class="ts-input-box">
						<input type="checkbox" name="ts_show_notes_'.$id.'" id="ts_show_notes_'.$id.'" style="width:auto;" value="1" '.(get_option('ts_show_notes_'.$id) == 1 ? 'checked="checked"' : '').' />
					</div>
				</div>
				
				
				<div class="ts-row">
					<div class="title-label">
						<label for="profile_id_'.$id.'" title="'.__('Multiple company profiles is a Transporters Pro feature. If you want to assign this quote form to a profile enter its ID number in the box below.','transportersio').'">'.__('Profile ID','transportersio').' <span class="dashicons dashicons-editor-help"></span></label>
					</div>
					<div class="ts-input-box">
						<input type="number" name="profile_id_'.$id.'" id="profile_id_'.$id.'" value="'.stripslashes(get_option('transporters_profile_id_'.$id)).'" >
					</div>
				</div>
				<p class="submit"><input type="submit" name="createuser" id="createusersub" class="button button-primary" value="'.__('Update Quote Form','transportersio').'"></p>
			</div>';
	$html .= '<div class="ts-right">
					<div class="ts-row">
						<div class="title-label title-color">
							<label for="ts_onoff_switch_background_'.$id.'">'.__('Background','transportersio').'</label>
						</div>
						<div class="ts-input-box">
							
							<div class="ts-switch-box">
								<div class="onoffswitch">
									<input type="checkbox" name="ts_onoff_switch_background_'.$id.'" onchange="switchBackground(this.checked,'.$id.');" value="1" class="onoffswitch-checkbox" id="ts_onoff_switch_background_'.$id.'" '.((get_option('ts_custom_background_'.$id)) ? ((get_option('ts_custom_background_'.$id) == 'none') ? '' : 'checked') : 'checked').' >
									<label class="onoffswitch-label" for="ts_onoff_switch_background_'.$id.'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
							</div>
							
							<div id="ts_box_custom_background_'.$id.'" class="ts-color-box" '.((get_option('ts_custom_background_'.$id)) ? ((get_option('ts_custom_background_'.$id) == 'none') ? 'style="display:none;"' : '') : '').'>
								<input type="color" id="ts_custom_background_'.$id.'" name="ts_custom_background_'.$id.'" class="color-field" data-preferred-format="hex" value="'.((get_option('ts_custom_background_'.$id)) ? ((get_option('ts_custom_background_'.$id) == 'none') ? '#ffffff' : get_option('ts_custom_background_'.$id)) : '#ffffff').'">
							</div>
						</div>
					</div>
					
					<div class="ts-row">
						<div class="title-label title-color">
							<label for="ts_custom_text_color_'.$id.'">'.__('Text colour','transportersio').'</label>
						</div>
						<div class="ts-input-box">
								<input type="color" id="ts_custom_text_color_'.$id.'" name="ts_custom_text_color_'.$id.'" class="color-field" data-preferred-format="hex" value="'.((get_option('ts_custom_text_color_'.$id)) ? get_option('ts_custom_text_color_'.$id) : '#003ffb').'">							
						</div>
					</div>
					
					<div class="ts-row">
						<div class="title-label title-color">
							<label for="ts_custom_title_color_'.$id.'">'.__('Titles colour','transportersio').'</label>
						</div>
						<div class="ts-input-box">						
							<input type="color" id="ts_custom_title_color_'.$id.'" name="ts_custom_title_color_'.$id.'" class="color-field" data-preferred-format="hex" value="'.((get_option('ts_custom_title_color_'.$id)) ? get_option('ts_custom_title_color_'.$id) : '#ffffff').'">
						</div>
					</div>
					
					<div class="ts-row">
						<div class="title-label title-color">
							<label for="ts_onoff_switch_border_color_'.$id.'">'.__('Borders colour','transportersio').'</label>
						</div>
						<div class="ts-input-box">
						
						<div class="ts-switch-box">
								<div class="onoffswitch">
									<input type="checkbox" name="ts_onoff_switch_border_color_'.$id.'" onchange="switchBorder(this.checked,'.$id.');" value="1" class="onoffswitch-checkbox" id="ts_onoff_switch_border_color_'.$id.'" '.((get_option('ts_custom_border_color_'.$id)) ? ((get_option('ts_custom_border_color_'.$id) == 'none') ? '' : 'checked') : 'checked').'>
									<label class="onoffswitch-label" for="ts_onoff_switch_border_color_'.$id.'">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
							</div>
							
							<div id="ts_box_custom_border_color_'.$id.'" class="ts-color-box" '.((get_option('ts_custom_border_color_'.$id)) ? ((get_option('ts_custom_border_color_'.$id) == 'none') ? 'style="display:none;"' : '') : '').'>
								<input type="color" id="ts_custom_border_color_'.$id.'" name="ts_custom_border_color_'.$id.'" class="color-field" data-preferred-format="hex" value="'.((get_option('ts_custom_border_color_'.$id)) ? ((get_option('ts_custom_border_color_'.$id) == 'none') ? '#e3bc27' : get_option('ts_custom_border_color_'.$id)) : '#e3bc27').'">
							</div>
							
						</div>
					</div>
					
					<div class="ts-row">
						<div class="title-label title-color">
							<label for="ts_custom_button_color_'.$id.'">'.__('Buttons colour','transportersio').'</label>
						</div>
						<div class="ts-input-box">
							<input type="color" id="ts_custom_button_color_'.$id.'" name="ts_custom_button_color_'.$id.'" class="color-field" data-preferred-format="hex" value="'.((get_option('ts_custom_button_color_'.$id)) ? get_option('ts_custom_button_color_'.$id) : '#e3bc27').'">
						</div>
					</div>
					
					<div class="ts-row">
						<input type="button" name="filter_action" onClick="jQuery(\'.ts-advanced\').toggle();" class="button" value="Advanced">
					</div>
	
					<div class="ts-row ts-advanced">
						<div class="title-label">
							<label for="custom_js_after1_'.$id.'">'.__('Custom JS (after stage 1)','transportersio').'</label>
						</div>
						<div class="ts-input-box">
							<textarea name="custom_js_after1_'.$id.'" type="text" id="custom_js_after1_'.$id.'" >'.stripslashes(get_option('transporters_custom_js_after1_'.$id)).'</textarea>
						</div>
					</div>
					
					<div class="ts-row ts-advanced">
						<div class="title-label">
							<label for="custom_js_after2_'.$id.'">'.__('Custom JS (after stage 2)','transportersio').'</label>
						</div>
						<div class="ts-input-box">
							<textarea name="custom_js_after2_'.$id.'" type="text" id="custom_js_after2_'.$id.'" >'.stripslashes(get_option('transporters_custom_js_after2_'.$id)).'</textarea>
						</div>
					</div>
					
					<div class="ts-row ts-advanced">
						<div class="title-label">
							<label for="custom_js_back_'.$id.'">'.__('Custom JS (after back)','transportersio').'</label>
						</div>
						<div class="ts-input-box">
							<textarea name="custom_js_back_'.$id.'" type="text" id="custom_js_back_'.$id.'" >'.stripslashes(get_option('transporters_custom_js_back_'.$id)).'</textarea>
						</div>
					</div>
					
					
					<div class="ts-row ts-advanced">
						<div class="title-label">
							<label for="custom_css_'.$id.'">'.__('Custom CSS','transportersio').'</label>
						</div>
						<div class="ts-input-box">
							<textarea name="custom_css_'.$id.'" type="text" id="custom_css_'.$id.'" >'.stripslashes(get_option('transporters_custom_css_'.$id)).'</textarea>
						</div>
					</div>
					
					<div class="ts-row ts-advanced">
						<div class="title-label">
							<label for="custom_js_'.$id.'">'.__('Custom JS','transportersio').'</label>
						</div>
						<div class="ts-input-box">
							<textarea name="custom_js_'.$id.'" type="text" id="custom_js_'.$id.'" >'.stripslashes(get_option('transporters_custom_js_'.$id)).'</textarea>
						</div>
					</div>
					
				</div>';
	$html .= '<div id="ts-preview-'.$id.'">
					<label for="user_login">'.__('Preview','transportersio').'</label>
					<div class="ts-container">
						<div class="ts-docs-section">
							<div class="ts-page-header" style="margin-top: 0px;">
								<div class="row">
									<div class="ts-col-xs-12">
										<h1 id="Panels-'.$id.'">Panels</h1>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="ts-col-xs-12">
									<div class="ts-component preview-border-color">
										<div class="ts-panel ts-panel-primary">
											<div class="ts-panel-heading preview-border-color-bottom preview-button-color">
												<h3 class="ts-panel-title preview-title-color preview-button-color">Panel primary</h3>
											</div>
											<div class="ts-panel-body">
												Panel content
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="ts-docs-section">
							<div class="ts-page-header">
								<div class="row">
									<div class="ts-col-xs-12">
										<h1 id="ts-buttons" class="preview-text-color">Buttons</h1>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="ts-col-xs-12">
									<a href="javascript:;" class="ts-btn ts-btn-primary ts-btn-xs preview-title-color preview-border-color preview-button-color">Mini</a>
									<br>
									<a href="javascript:;" class="ts-btn ts-btn-primary ts-btn-sm preview-title-color preview-border-color preview-button-color">Small</a>
									<br>
									<a href="javascript:;" class="ts-btn ts-btn-primary preview-title-color preview-border-color preview-button-color">Medium</a>
									<br>
									<a href="javascript:;" class="ts-btn ts-btn-primary ts-btn-lg preview-title-color preview-border-color preview-button-color">Large</a>
								</div>
							</div>
						</div>
						<div class="ts-docs-section">
							<div class="ts-page-header">
								<div class="row">
									<div class="ts-col-xs-12">
										<h1 id="ts-typography" class="preview-text-color">Typography</h1>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="ts-col-xs-12">
									<h1 style="margin-top: 0px;" class="preview-text-color">Heading 1</h1>
									<p class="ts-lead">Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
								</div>
								<div class="ts-col-xs-12">
									<h2 class="preview-text-color">Example body text</h2>
									<p>Nullam quis risus eget
										<a href="#">urna mollis ornare</a> vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula.
									</p>
									<p>
										<small>This line of text is meant to be treated as fine print.</small>
									</p>
									<p>The following snippet of text is <strong>rendered as bold text</strong>.</p>
									<p>The following snippet of text is <em>rendered as italicized text</em>.</p>
									<p>An abbreviation of the word attribute is <abbr title="attribute">attr</abbr>.</p>
								</div>
							</div>
						</div>
					</div>
				</div>';			
				
	$html .= '<input type="hidden" name="update_form_'.$id.'" value="1" />';
	$html .= '</form>';
	$html .= '</div>';
	return $html;
}

function transporters_setting(){
	
	$html = '';

	$html .= '<div style="width:40%; float:left;">
	<h4>Enter your Google Maps API Key</h4>
	<p>'.__('Transporters.io relies on Google maps - you need to enter your free API key in the box below.','transportersio').'</p>';
	$html .= '<p><a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">'.__('Get Google map API Key','transportersio').'</a></p>';
	$html .= '<form id="quoteform_setting" method="post" action="'.admin_url( 'admin.php?page=transporters_quoteform').'#ts-setting">';
	$html .= '<table class="form-table">
	<tbody>
	<tr class="form-field form-required">
		<th scope="row"><label for="map_api_key">'.__('Google Map API Key','transportersio').' <span class="description">('.__('required','transportersio').')</span></label></th>
		<td><input name="map_api_key" type="text" id="map_api_key" value="'.get_option('transporters_map_api_key').'" ></td>
	</tr>
	</tbody>
	</table>
	<p class="submit"><input type="submit" class="button button-primary" value="'.__('Update Setting','transportersio').'"></p>
	</form>
	</div>
	<div style="width:45%; float:left;">
	<h4>Common Troubleshooting</h4>
	If you have problems with the address suggestions or google maps not displaying, please check the following.<br>
	<br>
	1) Click <a href="https://console.developers.google.com/apis/dashboard">https://console.developers.google.com/apis/dashboard</a>
		Then click enable API and ensure that the following are active.<br>
		<ul>
		<li>Google Places API Web Service</li>
		<li>Google Maps Geocoding API</li>
		<li>Google Maps Directions API</li>
		<li>Google Maps JavaScript API</li>
		</ul>
	<br> 
	2) Click <a href="https://console.developers.google.com/apis/credentials">https://console.developers.google.com/apis/credentials</a><br>
	Find the API key entered in the box on this page.<br>
	Under Key restriction choose "HTTP referrers (web sites)"<br>
	In the list of sites to accept make sure the following is present.<br>
	*yourwebsitename.com/*<br>
	[Replace "yourwebsitename.com" with your real website]<br>
	</div>';
	
	return $html;
}

?>
