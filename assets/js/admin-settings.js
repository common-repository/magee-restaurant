jQuery(document).ready(function($){
	
	$(".magee-restaurant-form .wp-color-picker-field").wpColorPicker();
	
	
	$(document).on('click',".magee-restaurant-update",function(){
	    if($(".mgrt-save").length > 0){
			$(".mgrt-save").remove();
			}	
		var html = $(this).parents("form.magee-restaurant-form").serializeArray();
		$(this).before("<p class='mgrt-loading'><img alt='loading' class='loading' src='"+mgrt_params.loadurl+"/images/AjaxLoader.gif' /></p>");
        var cart_status = $(this).parents("form.magee-restaurant-form").find("select#mgrt_ct_display_cart").val();
		$.ajax({
		   type: "POST",
		   url: ajaxurl,
		   dataType: "html",   
		   data:{action:"save_options",param:html,cart_display:cart_status},
		   success:function(data){ 
			   $("form.magee-restaurant-form").before('<div class="mgrt-save updated"><p>Your options was saved.</p></div>');
			   $(".mgrt-loading").remove();
			   },
		   error:function(){
			   }	   
		
		});
	return false;
	});
	
	$(".magee-restaurant-form .mgrt-form-checkout-multi").each(function(){
		var obj = jQuery(this);	
		obj.change(function(){
			if(obj.val() == 'yes'){
				obj.attr('value','no');
				
				}else{
				obj.attr('value','yes');
				
				}
			});
		});
});	