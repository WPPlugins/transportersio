<?php
if($fixed == true){
	$html .= "<script>
	function getQuoteForm".$widget_type."(siteURL,widget_type){
	jQuery.ajax({
		url: siteURL+'api/v1/quote-form?fixed=true',
		jsonp: 'callback',
		data: {
			format: 'json'
		},
		success: function (response) {
			if (response.result == 'OK') {
				if (typeof response.templates.html_quote_form_confirmation !== 'undefined') {
					jQuery('#html_quote_form_confirmation'+widget_type).html(response.templates.html_quote_form_confirmation);
				}
				if (typeof response.templates.booking_details_box !== 'undefined' && response.templates.booking_details_box == true) {
					jQuery('#booking_details_box'+widget_type).show();
				}
				if (typeof response.templates.booking_details_box_description !== 'undefined') {
					jQuery('#booking_details_subject'+widget_type).html(response.templates.booking_details_box_description);
					jQuery('textarea[name=\"booking_details\"]').attr('placeholder', response.templates.booking_details_box_description);
				}
				
				if(response.startLocation !== 'undefined'){
					var startLocation = response.startLocation;
					if(startLocation.length > 0){
						var select_html = '<option data-id=\"0\" value=\"0\">Select a location</option>';
						for(var i=0;i<startLocation.length;i++){
							select_html += '<option data-endId=\"'+startLocation[i].end_id+'\" data-latitude=\"'+startLocation[i].latitude+'\" data-longitude=\"'+startLocation[i].longitude+'\" >'+startLocation[i].name+'</option>';
						}
						jQuery('#select-start-location'+widget_type).html(select_html);
					}
				}
				
				if(response.endLocation !== 'undefined'){
					var endLocation = response.endLocation;
					if(endLocation.length > 0){
						var select_html = '<option data-endId=\"0\" value=\"0\">Select a location</option>';
						for(var i=0;i<endLocation.length;i++){
							select_html += '<option data-id=\"'+endLocation[i].id+'\" data-latitude=\"'+endLocation[i].latitude+'\" data-longitude=\"'+endLocation[i].longitude+'\" >'+endLocation[i].name+'</option>';
						}
						jQuery('#select-end-location'+widget_type).html(select_html);
					}
				}
				
				if(response.startLocation !== 'undefined'){
					var startLocation = response.startLocation;
					if(startLocation.length > 0){
						var select_html = '<option data-id=\"0\" value=\"0\">Select a location</option>';
						for(var i=0;i<startLocation.length;i++){
							select_html += '<option data-endId=\"'+startLocation[i].end_id+'\" data-latitude=\"'+startLocation[i].latitude+'\" data-longitude=\"'+startLocation[i].longitude+'\" >'+startLocation[i].name+'</option>';
						}
						jQuery('#select-start-map-location'+widget_type).html(select_html);
					}
				}
				
				if(response.endLocation !== 'undefined'){
					var endLocation = response.endLocation;
					if(endLocation.length > 0){
						var select_html = '<option data-endId=\"0\" value=\"0\">Select a location</option>';
						for(var i=0;i<endLocation.length;i++){
							select_html += '<option data-id=\"'+endLocation[i].id+'\" data-latitude=\"'+endLocation[i].latitude+'\" data-longitude=\"'+endLocation[i].longitude+'\" >'+endLocation[i].name+'</option>';
						}
						jQuery('#select-end-map-location'+widget_type).html(select_html);
					}
				}

				jQuery.region = response.region;
				jQuery.country = response.country;

				jQuery('#panel-quote-form'+widget_type).fadeIn('fast');

				jQuery('#select-start-location'+widget_type).on('change',function(){
					jQuery('input[name=\"start_location_latitude\"]').val(jQuery(this).find(':selected').data('latitude'));
					jQuery('input[name=\"start_location_longitude\"]').val(jQuery(this).find(':selected').data('longitude'));

					jQuery('#select-end-location'+widget_type+' option').hide();
					var endid = jQuery(this).find(':selected').data('endid');
					if(endid.toString().indexOf(',') !== -1){
						cut_endid = endid.split(',');
						for(var i=0;i < cut_endid.length;i++){
							jQuery('#select-end-location'+widget_type+' option[data-id=' + cut_endid[i] + ']').show();
						}
						jQuery('#select-end-location'+widget_type+' option[value=0]').show();
						if(cut_endid.indexOf(jQuery('#select-end-location'+widget_type).find(':selected').data('id')) == -1 && jQuery('#select-end-location'+widget_type).find(':selected').data('id') != 0){
							jQuery('#select-end-location'+widget_type).val(0);
							jQuery('input[name=\"end_location_latitude\"]').val(0);
							jQuery('input[name=\"end_location_longitude\"]').val(0);
						}
					}else{
						jQuery('#select-end-location'+widget_type+' option[data-id=' + endid + ']').show();
						jQuery('#select-end-location'+widget_type+' option[value=0]').show();
						if(jQuery('#select-end-location'+widget_type).find(':selected').data('id') != endid && jQuery('#select-end-location'+widget_type).find(':selected').data('id') != 0){
							jQuery('#select-end-location'+widget_type).val(0);
							jQuery('input[name=\"end_location_latitude\"]').val(0);
							jQuery('input[name=\"end_location_longitude\"]').val(0);
						}
					}
					jQuery('#select-end-location'+widget_type+' option[data-id=0]').show();
				});

				jQuery('#select-end-location'+widget_type).on('change',function(){
					if(jQuery(this).find(':selected').data('latitude') && jQuery(this).find(':selected').data('longitude')){
						jQuery('input[name=\"end_location_latitude\"]').val(jQuery(this).find(':selected').data('latitude'));
						jQuery('input[name=\"end_location_longitude\"]').val(jQuery(this).find(':selected').data('longitude'));
					}else{
						jQuery('input[name=\"end_location_latitude\"]').val(0);
						jQuery('input[name=\"end_location_longitude\"]').val(0);
					}

				});

				jQuery('#start-date'+widget_type).datepicker({
					orientation: 'left',
					autoclose: true,
					format: 'dd-mm-yyyy'
				}).on('changeDate', function () {
					jQuery('#form-get-a-quote'+widget_type).valid();
				});
				jQuery('#start-time'+widget_type).timepicker({
					minuteStep: 5,
					showMeridian: false,
					defaultTime: ['12', '00'].join(':'),
				}).on('hide.timepicker', function (e) {
					var value = e.time.minutes % 5;
					if (value != 0) {
						jQuery('#start-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
					}
				});
				
				jQuery.validator.addMethod('notEqual', function(value, element, param) {
				  return this.optional(element) || value != param;
				}, 'This field is required.');

				jQuery('#form-get-a-quote'+widget_type).validate({
					errorElement: 'span',
					errorClass: 'help-block help-block-error',
					focusInvalid: false,
					ignore: '',
					rules: {
						start_location: {
							required: true,
							notEqual: 0
						},
						end_location: {
							required: true,
							notEqual: 0
						},
						start_date: {
							required: true
						},
						start_time: {
							required: true
						}
					},
					highlight: function (element) {
						jQuery(element).closest('.form-group').addClass('has-error');
					},
					unhighlight: function (element) {
						jQuery(element).closest('.form-group').removeClass('has-error');
					},
					success: function (label) {
						label.closest('.form-group').removeClass('has-error');
					},
					errorPlacement: function (error, element) {
						error.insertAfter(jQuery(element));
					},
					submitHandler: function () {
						var form = jQuery('#form-get-a-quote'+widget_type);
						var element = form.closest('.panel-body');
						jQuery.ajax({
							url: siteURL+'api/v1/quote-form/token?fixed=true',
							type: 'POST',
							data: form.serialize(),
							beforeSend: function (xhr) {
								element.block({
									message: '<i class=\"fa fa-spinner fa-pulse\"></i>',
									css: {
										border: 'inherit !important',
										backgroundColor: 'inherit !important'
									}
								});

								if (jQuery('input[name=\"start_location_latitude\"]').val().trim()
									&& jQuery('input[name=\"start_location_longitude\"]').val().trim()) {
									jQuery('#start-map-location-latitude'+widget_type).val(jQuery('input[name=\"start_location_latitude\"]').val());
									jQuery('#start-map-location-longitude'+widget_type).val(jQuery('input[name=start_location_longitude]').val());
								} else if (jQuery('#select-start-location'+widget_type).val().trim()) {
									var value = jQuery('#select-start-location'+widget_type).val();
									if (value.indexOf(jQuery.country) == -1) {
										value = [value, jQuery.country].join(', ');
									}

									var geocoder = new google.maps.Geocoder();
									geocoder.geocode({
										address: value,
										region: jQuery.region
									}, function (result, format) {
										if (format == 'OK') {
											jQuery('#start-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
											jQuery('#start-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
										}
									});
								}

								if (jQuery('input[name=\"end_location_latitude\"]').val().trim()
									&& jQuery('input[name=\"end_location_longitude\"]').val().trim()) {
									jQuery('#end-map-location-latitude'+widget_type).val(jQuery('input[name=\"end_location_latitude\"]').val());
									jQuery('#end-map-location-longitude'+widget_type).val(jQuery('input[name=\"end_location_longitude\"]').val());
								} else if (jQuery('#select-end-location'+widget_type).val().trim()) {
									var value = jQuery('#select-end-location'+widget_type).val();
									if (value.indexOf(jQuery.country) == -1) {
										value = [value, jQuery.country].join(', ');
									}

									var geocoder = new google.maps.Geocoder();
									geocoder.geocode({
										address: value,
										region: jQuery.region
									}, function (result, format) {
										if (format == 'OK') {
											jQuery('#end-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
											jQuery('#end-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
										}
									});
								}
							}
						}).done(function (data, textStatus, jqXHR) {";
							
			$html .= "jQuery.ajax({
											url: transporters_ajax_object.ajax_url,
											data:{
												action : 'get_stage',
												stage : 'after1',
												widget_id : '".$widget_id."'
											},
											success:function(result){
												jQuery('body').append(result);	
											}
										});";
							
			$html .= "element.fadeOut(function () {
								jQuery('#panel-quotation-request'+widget_type).fadeIn(function () {
									// START MAP
									jQuery('#select-start-map-location'+widget_type).val(jQuery('#select-start-location'+widget_type).val());

									jQuery('#select-start-map-location'+widget_type).on('change focusout', function () {

										jQuery('#start-map-location-latitude'+widget_type).val(jQuery(this).find(':selected').data('latitude'));
										jQuery('#start-map-location-longitude'+widget_type).val(jQuery(this).find(':selected').data('longitude'));

										jQuery('#select-end-map-location'+widget_type+' option').hide();
										var endid = jQuery(this).find(':selected').data('endid');
										if(endid.toString().indexOf(',') !== -1){
											cut_endid = endid.split(',');
											for(var i=0;i < cut_endid.length;i++){
												jQuery('#select-end-map-location'+widget_type+' option[data-id=' + cut_endid[i] + ']').show();
											}
											jQuery('#select-end-map-location'+widget_type+' option[value=0]').show();
											if(cut_endid.indexOf(jQuery('#select-end-map-location'+widget_type).find(':selected').data('id')) == -1 && jQuery('#select-end-map-location'+widget_type).find(':selected').data('id') != 0){
												jQuery('#select-end-map-location'+widget_type).val(0);
												jQuery('#end-map-location-latitude'+widget_type).val(0);
												jQuery('#end-map-location-longitude'+widget_type).val(0);
											}
										}else{
											jQuery('#select-end-map-location'+widget_type+' option[data-id=' + endid + ']').show();

											jQuery('#select-end-map-location'+widget_type+' option[value=0]').show();
											if(jQuery('#select-end-map-location'+widget_type).find(':selected').data('id') != endid && jQuery('#select-end-map-location'+widget_type).find(':selected').data('id') != 0){
												jQuery('#select-end-map-location'+widget_type).val(0);
												jQuery('#end-map-location-latitude'+widget_type).val(0);
												jQuery('#end-map-location-longitude'+widget_type).val(0);
											}
										}
										jQuery('#select-end-map-location'+widget_type+' option[data-id=0]').show();


									});

									jQuery('#select-end-map-location'+widget_type+' option').hide();
									var endid = jQuery('#select-start-location'+widget_type).find(':selected').data('endid');
									if(endid.toString().indexOf(',') !== -1){
										cut_endid = endid.split(',');
										for(var i=0;i < cut_endid.length;i++){
											jQuery('#select-end-map-location'+widget_type+' option[data-id=' + cut_endid[i] + ']').show();
										}
									}else{
										jQuery('#select-end-map-location'+widget_type+' option[data-id=' + endid + ']').show();
									}
									jQuery('#select-end-map-location'+widget_type+' option[data-id=0]').show();

									jQuery('#select-end-map-location'+widget_type).val(jQuery('#select-end-location'+widget_type).val());

									jQuery('#select-end-map-location'+widget_type).on('change focusout', function () {

										jQuery('#end-map-location-latitude'+widget_type).val(jQuery(this).find(':selected').data('latitude'));
										jQuery('#end-map-location-longitude'+widget_type).val(jQuery(this).find(':selected').data('longitude'));

									});

									var tz = moment([jQuery('#start-date'+widget_type).val(), jQuery('#start-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
									tz.format('DD-MM-YYYY');
									tz.format('H:mm');
									jQuery('#pickup-date'+widget_type).val(tz.format('DD-MM-YYYY'));
									jQuery('#pickup-date'+widget_type).datepicker({
										orientation: 'left',
										autoclose: true,
										format: 'dd-mm-yyyy'
									}).on('changeDate', function () {
										if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
											var tz = moment([jQuery('#pickup-date'+widget_type).val(), jQuery('#pickup-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
											if (tz.isAfter(moment([jQuery('#return-date'+widget_type).val(), jQuery('#return-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm'))) {
												jQuery('#return-date'+widget_type).datepicker('update', tz.format('DD-MM-YYYY'));
												jQuery('#return-time'+widget_type).timepicker('setTime', tz.format('H:mm'));
											}
										}

										jQuery('#form-quotation-request'+widget_type).valid();
									});
									jQuery('#pickup-time'+widget_type).timepicker({
										minuteStep: 5,
										showMeridian: false,
										defaultTime: tz.format('H:mm')
									}).on('hide.timepicker', function (e) {
										jQuery('#pickup-date'+widget_type).trigger('changeDate');

										var value = e.time.minutes % 5;
										if (value != 0) {
											jQuery('#pickup-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
										}
									});
									
									jQuery('#return-journey-needed'+widget_type).on('click', function () {
										var element = jQuery('#form-group-return'+widget_type);
										if (jQuery(this).prop('checked')) {
											var tz = moment([jQuery('#pickup-date'+widget_type).val(), jQuery('#pickup-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
											tz.format('DD-MM-YYYY');
											tz.format('H:mm');
											jQuery('#return-date'+widget_type).val(tz.format('DD-MM-YYYY'));
											jQuery('#return-date'+widget_type).datepicker({
												orientation: 'left',
												autoclose: true,
												format: 'dd-mm-yyyy'
											}).on('changeDate', function () {
												jQuery('#pickup-date'+widget_type).trigger('changeDate');

												jQuery('#form-quotation-request'+widget_type).valid();
											});
											jQuery('#return-time'+widget_type).timepicker({
												minuteStep: 5,
												showMeridian: false,
												defaultTime: tz.format('H:mm')
											}).on('hide.timepicker', function (e) {
												jQuery('#pickup-date'+widget_type).trigger('changeDate');

												var value = e.time.minutes % 5;
												if (value != 0) {
													jQuery('#return-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
												}
											});

											element.slideDown();
										} else {
											element.slideUp();
										}
									});";
									
							if(get_option('transporters_return_journey_'.$widget_id) == 1){				
											
							  $html .= "
										jQuery('#return-journey-needed'+widget_type).attr('checked','checked');
										
										var element = jQuery('#form-group-return'+widget_type);
											if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
												var tz = moment([jQuery('#pickup-date'+widget_type).val(), jQuery('#pickup-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
												tz.format('DD-MM-YYYY');
												tz.format('H:mm');
												jQuery('#return-date'+widget_type).val(tz.format('DD-MM-YYYY'));
												jQuery('#return-date'+widget_type).datepicker({
													orientation: 'left',
													autoclose: true,
													dateFormat: 'dd-mm-yy'
												}).on('changeDate', function () {
													jQuery('#pickup-date'+widget_type).trigger('changeDate');

													jQuery('#form-quotation-request'+widget_type).valid();
												});
												jQuery('#return-time'+widget_type).timepicker({
													minuteStep: 5,
													showMeridian: false,
													defaultTime: tz.format('H:mm')
												}).on('hide.timepicker', function (e) {
													jQuery('#pickup-date'+widget_type).trigger('changeDate');

													var value = e.time.minutes % 5;
													if (value != 0) {
														jQuery('#return-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
													}
												});

												element.slideDown();
											} else {
												element.slideUp();
											}";	
								}

							$html .=    "// VEHICLE
									jQuery('#form-group-input'+widget_type).children().remove();
									jQuery.each(data.input, function (groupName, inputMultiple) {
										console.log(groupName, inputMultiple);
										if (typeof inputMultiple != 'undefined' && typeof inputMultiple[0] != 'undefined') {
											var append = '<div class=\"form-horizontal\">\
							<h4 id=\"group-' + inputMultiple[0].group + '\" class=\"no-margin\" style=\"font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #959595; font-weight: 700;\">' + groupName + '<small> (' + data.max[inputMultiple[0].group].toFixed(2) + ')</small></h4>';
											jQuery.each(inputMultiple, function (index, value) {
												append += '<div class=\"form-group form-group-sm\">\
<label for=\"input-field-' + value.type + '\" class=\"col-xs-5 control-label\">' + value.name + '</label>\
<div class=\"col-xs-7\">\
	<input data-group=\"' + value.group + '\" data-min=\"' + value.min + '\" data-max=\"' + value.count + '\" data-type=\"' + value.type + '\" data-rate=\"' + value.rate + '\" type=\"text\" name=\"input[' + value.type + ']\" id=\"input-' + value.type + '\" class=\"form-control input\" value=\"0\">\
</div>\
</div>';
											});
											append += '</div>';
											jQuery('#form-group-input'+widget_type).append(append);
										}
									});
									jQuery.each(data.max, function (groupSlug, maxRate) {
										jQuery.each(data.input, function (groupName, inputMultiple) {
											jQuery.each(inputMultiple, function (index, value) {
												var element = jQuery('input[data-type=\"' + value.type + '\"]');
												if (typeof element != 'undefined' && element.data('group') == groupSlug) {
													var inputRate = parseFloat(element.data('rate'));
													if (isNaN(inputRate)) inputRate = 0;
													element.attr('data-max', maxRate / inputRate);
												}
											});
										});
									});
									jQuery('#vehicleType'+widget_type).children().remove();
									jQuery('#vehicleType'+widget_type).append(jQuery('<option>', {
										value: '',
										text: 'Select vehicle type',
										disabled: true,
										selected: true,
										'class': 'disabled'
									}));
									jQuery.each(data.vehicleType, function (index, value) {
										jQuery('#vehicleType'+widget_type).append(jQuery('<option>', value));
									});
									jQuery('label[for=\"vehicleType\"]').children('span')
										.children('small')
										.text(' (' + (jQuery('#vehicleType'+widget_type).children().length - jQuery('#vehicleType'+widget_type).children('.disabled, .hide').length) + ')');

									if (typeof data.journeyType !== 'undefined' && data.journeyType.length > 0) {
										jQuery('#form-group-journeyType'+widget_type).show();
										jQuery('#journeyType'+widget_type).children().remove();
										jQuery('#journeyType'+widget_type).append(jQuery('<option>', {
											value: '',
											text: 'Select journey type',
											disabled: true,
											selected: true,
											'class': 'disabled'
										}));
										jQuery.each(data.journeyType, function (index, value) {
											jQuery('#journeyType'+widget_type).append(jQuery('<option>', value));
										});
									}

									jQuery('.input').TouchSpin({
										buttondown_class: 'btn btn-sm btn-primary',
										buttonup_class: 'btn btn-sm btn-primary'
									});
									jQuery('.input').on('change', function () {
										jQuery.each(data.max, function (groupSlug, maxRate) {
											var number = [];
											var amount = 0;
											jQuery.each(data.input, function (groupName, inputMultiple) {
												jQuery.each(inputMultiple, function (index, value) {
													var input = jQuery('input[data-type=\"' + value.type + '\"]');
													if (typeof input != 'undefined' && input.data('group') == groupSlug) {
														var type = parseInt(input.data('type'));
														if (isNaN(type)) type = 0;

														var rate = parseFloat(input.data('rate'));
														if (isNaN(rate)) rate = 0;

														var val = parseInt(input.val());
														if (isNaN(val)) val = 0;

														number[type] = val * rate;
														amount += number[type];
													}
												});
											});
											jQuery.each(data.input, function (groupName, inputMultiple) {
												jQuery.each(inputMultiple, function (index, value) {
													var input = jQuery('input[data-type=\"' + value.type + '\"]');
													if (typeof input != 'undefined' && input.data('group') == groupSlug) {
														var type = parseInt(input.data('type'));
														if (isNaN(type)) type = 0;

														var rate = parseFloat(input.data('rate'));
														if (isNaN(rate)) rate = 0;

														var val = parseInt(input.val());
														if (isNaN(val)) val = 0;

														var fixed = parseFloat((maxRate - amount).toFixed(2));
														var toFixed = parseFloat((fixed / rate).toFixed(2));
														var available = parseInt(toFixed + val);

														input.attr('data-max', available);
														input.trigger(\"touchspin.updatesettings\", {max: available});
													}
												});
											});

											var labelSmall = ( maxRate - amount );
											jQuery('#group-' + groupSlug).children('small').html(' (' + labelSmall.toFixed(2) + ')');
										});

										var rules = [];
										jQuery.each(data.input, function (groupName, inputMultiple) {
											jQuery.each(inputMultiple, function (index, value) {
												var input = jQuery('input[data-type=\"' + value.type + '\"]');
												if (typeof input != 'undefined') {
													var group = input.data('group');
													if (typeof group != 'undefined') {
														if (typeof rules[group] == 'undefined') {
															rules[group] = 0;
														}

														var rate = input.data('rate');
														if (isNaN(rate)) rate = 0;

														var val = input.val();
														if (isNaN(val)) val = 0;

														rules[group] += val * rate;
													}
												}
											});
										});
										jQuery('#vehicleType'+widget_type).children().each(function (index, value) {
											jQuery(value).removeClass('hide');
										});
										jQuery('#vehicleType'+widget_type).children().each(function (index, value) {
											var input = jQuery(value);
											if (typeof input.data() != 'undefined') {
												jQuery.each(input.data(), function (key, value) {
													if (rules[key] > value) {
														if (jQuery('#vehicleType'+widget_type).val() == input.val()) {
															jQuery('#vehicleType'+widget_type).val('');
														}
														input.addClass('hide');
													}
												});
											}
										});

										jQuery('label[for=\"vehicleType\"]').children('span')
											.children('small')
											.text(' (' + (jQuery('#vehicleType'+widget_type).children().length - jQuery('#vehicleType'+widget_type).children('.disabled, .hide').length) + ')');


									});

									jQuery('#form-quotation-request'+widget_type).validate({
										errorElement: 'span',
										errorClass: 'help-block help-block-error',
										focusInvalid: false,
										ignore: '',
										rules: {
											start_map_location: {
												required: true,
												notEqual: 0
											},
											end_map_location: {
												required: true,
												notEqual: 0
											},
											pickup_date: {
												required: true
											},
											pickup_time: {
												required: true
											},
											return_journey_needed: {
												required: false
											},
											return_date: {
												required: false
											},
											return_time: {
												required: false
											},
											vehicle_type_id: {
												required: true
											},
											contact_name: {
												required: true
											},
											contact_email: {
												required: true,
												email: true
											},
											mobile_number: {
												required: true
											}
										},
										highlight: function (element) {
											var input = jQuery(element);
											if (input.attr('name') == 'start_map_location' || input.attr('name') == 'end_map_location') {
												input.parent().children('.input-group-btn').find('.btn').removeClass('btn-default').addClass('btn-danger');
											}
											input.closest('.form-group').addClass('has-error');
										},
										unhighlight: function (element) {
											var input = jQuery(element);
											if (input.attr('name') == 'start_map_location' || input.attr('name') == 'end_map_location') {
												input.parent().children('.input-group-btn').find('.btn').removeClass('btn-danger').addClass('btn-default');
											}
											input.closest('.form-group').removeClass('has-error');
										},
										success: function (label) {
											label.closest('.form-group').removeClass('has-error');
										},
										errorPlacement: function (error, element) {
											var input = jQuery(element);
											if (input.attr('name') == 'start_map_location' || input.attr('name') == 'end_map_location') {
												input.parent().children('.input-group-btn').find('.btn').removeClass('btn-default').addClass('btn-danger');
												error.insertAfter(input.closest('.form-group').children('.mapdiv'));
											} else {
												error.insertAfter(input);
											}
										},
										submitHandler: function () {
											jQuery.fn.goToPanelThankYou = function () {
												var form = jQuery('#form-quotation-request'+widget_type);
												var element = form.closest('.panel-body');
												jQuery.ajax({
													url: siteURL+'api/v1/quote-form/store?fixed=true',
													type: 'POST',
													data: jQuery('#form-get-a-quote'+widget_type+', #form-quotation-request'+widget_type+', #form-thank-you'+widget_type).serialize(),
													beforeSend: function (xhr) {
														element.block({
															message: '<i class=\"fa fa-spinner fa-pulse\"></i>',
															css: {
																border: 'inherit !important',
																backgroundColor: 'inherit !important'
															}
														});
													}
												}).done(function (data, textStatus, jqXHR) {
													if (typeof data !== 'undefined'
														&& typeof data.result !== 'undefined'
														&& data.result == 'ok') {
															
														var html_thank_you = jQuery('#html_quote_form_confirmation'+widget_type).html();
														if (typeof data.replacePairs !== 'undefined') {
															jQuery.each(data.replacePairs, function (key, value) {
																html_thank_you = html_thank_you.replace(key, value);
															});
														}
														jQuery('#html_quote_form_confirmation'+widget_type).html(html_thank_you);";
														
														$html .= "
															jQuery.ajax({
																url: transporters_ajax_object.ajax_url,
																data:{
																	action : 'get_stage',
																	stage : 'after2',
																	widget_id : '".$widget_id."'
																},
																success:function(result){
																	jQuery('body').append(result);	
																}
															});
															";

												$html .= "element.fadeOut(function () {
															jQuery('#panel-thank-you'+widget_type).fadeIn(function () {
															});
														});
													}
												}).fail(function (jqXHR, textStatus, errorThrown) {
													jQuery('#alert-quotation-request'+widget_type).empty();
													if (typeof jqXHR.responseJSON === 'undefined') {
														jqXHR.responseJSON = [['Sorry, Internal Server Error.']];
													}

													jQuery.each(jqXHR.responseJSON, function (key, rules) {
														jQuery.each(rules, function (index, rule) {
															jQuery('#alert-quotation-request'+widget_type).append('<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">\
<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\
	<span aria-hidden=\"true\">&times;</span>\
</button>\
<strong>' + rule + '</strong>\
</div>');
														});
													});
												}).always(function () {
													element.unblock();
												});
											};

											/*var START_MAPDIV_MARKER_POSITION = START_MAPDIV_MARKER.getPosition();
											jQuery('#start-map-location-latitude').val(START_MAPDIV_MARKER_POSITION.lat());
											jQuery('#start-map-location-longitude').val(START_MAPDIV_MARKER_POSITION.lng());

											var END_MAPDIV_MARKER_POSITION = END_MAPDIV_MARKER.getPosition();
											jQuery('#end-map-location-latitude').val(END_MAPDIV_MARKER_POSITION.lat());
											jQuery('#end-map-location-longitude').val(END_MAPDIV_MARKER_POSITION.lng());*/

											jQuery('#form-thank-you'+widget_type).empty();
											getDistances(widget_type,function(){
												jQuery(this).goToPanelThankYou();
											});
										}
									});
									jQuery('#btn-quotation-request'+widget_type).prop('disabled', false)
										.on('click', function () {
											jQuery('#form-quotation-request'+widget_type).valid();
										});
									jQuery('#btn-back-get-quote'+widget_type).prop('disabled', false)
										.on('click', function () {";
								
								$html .= "
											jQuery.ajax({
																url: transporters_ajax_object.ajax_url,
																data:{
																	action : 'get_stage',
																	stage : 'back',
																	widget_id : '".$widget_id."'
																},
																success:function(result){
																	jQuery('body').append(result);	
																}
															});
															";
										
										
								$html .= "var element = jQuery(this).closest('.panel-body');
											element.block({
												message: '<i class=\"fa fa-spinner fa-pulse\"></i>',
												css: {
													border: 'inherit !important',
													backgroundColor: 'inherit !important'
												}
											});
											element.unblock().fadeOut(function () {
												jQuery('#panel-get-a-quote'+widget_type).fadeIn(function () {
													//
												});
											});
										});
								});
							});
						}).fail(function (jqXHR, textStatus, errorThrown) {
							jQuery('#alert-get-a-quote'+widget_type).empty();
							jQuery.each(jqXHR.responseJSON, function (key, rules) {
								jQuery.each(rules, function (index, rule) {
									jQuery('#alert-get-a-quote'+widget_type).append('<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">\
<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\
	<span aria-hidden=\"true\">&times;</span>\
</button>\
<strong>' + rule + '</strong>\
</div>');
								});
							});
						}).always(function () {
							element.unblock();
						});
					}
				});
				jQuery('#btn-get-a-quote'+widget_type).prop('disabled', false)
					.on('click', function () {
						jQuery('#form-get-a-quote'+widget_type).valid();
					});
			}
		}
	});

	jQuery('body').on('change', '#vehicleType'+widget_type, function () {
		getDistances(widget_type);
	});
}

function getDistances(widget_type,success) {
	jQuery('#transport-distance'+widget_type).remove();
	jQuery('#transport-duration'+widget_type).remove();
	jQuery('#return-distance'+widget_type).remove();
	jQuery('#return-duration'+widget_type).remove();

	var element = jQuery('#vehicleType'+widget_type+' option:selected');
	var TYPE = element.attr('type');
	switch (TYPE) {
		case 'google-driving':
			var directionsService = new google.maps.DirectionsService();

			if (typeof( jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat') ) == 'undefined') {

				var request = {
					origin: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
					destination: new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
					travelMode: google.maps.TravelMode.DRIVING
				};
			} else {
				//console.log('do base2base price');
				var request = {
					origin: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
					waypoints: [{
						location: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
						stopover: false
					}, {
						location: new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
						stopover: false
					}],
					destination: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
					travelMode: google.maps.TravelMode.DRIVING
				};
				//[[TODO - account for wait and stay returns]]
			}

			directionsService.route(request, function (result, format) {
				if (format == google.maps.DirectionsStatus.OK) {
					jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
						type: 'hidden',
						name: 'transport_distance',
						id: 'transport-distance'+widget_type,
						value: result.routes[0].legs[0].distance.value
					}));
					jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
						type: 'hidden',
						name: 'transport_duration',
						id: 'transport-duration'+widget_type,
						value: result.routes[0].legs[0].duration.value
					}));

					if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
						var directionsService = new google.maps.DirectionsService();

						if (typeof( jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat') ) == 'undefined') {

							var request = {
								origin: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
								destination: new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
								travelMode: google.maps.TravelMode.DRIVING
							};
						} else {
							//console.log('do base2base price');
							var request = {
								origin: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
								waypoints: [{
									location: new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
									stopover: false
								}, {
									location: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
									stopover: false
								}],
								destination: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
								travelMode: google.maps.TravelMode.DRIVING
							};
							//[[TODO - account for wait and stay returns]]
						}


						directionsService.route(request, function (result, format) {
							if (format == google.maps.DirectionsStatus.OK) {
								jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
									type: 'hidden',
									name: 'return_distance',
									id: 'return-distance'+widget_type,
									value: result.routes[0].legs[0].distance.value
								}));
								jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
									type: 'hidden',
									name: 'return_duration',
									id: 'return-duration'+widget_type,
									value: result.routes[0].legs[0].duration.value
								}));

								if (typeof success !== 'undefined') {
									jQuery(this).goToPanelThankYou();
								}
							}
						});
					} else {
						if (typeof success !== 'undefined') {
							jQuery(this).goToPanelThankYou();
						}
					}
				}
			});
			break;
		case 'straight-line':
			var UNIT = element.attr('unit');
			var PER_HOUR = element.attr('per_hour');

			var UNIT_RATE = 0;
			switch (UNIT) {
				case 'km':
					UNIT_RATE = 1000;
					break;
				case 'mile':
					UNIT_RATE = 1609.34;
					break;
				case 'nmi':
					UNIT_RATE = 1852;
					break;
				default:
					UNIT_RATE = 1;
					break;
			}

			var from = new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val());
			var to = new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val());
			var computeDistanceBetween = google.maps.geometry.spherical.computeDistanceBetween(from, to);
			jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
				type: 'hidden',
				name: 'transport_distance',
				id: 'transport-distance',
				value: computeDistanceBetween
			}));
			jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
				type: 'hidden',
				name: 'transport_duration',
				id: 'transport-duration',
				value: ( (computeDistanceBetween / UNIT_RATE) / PER_HOUR ) * 60 * 60
			}));

			if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
				var from = new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val());
				var to = new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val());
				var computeDistanceBetween = google.maps.geometry.spherical.computeDistanceBetween(from, to);

				jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
					type: 'hidden',
					name: 'return_distance',
					id: 'return-distance',
					value: computeDistanceBetween
				}));
				jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
					type: 'hidden',
					name: 'return_duration',
					id: 'return-duration',
					value: ( (computeDistanceBetween / UNIT_RATE) / PER_HOUR ) * 60 * 60
				}));

				if (typeof success !== 'undefined') {
					jQuery(this).goToPanelThankYou();
				}
			} else {
				if (typeof success !== 'undefined') {
					jQuery(this).goToPanelThankYou();
				}
			}
			break;
		default:
			window.alert('Please select other vehicle type');
			break;
	}
}
</script>
";

}else{
		$html .= "<script>
		var START_MAPDIV_LATLNG;
		var START_MAPDIV;
		var END_MAPDIV;
	function getQuoteForm".$widget_type."(siteURL,widget_type){
			jQuery.ajax({
				url: siteURL+'api/v1/quote-form',
				jsonp: 'callback',
				data: {
					format: 'json'
				},
				success: function (response) {
					if (response.result == 'OK') {
						if (typeof response.templates.html_quote_form_confirmation !== 'undefined') {
							jQuery('#html_quote_form_confirmation'+widget_type).html(response.templates.html_quote_form_confirmation);
						}
						if (typeof response.templates.booking_details_box !== 'undefined' && response.templates.booking_details_box == true) {
							jQuery('#booking_details_box'+widget_type).show();
						}
						if (typeof response.templates.booking_details_box_description !== 'undefined') {
							jQuery('#booking_details_subject'+widget_type).html(response.templates.booking_details_box_description);
							jQuery('textarea[name=\"booking_details\"]').attr('placeholder', response.templates.booking_details_box_description);
						}
	
						jQuery.region = response.region;
						jQuery.country = response.country;
	
						jQuery('#panel-quote-form'+widget_type).fadeIn();
	
						var START_AUTOCOMPLETE = new google.maps.places.Autocomplete(document.getElementById('start-location'+widget_type), {
							componentRestrictions: {
								country: jQuery.region
							}
						});
						
						START_AUTOCOMPLETE.addListener('place_changed', function () {
							var place = START_AUTOCOMPLETE.getPlace();
							if (!place || !place.geometry) {
								console.log(\"Autocomplete's returned place contains no geometry\");
								return;
							}
	
							jQuery('input[name=\"start_location_latitude\"]').val(place.geometry.location.lat());
							jQuery('input[name=\"start_location_longitude\"]').val(place.geometry.location.lng());
						});
	
	
						
						var END_AUTOCOMPLETE = new google.maps.places.Autocomplete(document.getElementById('end-location'+widget_type), {
							componentRestrictions: {
								country: jQuery.region
							}
						});
						
						END_AUTOCOMPLETE.addListener('place_changed', function () {
							var place = END_AUTOCOMPLETE.getPlace();
							if (!place || !place.geometry) {
								console.log(\"Autocomplete's returned place contains no geometry\");
								return;
							}
	
							jQuery('input[name=\"end_location_latitude\"]').val(place.geometry.location.lat());
							jQuery('input[name=\"end_location_longitude\"]').val(place.geometry.location.lng());
						});
	
	
						jQuery('#start-date'+widget_type).datepicker({
							orientation: 'left',
							autoclose: true,
							dateFormat: 'dd-mm-yy'
						}).on('changeDate', function () {
							jQuery('#form-get-a-quote'+widget_type).valid();
						});
						jQuery('#start-time'+widget_type).timepicker({
							minuteStep: 5,
							showMeridian: false,
							defaultTime: ['12', '00'].join(':'),
						}).on('hide.timepicker', function (e) {
							var value = e.time.minutes % 5;
							if (value != 0) {
								jQuery('#start-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
							}
						});";
	
			$html .=  "jQuery('#form-get-a-quote'+widget_type).validate({
							onclick: true,
							errorElement: 'span',
							errorClass: 'help-block help-block-error',
							focusInvalid: false,
							ignore: '',
							rules: {
								start_location: {
									required: true
								},
								end_location: {
									required: true
								},
								start_date: {
									required: true
								},
								start_time: {
									required: true
								}
							},
							messages: {
								start_location: '".__('This field is required.','transportersio')."',
								end_location: '".__('This field is required.','transportersio')."',
								start_date: '".__('This field is required.','transportersio')."',
								start_time: '".__('This field is required.','transportersio')."'
							
							},
							highlight: function (element) {
								jQuery(element).closest('.form-group').addClass('has-error');
							},
							unhighlight: function (element) {
								jQuery(element).closest('.form-group').removeClass('has-error');
							},
							success: function (label) {
								label.closest('.form-group').removeClass('has-error');
							},
							errorPlacement: function (error, element) {
								error.insertAfter(jQuery(element));
							},
							submitHandler: function () {
								var form = jQuery('#form-get-a-quote'+widget_type);
								var element = form.closest('.panel-body');
								jQuery.ajax({
									url: siteURL+'api/v1/quote-form/token',
									type: 'POST',
									data: form.serialize(),
									beforeSend: function (xhr) {
										element.block({
											message: '<i class=\"fa fa-spinner fa-pulse\"></i>',
											css: {
												border: 'inherit !important',
												backgroundColor: 'inherit !important'
											}
										});
	
										/*if (jQuery('#start-location'+widget_type).val().trim()) {
											var value = jQuery('#start-location'+widget_type).val();
											if (value.indexOf(jQuery.country) == -1) {
												value = [value, jQuery.country].join(', ');
											}
	
											var geocoder = new google.maps.Geocoder();
											geocoder.geocode({
												address: value,
												region: jQuery.region
											}, function (result, format) {
												if (format == 'OK') {
													jQuery('#start-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
													jQuery('#start-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
												}
											});
										}*/
										
										if (jQuery('input[name=\"start_location_latitude\"]').val().trim()
												&& jQuery('input[name=\"start_location_longitude\"]').val().trim()) {
											jQuery('#start-map-location-latitude'+widget_type).val(jQuery('input[name=\"start_location_latitude\"]').val());
											jQuery('#start-map-location-longitude'+widget_type).val(jQuery('input[name=start_location_longitude]').val());
										} else if (jQuery('#start-location'+widget_type).val().trim()) {
											var value = jQuery('#start-location'+widget_type).val();
											if (value.indexOf(jQuery.country) == -1) {
												value = [value, jQuery.country].join(', ');
											}
	
											var geocoder = new google.maps.Geocoder();
											geocoder.geocode({
												address: value,
												region: jQuery.region
											}, function (result, format) {
												if (format == 'OK') {
													jQuery('#start-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
													jQuery('#start-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
												}
											});
										}
	
										
										/*if (jQuery('#end-location'+widget_type).val().trim()) {
											var value = jQuery('#end-location'+widget_type).val();
											if (value.indexOf(jQuery.country) == -1) {
												value = [value, jQuery.country].join(', ');
											}
	
											var geocoder = new google.maps.Geocoder();
											geocoder.geocode({
												address: value,
												region: jQuery.region
											}, function (result, format) {
												if (format == 'OK') {
													jQuery('#end-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
													jQuery('#end-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
												}
											});
										}*/
										
										if (jQuery('input[name=\"end_location_latitude\"]').val().trim()
												&& jQuery('input[name=\"end_location_longitude\"]').val().trim()) {
											jQuery('#end-map-location-latitude'+widget_type).val(jQuery('input[name=\"end_location_latitude\"]').val());
											jQuery('#end-map-location-longitude'+widget_type).val(jQuery('input[name=\"end_location_longitude\"]').val());
										} else if (jQuery('#end-location'+widget_type).val().trim()) {
											var value = jQuery('#end-location'+widget_type).val();
											if (value.indexOf(jQuery.country) == -1) {
												value = [value, jQuery.country].join(', ');
											}
	
											var geocoder = new google.maps.Geocoder();
											geocoder.geocode({
												address: value,
												region: jQuery.region
											}, function (result, format) {
												if (format == 'OK') {
													jQuery('#end-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
													jQuery('#end-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
												}
											});
										}
										
									}
								}).done(function (data, textStatus, jqXHR) {";
								
								$html .= "jQuery.ajax({
											url: transporters_ajax_object.ajax_url,
											data:{
												action : 'get_stage',
												stage : 'after1',
												widget_id : '".$widget_id."'
											},
											success:function(result){
												jQuery('body').append(result);	
											}
										});";
										
								 $html .= "element.fadeOut(function () {
										jQuery('#panel-quotation-request'+widget_type).fadeIn(function () {
											// START MAP
											jQuery('#start-map-location'+widget_type).val(jQuery('#start-location'+widget_type).val());
											jQuery('#start-map-location'+widget_type).on('change focusout', function () {
												if (!jQuery(this).val().trim()) {
													return;
												}
	
												var geocoder = new google.maps.Geocoder();
												geocoder.geocode({
													address: jQuery(this).val(),
													region: jQuery.region
												}, function (result, format) {
													if (format == 'OK') {
														if (!result[0] || !result[0].geometry) {
															return;
														}
	
														START_MAPDIV.setCenter(result[0].geometry.location);
														START_MAPDIV_MARKER.setPosition(result[0].geometry.location);
	
														jQuery('#start-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
														jQuery('#start-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
													}
												});
											});
																					
											
											jQuery('#btn-start-map-location'+widget_type).on('click', function () {
												var getPosition = START_MAPDIV_MARKER.getPosition();
												START_MAPDIV.panTo(getPosition);
	
												jQuery('#start-map-location-latitude'+widget_type).val(getPosition.lat());
												jQuery('#start-map-location-longitude'+widget_type).val(getPosition.lng());
	
												var geocoder = new google.maps.Geocoder();
												geocoder.geocode({
													location: getPosition,
													region: jQuery.region
												}, function (result, format) {
													if (format == 'OK') {
														if (!result[0] || !result[0].geometry) {
															return;
														}
	
														jQuery('#start-map-location'+widget_type).val(result[0].formatted_address);
													}
												});
											});
	
											var START_MAP_AUTOCOMPLETE = new google.maps.places.Autocomplete(document.getElementById('start-map-location'+widget_type), {
												componentRestrictions: {
													country: jQuery.region
												}
											});
											START_MAP_AUTOCOMPLETE.addListener('place_changed', function () {
												var place = START_MAP_AUTOCOMPLETE.getPlace();
												if (!place || !place.geometry) {
													console.log(\"Autocomplete's returned place contains no geometry\");
													return;
												}
	
	
												START_MAPDIV.setCenter(place.geometry.location);
												START_MAPDIV_MARKER.setPosition(place.geometry.location);
											});
	
											var START_MAPDIV_LATLNG = new google.maps.LatLng(51.498, -0.126);
											if (jQuery('#start-map-location-latitude'+widget_type).val().trim() && jQuery('#start-map-location-longitude'+widget_type).val().trim()) {
												START_MAPDIV_LATLNG = new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val());
											}
											
											START_MAPDIV = new google.maps.Map(document.getElementById('start-mapdiv-location'+widget_type), {
												center: START_MAPDIV_LATLNG,
												zoom: 8,
												mapTypeId: google.maps.MapTypeId.ROADMAP,
												streetViewControl: false
											});
											START_MAPDIV.controls[google.maps.ControlPosition.TOP_RIGHT].push(new FullScreenControl(START_MAPDIV));
	
											var START_MAPDIV_MARKER = new google.maps.Marker({
												position: START_MAPDIV_LATLNG,
												map: START_MAPDIV,
												draggable: true
											});
											google.maps.event.addListener(START_MAPDIV_MARKER, 'dragend', function () {
												jQuery('#btn-start-map-location'+widget_type).trigger('click');
											});
											
											google.maps.event.addDomListener(window, 'resize', function() {
												var center = START_MAPDIV.getCenter();
												google.maps.event.trigger(START_MAPDIV, 'resize');
												START_MAPDIV.setCenter(center); 
											});
										
											jQuery('#end-map-location'+widget_type).val(jQuery('#end-location'+widget_type).val());
											jQuery('#end-map-location'+widget_type).on('change focusout', function () {
												if (!jQuery(this).val().trim()) {
													return;
												}
	
												var value = jQuery(this).val();
												if (value.indexOf(jQuery.country) == -1) {
													value = [value, jQuery.country].join(', ');
												}
	
												var geocoder = new google.maps.Geocoder();
												geocoder.geocode({
													address: value,
													region: jQuery.region
												}, function (result, format) {
													if (format == 'OK') {
														if (!result[0] || !result[0].geometry) {
															return;
														}
	
														END_MAPDIV.setCenter(result[0].geometry.location);
														END_MAPDIV_MARKER.setPosition(result[0].geometry.location);
	
														jQuery('#end-map-location-latitude'+widget_type).val(result[0].geometry.location.lat());
														jQuery('#end-map-location-longitude'+widget_type).val(result[0].geometry.location.lng());
													}
												});
											});
											jQuery('#btn-end-map-location'+widget_type).on('click', function () {
												var getPosition = END_MAPDIV_MARKER.getPosition();
												END_MAPDIV.panTo(getPosition);
	
												jQuery('#end-map-location-latitude'+widget_type).val(getPosition.lat());
												jQuery('#end-map-location-longitude'+widget_type).val(getPosition.lng());
	
												var geocoder = new google.maps.Geocoder();
												geocoder.geocode({
													location: getPosition,
													region: jQuery.region
												}, function (result, format) {
													if (format == 'OK') {
														if (!result[0] || !result[0].geometry) {
															return;
														}
	
														jQuery('#end-map-location'+widget_type).val(result[0].formatted_address);
													}
												});
											});";
	
										   $html .= "var END_MAP_AUTOCOMPLETE = new google.maps.places.Autocomplete(document.getElementById('end-map-location'+widget_type), {
												componentRestrictions: {
													country: jQuery.region
												}
											});
											END_MAP_AUTOCOMPLETE.addListener('place_changed', function () {
												var place = END_MAP_AUTOCOMPLETE.getPlace();
												if (!place || !place.geometry) {
													console.log(\"Autocomplete's returned place contains no geometry\");
													return;
												}
	
												END_MAPDIV.setCenter(place.geometry.location);
												END_MAPDIV_MARKER.setPosition(place.geometry.location);
											});
	
											var END_MAPDIV_LATLNG = new google.maps.LatLng(51.498, -0.126);
											if (jQuery('#end-map-location-latitude'+widget_type).val().trim() && jQuery('#end-map-location-longitude'+widget_type).val().trim()) {
												END_MAPDIV_LATLNG = new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val());
											}
	
											END_MAPDIV = new google.maps.Map(document.getElementById('end-mapdiv-location'+widget_type), {
												center: END_MAPDIV_LATLNG,
												zoom: 8,
												mapTypeId: google.maps.MapTypeId.ROADMAP,
												streetViewControl: false
											});
											END_MAPDIV.controls[google.maps.ControlPosition.TOP_RIGHT].push(new FullScreenControl(END_MAPDIV));
	
											var END_MAPDIV_MARKER = new google.maps.Marker({
												position: END_MAPDIV_LATLNG,
												map: END_MAPDIV,
												draggable: true
											});
											google.maps.event.addListener(END_MAPDIV_MARKER, 'dragend', function () {
												jQuery('#btn-end-map-location'+widget_type).trigger('click');
											});
											
											google.maps.event.addDomListener(window, 'resize', function() {
												var center = END_MAPDIV.getCenter();
												google.maps.event.trigger(END_MAPDIV, 'resize');
												END_MAPDIV.setCenter(center); 
											});
	
											var tz = moment([jQuery('#start-date'+widget_type).val(), jQuery('#start-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
											tz.format('DD-MM-YYYY');
											tz.format('H:mm');
											jQuery('#pickup-date'+widget_type).val(tz.format('DD-MM-YYYY'));
											jQuery('#pickup-date'+widget_type).datepicker({
												orientation: 'left',
												autoclose: true,
												dateFormat: 'dd-mm-yy'
											}).on('changeDate', function () {
												if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
													var tz = moment([jQuery('#pickup-date'+widget_type).val(), jQuery('#pickup-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
													if (tz.isAfter(moment([jQuery('#return-date'+widget_type).val(), jQuery('#return-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm'))) {
														jQuery('#return-date'+widget_type).datepicker('update', tz.format('DD-MM-YYYY'));
														jQuery('#return-time'+widget_type).timepicker('setTime', tz.format('H:mm'));
													}
												}
	
												jQuery('#form-quotation-request'+widget_type).valid();
											});
											jQuery('#pickup-time'+widget_type).timepicker({
												minuteStep: 5,
												showMeridian: false,
												defaultTime: tz.format('H:mm')
											}).on('hide.timepicker', function (e) {
												jQuery('#pickup-date'+widget_type).trigger('changeDate');
	
												var value = e.time.minutes % 5;
												if (value != 0) {
													jQuery('#pickup-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
												}
											});
	
											jQuery('#return-journey-needed'+widget_type).on('click', function () {
												var element = jQuery('#form-group-return'+widget_type);
												if (jQuery(this).prop('checked')) {
													var tz = moment([jQuery('#pickup-date'+widget_type).val(), jQuery('#pickup-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
													tz.format('DD-MM-YYYY');
													tz.format('H:mm');
													jQuery('#return-date'+widget_type).val(tz.format('DD-MM-YYYY'));
													jQuery('#return-date'+widget_type).datepicker({
														orientation: 'left',
														autoclose: true,
														dateFormat: 'dd-mm-yy'
													}).on('changeDate', function () {
														jQuery('#pickup-date'+widget_type).trigger('changeDate');
	
														jQuery('#form-quotation-request'+widget_type).valid();
													});
													jQuery('#return-time'+widget_type).timepicker({
														minuteStep: 5,
														showMeridian: false,
														defaultTime: tz.format('H:mm')
													}).on('hide.timepicker', function (e) {
														jQuery('#pickup-date'+widget_type).trigger('changeDate');
	
														var value = e.time.minutes % 5;
														if (value != 0) {
															jQuery('#return-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
														}
													});
	
													element.slideDown();
												} else {
													element.slideUp();
												}
											});";
											
							if(get_option('transporters_return_journey_'.$widget_id) == 1){				
											
							  $html .= "
											jQuery('#return-journey-needed'+widget_type).attr('checked','checked');
											
											var element = jQuery('#form-group-return'+widget_type);
												if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
													var tz = moment([jQuery('#pickup-date'+widget_type).val(), jQuery('#pickup-time'+widget_type).val()].join(' '), 'DD-MM-YYYY H:mm');
													tz.format('DD-MM-YYYY');
													tz.format('H:mm');
													jQuery('#return-date'+widget_type).val(tz.format('DD-MM-YYYY'));
													jQuery('#return-date'+widget_type).datepicker({
														orientation: 'left',
														autoclose: true,
														dateFormat: 'dd-mm-yy'
													}).on('changeDate', function () {
														jQuery('#pickup-date'+widget_type).trigger('changeDate');
	
														jQuery('#form-quotation-request'+widget_type).valid();
													});
													jQuery('#return-time'+widget_type).timepicker({
														minuteStep: 5,
														showMeridian: false,
														defaultTime: tz.format('H:mm')
													}).on('hide.timepicker', function (e) {
														jQuery('#pickup-date'+widget_type).trigger('changeDate');
	
														var value = e.time.minutes % 5;
														if (value != 0) {
															jQuery('#return-time'+widget_type).timepicker('setTime', [e.time.hours, e.time.minutes - value].join(':'));
														}
													});
	
													element.slideDown();
												} else {
													element.slideUp();
												}";	
							}
	
							  $html .= "jQuery('#form-group-input'+widget_type).children().remove();
											jQuery.each(data.input, function (groupName, inputMultiple) {
												//console.log(groupName, inputMultiple);
												if (typeof inputMultiple != 'undefined' && typeof inputMultiple[0] != 'undefined') {
													var append = '<div class=\"form-horizontal\"><h4 id=\"group-' + inputMultiple[0].group + '\" class=\"no-margin\" style=\"font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #959595; font-weight: 700;\">' + groupName + '<small> (' + data.max[inputMultiple[0].group].toFixed(2) + ')</small></h4>';
													jQuery.each(inputMultiple, function (index, value) {
														append += '<div class=\"form-group form-group-sm\"><label for=\"input-field-' + value.type + '\" class=\"col-xs-5 control-label\">' + value.name + '</label><div class=\"col-xs-7\"><input data-group=\"' + value.group + '\" data-min=\"' + value.min + '\" data-max=\"' + value.count + '\" data-type=\"' + value.type + '\" data-rate=\"' + value.rate + '\" type=\"text\" name=\"input[' + value.type + ']\" id=\"input-' + value.type + widget_type +'\" class=\"form-control input\" value=\"0\"></div></div>';
													});
													append += '</div>';
													jQuery('#form-group-input'+widget_type).append(append);
												}
											});";
											
								$html .=  "jQuery.each(data.max, function (groupSlug, maxRate) {
												jQuery.each(data.input, function (groupName, inputMultiple) {
													jQuery.each(inputMultiple, function (index, value) {
														var element = jQuery('input[data-type=\"' + value.type + '\"]');
														if (typeof element != 'undefined' && element.data('group') == groupSlug) {
															var inputRate = parseFloat(element.data('rate'));
															if (isNaN(inputRate)) inputRate = 0;
															element.attr('data-max', maxRate / inputRate);
														}
													});
												});
											});
											jQuery('#vehicleType'+widget_type).children().remove();
											jQuery('#vehicleType'+widget_type).append(jQuery('<option>', {
												value: '',
												text: '".__('Select vehicle type','transportersio')."',
												disabled: true,
												selected: true,
												'class': 'disabled'
											}));
											jQuery.each(data.vehicleType, function (index, value) {
												jQuery('#vehicleType'+widget_type).append(jQuery('<option>', value));
											});
											jQuery('label[for=\"vehicleType\"]').children('span')
													.children('small')
													.text(' (' + (jQuery('#vehicleType'+widget_type).children().length - jQuery('#vehicleType'+widget_type).children('.disabled, .hide').length) + ')');
													
											if (typeof data.journeyType !== 'undefined' && data.journeyType.length > 0) {
													jQuery('#form-group-journeyType'+widget_type).show();
													jQuery('#journeyType'+widget_type).children().remove();
													jQuery('#journeyType'+widget_type).append(jQuery('<option>', {
														value: '',
														text: '".__('Select journey type','transportersio')."',
														disabled: true,
														selected: true,
														'class': 'disabled'
													}));
													jQuery.each(data.journeyType, function (index, value) {
														jQuery('#journeyType'+widget_type).append(jQuery('<option>', value));
													});
												}		
	
	
											jQuery('.input').TouchSpin({
												buttondown_class: 'btn btn-sm btn-primary',
												buttonup_class: 'btn btn-sm btn-primary'
											});
											jQuery('.input').on('change', function () {
												jQuery.each(data.max, function (groupSlug, maxRate) {
													var number = [];
													var amount = 0;
													jQuery.each(data.input, function (groupName, inputMultiple) {
														jQuery.each(inputMultiple, function (index, value) {
															var input = jQuery('input[data-type=\"' + value.type + '\"]');
															if (typeof input != 'undefined' && input.data('group') == groupSlug) {
																var type = parseInt(input.data('type'));
																if (isNaN(type)) type = 0;
	
																var rate = parseFloat(input.data('rate'));
																if (isNaN(rate)) rate = 0;
	
																var val = parseInt(input.val());
																if (isNaN(val)) val = 0;
	
																number[type] = val * rate;
																amount += number[type];
															}
														});
													});
													jQuery.each(data.input, function (groupName, inputMultiple) {
														jQuery.each(inputMultiple, function (index, value) {
															var input = jQuery('input[data-type=\"' + value.type + '\"]');
															if (typeof input != 'undefined' && input.data('group') == groupSlug) {
																var type = parseInt(input.data('type'));
																if (isNaN(type)) type = 0;
	
																var rate = parseFloat(input.data('rate'));
																if (isNaN(rate)) rate = 0;
	
																var val = parseInt(input.val());
																if (isNaN(val)) val = 0;
	
																var fixed = parseFloat((maxRate - amount).toFixed(2));
																var toFixed = parseFloat((fixed / rate).toFixed(2));
																var available = parseInt(toFixed + val);
	
																input.attr('data-max', available);
																input.trigger(\"touchspin.updatesettings\", {max: available});
															}
														});
													});
	
													var labelSmall = ( maxRate - amount );
													jQuery('#group-' + groupSlug).children('small').html(' (' + labelSmall.toFixed(2) + ')');
												});";
	
									  $html .= "var rules = [];
												jQuery.each(data.input, function (groupName, inputMultiple) {
													jQuery.each(inputMultiple, function (index, value) {
														var input = jQuery('input[data-type=\"' + value.type + '\"]');
														if (typeof input != 'undefined') {
															var group = input.data('group');
															if (typeof group != 'undefined') {
																if (typeof rules[group] == 'undefined') {
																	rules[group] = 0;
																}
	
																var rate = input.data('rate');
																if (isNaN(rate)) rate = 0;
	
																var val = input.val();
																if (isNaN(val)) val = 0;
	
																rules[group] += val * rate;
															}
														}
													});
												});";
												
									 $html .=   "jQuery('#vehicleType'+widget_type).children().each(function (index, value) {
													jQuery(value).removeClass('hide');
												});
												jQuery('#vehicleType'+widget_type).children().each(function (index, value) {
													var input = jQuery(value);
													if (typeof input.data() != 'undefined') {
														jQuery.each(input.data(), function (key, value) {
															if (rules[key] > value) {
																if (jQuery('#vehicleType'+widget_type).val() == input.val()) {
																	jQuery('#vehicleType'+widget_type).val('');
																}
																input.addClass('hide');
															}
														});
													}
												});";
	
									 $html .=  "jQuery('label[for=\"vehicleType\"]').children('span')
														.children('small')
														.text(' (' + (jQuery('#vehicleType'+widget_type).children().length - jQuery('#vehicleType'+widget_type).children('.disabled, .hide').length) + ')');
											});";
	
									 $html .= "jQuery('#form-quotation-request'+widget_type).validate({
												errorElement: 'span',
												errorClass: 'help-block help-block-error',
												focusInvalid: false,
												ignore: '',
												rules: {
													start_map_location: {
														required: true
													},
													end_map_location: {
														required: true
													},
													pickup_date: {
														required: true
													},
													pickup_time: {
														required: true
													},
													return_journey_needed: {
														required: false,
													},
													return_date: {
														required: false
													},
													return_time: {
														required: false
													},
													vehicle_type_id: {
														required: true
													},
													contact_name: {
														required: true
													},
													contact_email: {
														required: true,
														email: true
													},
													mobile_number: {
														required: true
													}
												},
												messages: {
													start_map_location: '".__('This field is required.','transportersio')."',
													end_map_location: '".__('This field is required.','transportersio')."',
													pickup_date: '".__('This field is required.','transportersio')."',
													pickup_time: '".__('This field is required.','transportersio')."',
													vehicleType: '".__('This field is required.','transportersio')."',
													contact_name: '".__('This field is required.','transportersio')."',
													contact_email: '".__('This field is required.','transportersio')."',
													mobile_number: '".__('This field is required.','transportersio')."'
							
												},
												highlight: function (element) {
													var input = jQuery(element);
													if (input.attr('name') == 'start_map_location' || input.attr('name') == 'end_map_location') {
														input.parent().children('.input-group-btn').find('.btn').removeClass('btn-default').addClass('btn-danger');
													}
													input.closest('.form-group').addClass('has-error');
												},
												unhighlight: function (element) {
													var input = jQuery(element);
													if (input.attr('name') == 'start_map_location' || input.attr('name') == 'end_map_location') {
														input.parent().children('.input-group-btn').find('.btn').removeClass('btn-danger').addClass('btn-default');
													}
													input.closest('.form-group').removeClass('has-error');
												},
												success: function (label) {
													label.closest('.form-group').removeClass('has-error');
												},
												errorPlacement: function (error, element) {
													var input = jQuery(element);
													if (input.attr('name') == 'start_map_location' || input.attr('name') == 'end_map_location') {
														input.parent().children('.input-group-btn').find('.btn').removeClass('btn-default').addClass('btn-danger');
														error.insertAfter(input.closest('.form-group').children('.mapdiv'));
													} else {
														error.insertAfter(input);
													}
												},
												submitHandler: function () {
													if(jQuery('#return-journey-needed'+widget_type).attr('checked')){
														if( (jQuery('#pickup-date'+widget_type).val() == jQuery('#return-date'+widget_type).val()) &&  (jQuery('#pickup-time'+widget_type).val() == jQuery('#return-time'+widget_type).val()))
														{
															alert('".__('Your return time is the same as outbound time. Please change your return time or unselect the return journey.','transportersio')."');
															return;
														}
													}
													
													jQuery.fn.goToPanelThankYou = function () {
														var form = jQuery('#form-quotation-request'+widget_type);
														var element = form.closest('.panel-body');
														jQuery.ajax({
															url: siteURL+'api/v1/quote-form/store',
															type: 'POST',
															data: jQuery('#form-get-a-quote'+widget_type+', #form-quotation-request'+widget_type+', #form-thank-you'+widget_type).serialize(),
															beforeSend: function (xhr) {
																element.block({
																	message: '<i class=\"fa fa-spinner fa-pulse\"></i>',
																	css: {
																		border: 'inherit !important',
																		backgroundColor: 'inherit !important'
																	}
																});
															}
														}).done(function (data, textStatus, jqXHR) {
															if (data.result == 'ok') {";
															
															$html .= "jQuery.ajax({
																		url: transporters_ajax_object.ajax_url,
																		data:{
																			action : 'get_stage',
																			stage : 'after2',
																			widget_id : '".$widget_id."'
																		},
																		success:function(result){
																			jQuery('body').append(result);	
																		}
																	});";
															
														 $html .= "element.fadeOut(function () {
																
																	var html_thank_you = jQuery('#html_quote_form_confirmation'+widget_type).html();
																	if (typeof data.replacePairs !== 'undefined') {
																		jQuery.each(data.replacePairs, function (key, value) {
																			html_thank_you = html_thank_you.replace(key, value);
																		});
																	}
																	jQuery('#html_quote_form_confirmation'+widget_type).html(html_thank_you);
																	
																	jQuery('#panel-thank-you'+widget_type).fadeIn(function () {
																	});
																});
															}
														}).fail(function (jqXHR, textStatus, errorThrown) {
															jQuery('#alert-quotation-request'+widget_type).empty();
															if (typeof jqXHR.responseJSON === 'undefined') {
																jqXHR.responseJSON = [['Sorry, Internal Server Error.']];
															}
	
															jQuery.each(jqXHR.responseJSON, function (key, rules) {
																jQuery.each(rules, function (index, rule) {
																	jQuery('#alert-quotation-request'+widget_type).append('<div class=\"alert alert-danger alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>' + rule + '</strong></div>');
																});
															});
														}).always(function () {
															element.unblock();
														});
													};";
	
									   $html .=  "var START_MAPDIV_MARKER_POSITION = START_MAPDIV_MARKER.getPosition();
													jQuery('#start-map-location-latitude'+widget_type).val(START_MAPDIV_MARKER_POSITION.lat());
													jQuery('#start-map-location-longitude'+widget_type).val(START_MAPDIV_MARKER_POSITION.lng());
	
													var END_MAPDIV_MARKER_POSITION = END_MAPDIV_MARKER.getPosition();
													jQuery('#end-map-location-latitude'+widget_type).val(END_MAPDIV_MARKER_POSITION.lat());
													jQuery('#end-map-location-longitude'+widget_type).val(END_MAPDIV_MARKER_POSITION.lng());
	
													jQuery('#form-thank-you'+widget_type).empty();
													
													getDistances(widget_type,function(){
														jQuery(this).goToPanelThankYou();
													});
												}
											});
											jQuery('#btn-quotation-request'+widget_type).prop('disabled', false)
													.on('click', function () {
														jQuery('#form-quotation-request'+widget_type).valid();
													});
											jQuery('#btn-back-get-quote'+widget_type).prop('disabled', false)
													.on('click', function () { ";
													
													$html .= "jQuery.ajax({
																url: transporters_ajax_object.ajax_url,
																data:{
																	action : 'get_stage',
																	stage : 'back',
																	widget_id : '".$widget_id."'
																},
																success:function(result){
																	jQuery('body').append(result);	
																}
															});";
													
											   $html .= "var element = jQuery(this).closest('.panel-body');
														element.block({
															message: '<i class=\"fa fa-spinner fa-pulse\"></i>',
															css: {
																border: 'inherit !important',
																backgroundColor: 'inherit !important'
															}
														});
														element.unblock().fadeOut(function () {
															jQuery('#panel-get-a-quote'+widget_type).fadeIn(function () {
																//
															});
														});
													});
										});
									});
								}).fail(function (jqXHR, textStatus, errorThrown) {
									jQuery('#alert-get-a-quote'+widget_type).empty();
									jQuery.each(jqXHR.responseJSON, function (key, rules) {
										jQuery.each(rules, function (index, rule) {
											jQuery('#alert-get-a-quote'+widget_type).append('<div class=\"alert alert-danger alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>' + rule + '</strong></div>');
										});
									});
								}).always(function () {
									element.unblock();
								});
							}
						});
						jQuery('#btn-get-a-quote'+widget_type).prop('disabled', false)
								.on('click', function () {
									jQuery('#form-get-a-quote'+widget_type).valid();
								});
					}
				}
				
			});
				
				jQuery(\"body\").on('change', '#vehicleType'+widget_type, function () {
					getDistances(widget_type);
				});
				
			var tz = moment().tz('Europe/London');
			
	function getDistances(widget_type,success){
			
			jQuery('#transport-distance'+widget_type).remove();
			jQuery('#transport-duration'+widget_type).remove();
			jQuery('#return-distance'+widget_type).remove();
			jQuery('#return-duration'+widget_type).remove();
				   
			var element = jQuery('#vehicleType'+widget_type+' option:selected');
			var TYPE = element.attr('type');
			switch (TYPE) {
				case 'google-driving':
					var directionsService = new google.maps.DirectionsService();
															
					if( typeof( jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat') ) == 'undefined' ){
																								  
						var request = {
							origin: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
							destination: new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
							travelMode: google.maps.TravelMode.DRIVING
						};
					}else{
								//console.log('do base2base price');
						var request = {
							origin: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
							waypoints: [{
								location: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
								stopover: false
							},{
								location: new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
								stopover: false
							}],
							destination: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
							travelMode: google.maps.TravelMode.DRIVING
						};
						//[[TODO - account for wait and stay returns]]
					}				
						
					directionsService.route(request , function (result, format) {
						if (format == google.maps.DirectionsStatus.OK) {					    
							jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
								type: 'hidden',
								name: 'transport_distance',
								id: 'transport-distance'+widget_type,
								value: result.routes[0].legs[0].distance.value
							}));
							jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
								type: 'hidden',
								name: 'transport_duration',
								id: 'transport-duration'+widget_type,
								value: result.routes[0].legs[0].duration.value
							}));
	
							if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
								var directionsService = new google.maps.DirectionsService();
								
								if( typeof( jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat') ) == 'undefined' ){
																								  
									var request = {
										origin: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
										destination: new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
										travelMode: google.maps.TravelMode.DRIVING
									};
								}else{
											//console.log('do base2base price');
									var request = {
										origin: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
										waypoints: [{
											location: new  google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val()),
											stopover: false
										},{
											location: new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val()),
											stopover: false
										}],
										destination: new google.maps.LatLng(jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lat'), jQuery('#vehicleType'+widget_type+' option:selected').attr('base_location_lng')),
										travelMode: google.maps.TravelMode.DRIVING
									};
									//[[TODO - account for wait and stay returns]]
								}
					
					
								directionsService.route(request, function (result, format) {
									if (format == google.maps.DirectionsStatus.OK) {
										jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
											type: 'hidden',
											name: 'return_distance',
											id: 'return-distance'+widget_type,
											value: result.routes[0].legs[0].distance.value
										}));
										jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
											type: 'hidden',
											name: 'return_duration',
											id: 'return-duration'+widget_type,
											value: result.routes[0].legs[0].duration.value
										}));
										
										if(typeof success !== 'undefined'){
											jQuery(this).goToPanelThankYou();
										}
									}
								});
							} else {
								if(typeof success !== 'undefined'){
									jQuery(this).goToPanelThankYou();
								}
							}
						}
					});
					break;
				case 'straight-line':
						var UNIT = element.attr('unit');
						var PER_HOUR = element.attr('per_hour');
	
						var UNIT_RATE = 0;
						switch (UNIT) {
							case 'km':
								UNIT_RATE = 1000;
								break;
							case 'mile':
								UNIT_RATE = 1609.34;
								break;
							case 'nmi':
								UNIT_RATE = 1852;
								break;
							default:
								UNIT_RATE = 1;
								break;
						}
	
						var from = new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val());
						var to = new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val());
						var computeDistanceBetween = google.maps.geometry.spherical.computeDistanceBetween(from, to);
						jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
							type: 'hidden',
							name: 'transport_distance',
							id: 'transport-distance'+widget_type,
							value: computeDistanceBetween
						}));
						jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
							type: 'hidden',
							name: 'transport_duration',
							id: 'transport-duration'+widget_type,
							value: ( (computeDistanceBetween / UNIT_RATE) / PER_HOUR ) * 60 * 60
						}));
	
						if (jQuery('#return-journey-needed'+widget_type).prop('checked')) {
							var from = new google.maps.LatLng(jQuery('#end-map-location-latitude'+widget_type).val(), jQuery('#end-map-location-longitude'+widget_type).val());
							var to = new google.maps.LatLng(jQuery('#start-map-location-latitude'+widget_type).val(), jQuery('#start-map-location-longitude'+widget_type).val());
							var computeDistanceBetween = google.maps.geometry.spherical.computeDistanceBetween(from, to);
	
							jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
								type: 'hidden',
								name: 'return_distance',
								id: 'return-distance'+widget_type,
								value: computeDistanceBetween
							}));
							jQuery('#form-thank-you'+widget_type).append(jQuery('<input>', {
								type: 'hidden',
								name: 'return_duration',
								id: 'return-duration'+widget_type,
								value: ( (computeDistanceBetween / UNIT_RATE) / PER_HOUR ) * 60 * 60
							}));
							
							if (typeof success !== 'undefined') {
								jQuery(this).goToPanelThankYou();
							}
							
						} else {
							if (typeof success !== 'undefined') {
								jQuery(this).goToPanelThankYou();
							}
						}
												
					break;
				default:
						window.alert('".__('Please select other vehicle type','transportersio')."');
					break;
			}                        
	
		}
	}
	</script>
	";
}
?>
