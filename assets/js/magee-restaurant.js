jQuery(document).ready(function($){
	//menu options
	$(".mgrt-menu .mgrt-filter-item").each(function(){
		$(this).click(function(){
			category = $(this).text();
			$(this).addClass('active').siblings().removeClass("active");
			if(category == 'ALL'){
				$(this).parents(".mgrt-menu").find("li.mgrt-product").show();
				}else{
				$(this).parents(".mgrt-menu").find("li.mgrt-product").hide();	
				$(this).parents(".mgrt-menu").find("li.mgrt-product."+category).show();	
				}
			});
		});
	
	//photobox
	$("a[rel^='prettyPhoto']").each(function(){
		id = $(this).data('id');
		$("a[rel^='prettyPhoto["+id+"]']").prettyPhoto();
		});
	
	//cart tab

	$(".magee-cart-control").click(function(){
		
                $(".magee-cart").addClass("active");
	
				
            });
	
    $(".magee-cart-close").click(function(){
		
			 $(".magee-cart").removeClass("active");
			 	
            });

	//checkout dine remove
	if($("#mgrt_field_dine").length > 0){
		$("h3").each(function(){
			text = $(this).text();
			if(text == 'Billing Details'){
				$(this).hide();
				}else if(text = 'Additional Information' ){
					$(this).hide();
					}
				
			});
		}
	
});
    