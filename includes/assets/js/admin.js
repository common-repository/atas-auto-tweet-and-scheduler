(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
$(document).ready(function() {
    
	// hide spinner
	$(".ataswp-spinner-img").hide();
	
	// tooltip, display dialog box on mouse over
	// source: http://www.codechewing.com/library/create-simple-tooltip-jquery/
	$(".ataswp-tooltip").hover(function(e) {
		
		var titleText = $(this).attr('title');
		//alert(titleText);
		
		$(this)
		  .data('tiptext', titleText)
		  .removeAttr('title');
		
		$('<p class="ataswp-tooltip-display"></p>')
		.text(titleText)
		.appendTo('body')
		.css('top', (e.pageY - 10) + 'px')
		.css('left', (e.pageX + 20) + 'px')
		.fadeIn('slow');
		
		}, function(){ // Hover off event
		
		$(this).attr('title', $(this).data('tiptext'));
		$('.ataswp-tooltip-display').remove();
		
		}).mousemove(function(e){ // Mouse move event
		
		$('.ataswp-tooltip-display')
		  .css('top', (e.pageY - 10) + 'px')
		  .css('left', (e.pageX + 20) + 'px');
	
	});
	
	// admin twitter tweet post
	$('#ataswp-twitter-tweet-box-form').submit(twitterTweetSubmit);
	
	function twitterTweetSubmit(){
	
	// empty div before process
    $('.twitter-tweet-response').empty();
	
	  var formData = $(this).serialize();
	
	  $.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'admin_post_tweet', formData:formData},
			success:function(data){
				
				$('.twitter-tweet-response').show().prepend( data );
				// fade out
				$('.twitter-tweet-response').delay(12000).fadeOut(800);
			
			}
	  });
	
	return false;
	}
	
	// char counter tweet box
	$('#twitter_tweet_message').keyup(function () {
	  var max = 115;
	  var len = $(this).val().length;
	  if (len >= max) {
		$('#twitter_char_count').text(' 0 ');
	  } else {
		var char = max - len;
		$('#twitter_char_count').text(char + ''); // characters left
	  }
	});
	
	// admin twitter app connect account
	$('#ataswp-twitter-connect-app-form').submit(ataswpTwitterAppConnectAccSubmit);
	
	function ataswpTwitterAppConnectAccSubmit(){
	
	// empty div before process
    $('.twitter-app-return-data').empty();
	
	  var formData = $(this).serialize();
	
	  $.ajax({
		type:"POST",
		dataType: 'json',
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'twitter_app_connect_account', formData:formData},
			success:function(data){
							
				// success - redirect (refress)
				if (data.success == true){
					document.location.href = data.authorization_url; 
				} else {
					$('.twitter-app-return-data').show().prepend( data.message );
					// fade out
					$('.twitter-app-return-data').delay(12000).fadeOut(800);
				}

				// test
				//$('.twitter-app-return-data').show().prepend( data.message );
			
			}
	  });
	
	return false;
	}
	
	// admin twitter app disconnect account
	$('#ataswp-twitter-disconnect-app-form').submit(ataswpTwitterAppDisconnectAccSubmit);
	
	function ataswpTwitterAppDisconnectAccSubmit(){
	
	// empty div before process
    $('.twitter-app-return-data').empty();
	
	  var formData = $(this).serialize();
	
	  $.ajax({
		type:"POST",
		dataType: 'json',
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'twitter_app_disconnect_account', formData:formData},
			success:function(data){

				// success - redirect (refress)
				if (data.success == true){
					document.location.reload(); // reload page
				} else {
					$('.twitter-app-return-data').show().prepend( data.message );
					// fade out
					$('.twitter-app-return-data').delay(12000).fadeOut(800);
				}
			
			}
	  });
	
	return false;
	}
	
	// admin run_importer
	$('#ataswp-run-importer-form').submit(ataswpRunImporterSubmit);
	
	function ataswpRunImporterSubmit(){
	
		// empty div before process
		$('.run-importer-response').empty();
		
		var formData = $(this).serialize();
		
		// spinner
		$(".ataswp-spinner-img").show();
		var spinner_img = ataswp_admin.ataswp_spinner_img; // ajax wp_localize_script
		$('.ataswp-spinner-img').html('<img src="' + spinner_img + '" width="15" height="15" />');
		$('#ataswp-run-importer-form-submit').attr('disabled', 'disabled'); // disable submit button after form submit
	
		$.ajax({
			type:"POST",
			dataType: 'json',
			url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			data: {action: 'run_importer', formData:formData},
				success:function(data){
					
					$('#ataswp-run-importer-form-submit').attr("disabled", false); // re-enable the submit button
					$(".ataswp-spinner-img").hide();
					
					//alert(JSON.stringify(data));// alert json data
					$('.run-importer-response').show().prepend( data.message ); // data.message
					// fade out
					$('.run-importer-response').delay(59000).fadeOut(800);
				
				}
		});
	
	return false;
	}

	// Scheduler remove post 
	$("#ataswp-posts-list-table").on("click", ".ataswp-remove-page", function (event) {
		//alert('clicked');
		var getthis = $(this); //Store the context of this in a local variable 
		var post_id  = getthis.attr('data-post-id');

		var post_data = 'post_id=' + post_id;
		$.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'remove_page', formData:post_data},
			success:function(response){
				// remove closest row
				//alert(response);
				getthis.closest("tr").remove(); // remove tr
				$("#remove-hidden-tr-" + post_id).remove();
			}
		});
		return false;
		
	});
	
	// Scheduler settings page
	$('#ataswp-scheduler-settings-form').submit(SchedulerSettingsSubmit);
	
	function SchedulerSettingsSubmit(){
	
		// empty div before process
		$('.show-return-data').empty();
		
		var formData = $(this).serialize();
	  
		// spinner
		$(".ataswp-spinner-img").show();
		var spinner_img = ataswp_admin.ataswp_spinner_img; // ajax wp_localize_script
		$('.ataswp-spinner-img').html('<img src="' + spinner_img + '" width="15" height="15" />');
		$('#ataswp-scheduler-settings-form-submit').attr('disabled', 'disabled'); // disable submit button after form submit
	
		$.ajax({
			type:"POST",
			url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			data: {action: 'scheduler_settings', formData:formData},
				success:function(data){
					
					$('#ataswp-scheduler-settings-form-submit').attr("disabled", false); // re-enable the submit button
					$(".ataswp-spinner-img").hide();
					
					$('.show-return-data').show().prepend( data );
					// fade out
					$('.show-return-data').delay(12000).fadeOut(800);
				
				}
		});
	
	return false;
	}
	
	// admin Auto Post settings page
	$('#ataswp-auto-post-settings-form').submit(AutoPostSettingsSubmit);
	
	function AutoPostSettingsSubmit(){
	
	// empty div before process
    $('.show-return-data').empty();
	
	  var formData = $(this).serialize();
	
	  $.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'auto_post_settings', formData:formData},
			success:function(data){
				
				$('.show-return-data').show().prepend( data );
				// fade out
				$('.show-return-data').delay(12000).fadeOut(800);
			
			}
	  });
	
	return false;
	}
	
	// live clock
	// do not use 500 interval as it will makes the server very slow
	setInterval(ataswpLiveClock,60 * 1000); // run every minutes
	function ataswpLiveClock(){
	  var formData = '';
	  $.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'live_clock' , formData:formData},
			success:function(data){
				 /*
				 data = data.split(':');
				 $('#ataswp-live-clock #hours').html(data[0]);
				 $('#ataswp-live-clock #minutes').html(data[1]);
				 $('#ataswp-live-clock #seconds').html(data[2]);
				 var clock = '<span id="hours">0</span>:<span id="minutes">0</span>:<span id="seconds">0</span>';
				 */
				 $('.ataswp-live-clock').html(data);
			}
	  });
	
	return false;
	}
	
	// reload content without refresh
	function reload_content_without_refresh() {     
		//alert("Boom!");
		$('body').load(window.location.href,'body');
		return false;
	};
	
	// Importer - enable/disable time
	$("#ataswp-run-importer-form").on("click", ".ataswp-scheduler-enable-cron-time-checkbox", function (event) {
		if(this.checked) {
		   //alert('checked');
		   $( "#display-cron-time-fields").show();
		   $("input.ataswp-scheduler-enable-cron-time-checkbox").val('1'); // set value to 1
		} else {
		   //alert('unchecked');
		   $( "#display-cron-time-fields" ).hide();
		   $("input.ataswp-scheduler-enable-cron-time-checkbox").val(''); // empty value
		}
	});
	
	// scheduler table
	$(".show-hiden-row").hide();
	$('#ataswp-scheduler-form .toggleContent').on('click', function(event) {
		var collapse = $(this).attr('id');					
        //alert(collapse);
		// zero = no animation
		$(collapse).slideToggle(0)(function(){
		});
	});
	
	// Scheduler - add time
	$("#ataswp-scheduler-form").on("click", ".ataswp-scheduler-add-time", function (event) {
														 
		var post_id = this.id;
		
		$('.scheduler-hour-and-minute-clone .ataswp_scheduler_hours_class').attr('name', 'hours_' + post_id + '[]');
		$('.scheduler-hour-and-minute-clone .ataswp_scheduler_minutes_class').attr('name', 'minutes_' + post_id + '[]');
														
		var row = $( '.scheduler-hour-and-minute-clone' ).clone(true);
		row.removeClass( 'scheduler-hour-and-minute-clone' );
		row.addClass( 'scheduler-hour-and-minute' );
		row.insertAfter( '#ataswp-scheduler-cron-time-fields-' + post_id ); // insertBefore or insertAfter
		
		$('.scheduler-hour-and-minute-clone .ataswp_scheduler_hours_class').attr('name', '');
		$('.scheduler-hour-and-minute-clone .ataswp_scheduler_minutes_class').attr('name', '');
		
	});
	
	// Scheduler - remove time
	$("#ataswp-scheduler-form").on("click", ".ataswp-scheduler-remove-time", function (event) {
		$(this).parents('.scheduler-hour-and-minute').remove();
		return false;
	});
	
	// Importer - add time
    $('.ataswp-importer-add-time').on('click', function(event) {
		
		$('.importer-hour-and-minute-clone .ataswp_importer_hours_class').attr('name', 'hours[]');
		$('.importer-hour-and-minute-clone .ataswp_importer_minutes_class').attr('name', 'minutes[]');
														
		var row = $( '.importer-hour-and-minute-clone' ).clone(true);
		row.removeClass( 'importer-hour-and-minute-clone' );
		row.addClass( 'importer-hour-and-minute' );
		row.insertAfter( '#ataswp-importer-cron-time-fields' ); // insertBefore or insertAfter
		
		$('.importer-hour-and-minute-clone .ataswp_importer_hours_class').attr('name', '');
		$('.importer-hour-and-minute-clone .ataswp_importer_minutes_class').attr('name', '');
		
	});
	
	// Importer - remove time
	$("#ataswp-run-importer-form").on("click", ".ataswp-importer-remove-time", function (event) {
		$(this).parents('.importer-hour-and-minute').remove();
		return false;
	});
	
	// Log - remove single log
	$("#ataswp-logs-list-table").on("click", ".ataswp-delete-single-log", function (event) {
		
		var getthis = $(this); //Store the context of this in a local variable 
		var post_id = this.id;
		//alert(post_id);
		
		var post_data = 'post_id=' + post_id;
		$.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'process_delete_single_log', formData:post_data},
			success:function(response){
				// remove closest row
				//alert(response);
				getthis.closest("tr").remove(); // remove tr
			}
		});
		
		return false;
		
	});
	
	// Log - remove all logs
	$("#ataswp-scheduler-log-content").on("click", ".ataswp-scheduler-reset-log", function (event) {
		
		//alert('clicked');
		
		var post_data = 'delete=' + 'ok';
		$.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'process_delete_all_logs', formData:post_data},
			success:function(response){
				// execute function after .. sec
				setTimeout(reload_content_without_refresh, 500);
			}
		});
		
		return false;
		
	});
	
	// Scheuler - custom post titles checkbox
	$("#ataswp-scheduler-form").on("click", ".ataswp-scheduler-custom-post-titles-checkbox", function (event) {
		var post_id = this.id;
		if(this.checked) {
		   //Do stuff
		   //alert('checked');
		   $( "#display-custom-post-titles-fields-" + post_id ).show();
		   $("input.enable-custom-post-titles-" + post_id).val('1'); // set value to 1
		} else {
		   //alert('unchecked');
		   $( "#display-custom-post-titles-fields-" + post_id ).hide();
		   $("input.enable-custom-post-titles-" + post_id).val(''); // empty value
		}
	});
	
	// Scheuler - add custom post title field
	$("#ataswp-scheduler-form").on("click", ".ataswp-add-custom-post-title", function (event) {
	//$('.ataswp-add-custom-post-title').on('click', function(event) {
																					   
		var post_id = this.id;	
		
		$('.ataswp-custom-post-title-clone .custom_post_title_class').attr('name', 'custom_post_titles_' + post_id + '[]');
														
		var row = $( '.ataswp-custom-post-title-clone' ).clone(true);
		row.removeClass( 'ataswp-custom-post-title-clone' );
		row.addClass( 'ataswp-custom-post-title' );
		row.insertAfter( '#ataswp-scheduler-custom-post-titles-fields-' + post_id ); // insertBefore or insertAfter
		
		$('.ataswp-custom-post-title-clone .custom_post_title_class').attr('name', '');
		
	});
	
	// Scheuler - add custom post title remove field
	$("#ataswp-scheduler-form").on("click", ".ataswp-remove-custom-post-title", function (event) {
		$(this).parents('.ataswp-custom-post-title').remove();
		return false;
	});
	
	// scheduler edit each blog post row submit button
	$("#ataswp-scheduler-form").on("click", ".ataswp-scheduler-form-submit", function (event) {
																				   
		var post_id = this.id;
		//alert(post_id);

		// empty div before process
		$('.response-data-' + post_id).empty();
		  
		  var formData = $('#ataswp-scheduler-form-' + post_id).serialize();
		  
		  $.ajax({
			type:"POST",
			dataType: 'json',
			url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			data: {action: 'scheduler_update_post', formData:formData, postID:post_id},
				success:function(data){
					
					if ( data.success === true ) {
						//window.location.reload();
						$('.response-data-' + post_id).show().prepend( data.message );
						
						 // execute function after .. sec
						setTimeout(reload_content_without_refresh, 1000);
	
						// reset form data at the end
						document.getElementById("#ataswp-scheduler-form").reset(); 
						// reset form data
						//$("#ataswp-scheduler-form")[0].reset();
						
					} else {
						
						$('.response-data-' + post_id).show().prepend( data.message );
						// fade out
						$('.response-data-' + post_id).delay(36000).fadeOut(800);
						
					}
				    
				}
		  });
		  
		
		return false;
	});
	
						   
});

})( jQuery );

jQuery(document).ready(function($) {
	// ### DOM Ready ### run importer select post types
	jQuery( '#ataswp-run-importer-form' ).on( 'click', '.ataswp-post-type', function () {
		var post_type = this.id;
		
		if(jQuery(this).is(":checked")) {
			//alert(post_type);
			// show categories
			jQuery('.ataswp-post-type-categories-' + post_type).show();
			// check all the checkboxes
			//jQuery('.ataswp-post-type-categories-' + post_type).each(function(){ jQuery('.ataswp-check-categories-' + post_type + ' input').prop('checked', true); });
		} else {
			//alert(post_type);
			// hide categories
			jQuery('.ataswp-post-type-categories-' + post_type).hide();
			// uncheck all the checkboxes
			jQuery('.ataswp-post-type-categories-' + post_type).each(function(){ jQuery('.ataswp-check-categories-' + post_type + ' input').prop('checked', false); });
		}
	});									
});

jQuery(document).ready(function($) {
	// ### DOM Ready ### importer check/uncheck all categories under post type
	jQuery(".ataswp-spinner-img-blog-posts").hide();
	jQuery( '#ataswp-run-importer-form' ).on( 'click', '.ataswp-get-post-type', function () {
																	   
		var post_type = this.id;
		var getthis   = jQuery(this);
		
		// empty div before process
		jQuery('#ataswp-blog-posts').empty();
		
		if(jQuery(this).is(":checked")) {
			//alert(post_type);
			// check all the checkboxes
			jQuery('.ataswp-post-type-categories-' + post_type).each(function(){ jQuery('.ataswp-check-categories-' + post_type + ' input').prop('checked', true); });
		} else {
			//alert(post_type);
			// uncheck all the checkboxes
			jQuery('.ataswp-post-type-categories-' + post_type).each(function(){ jQuery('.ataswp-check-categories-' + post_type + ' input').prop('checked', false); });
		}
		
		// Add posts if main category checked
		
		var categories = '';
		
		// create an array from the checked categories and get the values
		categories = jQuery(".ataswp-is-category-checked input:checkbox:checked").map(function(){
		  return jQuery(this).val();
		}).get(); // <----
		
		// spinner
		jQuery(".ataswp-spinner-img-blog-posts").show();
		var spinner_img = ataswp_admin.ataswp_spinner_img; // ajax wp_localize_script
		jQuery('.ataswp-spinner-img-blog-posts').html('<img src="' + spinner_img + '" width="32" height="32" />');

		jQuery.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'display_posts', formData:categories},
			success:function(response){
               //alert(JSON.stringify(response));// alert json data
			   jQuery(".ataswp-spinner-img-blog-posts").hide();
			   //jQuery('#ataswp-blog-posts').show().prepend( response );
			   jQuery('#ataswp-blog-posts').html( response );
			}
		});
		//return false; // don't use it
		

	});									
});

jQuery(document).ready(function($) {
	// ### DOM Ready ### importer, get posts by categories
	jQuery(".ataswp-spinner-img-blog-posts").hide();
	jQuery( '#ataswp-run-importer-form' ).on( 'click', '.ataswp-get-categories', function () {
																		
		// empty div before process
		jQuery('#ataswp-blog-posts').empty();															
																		
		//alert("Boom!");	
		var post_type  = this.id;
		var getthis    = jQuery(this);
		//var categories = getthis.val();
		//alert(categories);	
        
		var categories = '';
		
		// create an array from the checked categories and get the values
		categories = jQuery(".ataswp-is-category-checked input:checkbox:checked").map(function(){
		  return jQuery(this).val();
		}).get(); // <----
		
		// spinner
		jQuery(".ataswp-spinner-img-blog-posts").show();
		var spinner_img = ataswp_admin.ataswp_spinner_img; // ajax wp_localize_script
		jQuery('.ataswp-spinner-img-blog-posts').html('<img src="' + spinner_img + '" width="32" height="32" />');

		jQuery.ajax({
		type:"POST",
		url: ajaxurl, // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		data: {action: 'display_posts', formData:categories},
			success:function(response){
               //alert(JSON.stringify(response));// alert json data
			   jQuery(".ataswp-spinner-img-blog-posts").hide();
			   //jQuery('#ataswp-blog-posts').show().prepend( response );
			   jQuery('#ataswp-blog-posts').html( response );
			}
		});
		//return false; // don't use it

	});									
});

jQuery(document).ready(function($) {
	// ### DOM Ready ### importer check/uncheck all blog posts
	jQuery( '#ataswp-blog-posts' ).on( 'click', '.ataswp-get-blog-post-category', function () {
																	   
		var term_id = this.id;
		var getthis   = jQuery(this);
		
		//alert(term_id);

		if(jQuery(this).is(":checked")) {
			//alert(post_type);
			// check all the checkboxes
			jQuery('.ataswp-check-blog-post-' + term_id).each(function(){ jQuery('.ataswp-check-blog-post-' + term_id + ' input').prop('checked', true); });
		} else {
			//alert(post_type);
			// uncheck all the checkboxes
			jQuery('.ataswp-check-blog-post-' + term_id).each(function(){ jQuery('.ataswp-check-blog-post-' + term_id + ' input').prop('checked', false); });
		}

	});	
});
	
    jQuery(document).ready(function($) {
        //$(".datepicker").datepicker();
		// Date Picker
		$( ".datepicker" ).datepicker({
			dateFormat : "yy-mm-dd" // yy-mm-dd = Y-m-d
		});
    });

// source: http://stackoverflow.com/questions/17668899/how-to-add-the-media-uploader-in-wordpress-plugin
jQuery(document).ready(function($){
								
    var custom_uploader;
    $('#upload_image_button').click(function(e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            console.log(custom_uploader.state().get('selection').toJSON());
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();

    });
	
});

// source: http://stackoverflow.com/questions/17668899/how-to-add-the-media-uploader-in-wordpress-plugin
jQuery(document).ready(function($){
								
    var custom_uploader;
    $('#upload_default_image_button').click(function(e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            console.log(custom_uploader.state().get('selection').toJSON());
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_default_image').val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();

    });
	
});