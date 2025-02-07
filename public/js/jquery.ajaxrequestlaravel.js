/*
@PluginName: Ajax Request - Post specifically for Laravel Form Validation
@FileName: jquery.ajaxpostlaravel.js
@Verion: 1.0
@Author: ICTSC-Programmers
*Note: Make sure to load JQuery first before this plugin. Also different plugins instanciated here must be imported on the page
*/
(function( $ ) {

   $.fn.ajaxRequestLaravel = function(parameters) {
 		
 		var defaults = {
        	form_id: null,
        	method_type: 'POST',
        	action_url: null,
        	data_type: 'json',
        	btn_submit_id: 'submit',
        	btn_submit_text: 'Submit',
        	btn_cancel_id: null,
        	redirect_url: null,
        	msgbox_id: null,
        	show_edit: false,
        	show_edit_data: {},
        	show_edit_to: null,
        	show_result: [],
        	show_result_loader: true,
      };
      var params = $.extend({}, defaults, parameters);

      $.ajaxSetup({
		  headers: { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') }
		});
      var loader = (params.show_result_loader==true) ? '<img src="/images/ajax-loader.gif" style="margin:5px;"/>' : '...';

      function showResult(url_get, show_to, modal=null){
        	if (modal!==null) { $('#'+modal).modal('hide'); }

	       	$('#'+show_to).html('').append('<center>'+loader+'</center>').fadeTo(200,1,function(){
	            $.ajax({
	               type: "GET",
	               url: url_get,
	               cache: false,
	               success: function(result){
	                	// console.log(result);
	                  	$("#"+show_to).html(result);

	                  /*Load Plugins*/
	                  // Jquery-ui Date Picker
							// $('.datepicker').datepicker();
							// $(".datepick-default").datepicker({
							//   dateFormat: "mm/dd/yy",
							//   changeMonth: true,
							//   changeYear: true
							// });
							// $(".datepick-no-prev-month").datepicker({
							//   dateFormat: "mm/dd/yy",
							//   changeMonth: true,
							//   changeYear: true,
							//   minDate: "+0M +0D"
							// });
							// $(".datepick-no-next-month").datepicker({
							//   dateFormat: "mm/dd/yy",
							//   changeMonth: true,
							//   changeYear: true,
							//   maxDate: "+0M +0D"
							// });
		               // Data Table
							// $('.data-table').DataTable();
							// Jquery-Number
							// $(".number-format").number(true,2);
							// Select2
							$('.select2').select2();
	               },
	               error: function(result){
	               	console.log(result);
	               },
	            });
        	});
        	return false;
      }

      function showPostEdit(method, action, req_data, show_to) {
      	$('#'+show_to).html('').append('<center>'+loader+'</center>').fadeTo(200,1,function(){
            $.ajax({
               type: method,
               url: action,
               data: req_data,
               cache: false,
               success: function(result){
                	// console.log(result);
                  	$("#"+show_to).html(result);

                  /*Load Plugins*/
                	// Jquery-ui Date Picker
						// $('.datepicker').datepicker();
						// $(".datepick-default").datepicker({
						//   dateFormat: "mm/dd/yy",
						//   changeMonth: true,
						//   changeYear: true
						// });
						// $(".datepick-no-prev-month").datepicker({
						//   dateFormat: "mm/dd/yy",
						//   changeMonth: true,
						//   changeYear: true,
						//   minDate: "+0M +0D"
						// });
						// $(".datepick-no-next-month").datepicker({
						//   dateFormat: "mm/dd/yy",
						//   changeMonth: true,
						//   changeYear: true,
						//   maxDate: "+0M +0D"
						// });

						// Jquery-Number
						// $(".number-format").number(true,2);

						// Select2
						$('.select2').select2();
               },
               error: function(result){
               	console.log(result);
               },
            });
        	});
        	return false;
      }
 
 		if (params.form_id!=null && params.show_edit==false /*&& params.show_result.length>0*/) {
	      	return this.each( function() {

	        	if (params.form_id/* && params.action_url*/) {
	        		var form = $('#'+params.form_id);
	        		var serialized_form = form.serializeArray();
	        		var method = (params.method_type==null) ? form.attr('method') : params.method_type;
	        		var action = (params.action_url==null) ? form.attr('action') : params.action_url;
	        		console.log(serialized_form);
	        		if (params.msgbox_id) {
	        			$('#'+params.msgbox_id).html('').removeClass();
	        		}

	        		$('.err-handler').remove();

	               $.each(serialized_form, function(k, input_name){
	               // console.log(input_name);
	        			// $('#'+params.form_id+' #'+input_name.name).css({ border : '' });
	        			$('#'+params.form_id+' input[type=text], input[type=radio], input[type=checkbox], input[type=number], input[type=date], select, textarea').css({ border : '' });
	        		});

	        		if (params.btn_cancel_id) {
	    				$('#'+params.form_id+' #'+params.btn_cancel_id).prop('disabled', true);
	    			}
	    			
					$('#'+params.btn_submit_id).html(params.btn_submit_text+'&nbsp;<img src="/images/ajax-loader-1.gif"/>').prop('disabled', true).fadeTo(200, 1, function(){

						$.ajax({
							type: method,
							url: action,
							data: serialized_form,
							dataType: params.data_type,
							success: function(result){
				            console.log(result);
				            if(params.data_type=='json'){

				               if (result.errors) {
				                	// Handle the error message
				                	$.each(result.errors, function (key, value){
				                		var err = $('<span style="font-weight:bold" class="err-handler"></span>').text(value).addClass('text-danger');
				                		key = key.split('.').join('');
				                		if($('#'+params.form_id+' #'.key)){
				                			$('#'+params.form_id+' #'+key).css({ border : '1px solid #a94442' }).first().focus();
				                			$('#'+params.form_id+' #'+key).after(err);
				                		}
				                	});

				                	$('#'+params.btn_submit_id).html(params.btn_submit_text).prop('disabled', false);
				                	$('#'+params.msgbox_id).html('').removeClass();

				               }else if (result.error) {
				               	if (params.msgbox_id) {
				            			$('#'+params.msgbox_id).show().html('<center>'+result.error+'</center>').addClass('alert alert-danger');
				            		}
				            		$('#'+params.btn_submit_id).html(params.btn_submit_text).prop('disabled', false);
				               }else if (result.success) {
				                	// Handle the success message
				                	$('#'+params.btn_submit_id).html(params.btn_submit_text).prop('disabled', false);
				                	if (result.success.length == 2) {
				                		if (params.msgbox_id) {
						                	$('#'+params.msgbox_id).show().html('<center>'+result.success[0]+'</center>').addClass('alert alert-success').fadeTo(800,1,function(){
						                		if (params.redirect_url!=null) {
						                			window.location.href=params.redirect_url+result.success[1];
						                		}else{
						                			if (params.show_result[0]!=null && params.show_result[1]!=null && params.show_result[2]!=null) {
								                		showResult(params.show_result[0], params.show_result[1], params.show_result[2]);
								                	}
						                		}
						                	});
						               }else{
						                	if (params.redirect_url!=null) {
							                	window.location.href=params.redirect_url+result.success[1];
							               }else{
							                	if (params.show_result[0]!=null && params.show_result[1]!=null && params.show_result[2]!=null) {
							                		showResult(params.show_result[0], params.show_result[1], params.show_result[2]);
							                	}
							               }
						               }
				            		}else{
				            			if (params.msgbox_id) {
					            			$('#'+params.msgbox_id).show().html('<center>'+result.success[0]+'</center>').addClass('alert alert-success').fadeTo(500,1,function(){
					            				if (params.redirect_url!=null) {
							                		window.location.href=params.redirect_url;
							                	}else{
								                	if (params.show_result[0]!=null && params.show_result[1]!=null && params.show_result[2]!=null) {
								                		showResult(params.show_result[0], params.show_result[1], params.show_result[2]);
								                	}
								               }
						                	});
					            		}else{
					            			if (params.redirect_url!=null) {
						            			window.location.href=params.redirect_url;
						            		}else{
							                	if (params.show_result[0]!=null && params.show_result[1]!=null && params.show_result[2]!=null) {
							                		showResult(params.show_result[0], params.show_result[1], params.show_result[2]);
							                	}
							                }
					            		}
				            		}
				            	}else if (result.exist) {
				            		$('#'+params.btn_submit_id).html(params.btn_submit_text).prop('disabled', false);
				            		if (params.msgbox_id) {
					                	$('#'+params.msgbox_id).show().html('<center>Data already exist!</center').addClass('alert alert-warning');
					                }

				               }else{
				                	$('#'+params.btn_submit_id).html(params.btn_submit_text).prop('disabled', false);
				                	if (params.msgbox_id) {
					                	$('#'+params.msgbox_id).show().html('<center>Failed!</center>').addClass('alert alert-danger');
					                }
				                	
				               }

				               if (params.btn_cancel_id) {
			        					$('#'+params.form_id+' #'+params.btn_cancel_id).prop('disabled', false);
			        				}

			        			}

				         },
				         error: function(result){
			            	console.log(result);
			            	if(result.status === 422) {
			            		var response = result.responseJSON;
				            	$.each(response.errors, function (key, value){
			                		var err = $('<span style="font-weight:bold" class="err-handler"></span>').text(value).addClass('text-danger');
			                		key = key.split('.').join('');
			                		if($('#'+params.form_id+' #'.key)){
			                			$('#'+params.form_id+' #'+key).css({ border : '1px solid #a94442' }).first().focus();
			                			$('#'+params.form_id+' #'+key).after(err);
			                		}
			                	});
				            }else{
				            	if (params.msgbox_id) {
					            	$('#'+params.msgbox_id).show().html('<center>Oops! Something went wrong. Please try again. If error still occur, please contact support.</center>').addClass('alert alert-danger');
					            }
				            }
			            	
			            	$('#'+params.btn_submit_id).html(params.btn_submit_text).prop('disabled', false);
			            	if (params.btn_cancel_id) {
			        				$('#'+params.form_id+' #'+params.btn_cancel_id).prop('disabled', false);
			        			}
			        			
				         },
						});
					});
	        	}

	      });
		}

		if (params.form_id==null && params.show_edit==false && params.show_result.length>0) {
			return this.each(function (){
				showResult(params.show_result[0], params.show_result[1], params.show_result[2]);
			});
		}

		if (params.form_id==null && params.show_edit==true && params.show_result.length==0) {
			return this.each(function (){
				showPostEdit(params.method_type, params.action_url, params.show_edit_data, params.show_edit_to);
			});
		}
 		
   };
 
}( jQuery ));