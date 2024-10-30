<?php

if(! defined('ABSPATH') ) return;

class mgrt_shortcodes{
	public static $args;
	
	public function __construct(){
		
		add_shortcode( 'mgrt', array( $this, 'mgrt_shortcode' ) );

   		}
	public function mgrt_shortcode($args, $content = null ){
		
		$defaults =	Magee_Restaurant::set_shortcode_defaults(
			array(
				'id' => "",
			), $args
		);
		
		extract( $defaults );
		self::$args = $defaults;
		$uniqid = uniqid('restaurant-').'-'.$id;
		$html = '';
		$cat  = '';
		$col_class = '';
		$mgrt_product_category = get_post_meta($id,'mgrt_product_category',true);
		$mgrt_product_desktop_col      = get_post_meta($id,'mgrt_product_desktop_col',true);
		$mgrt_product_tablet_col      = get_post_meta($id,'mgrt_product_tablet_col',true);
		$mgrt_product_mobile_col      = get_post_meta($id,'mgrt_product_mobile_col',true);
		$mgrt_product_num      = get_post_meta($id,'mgrt_product_num',true);
		$mgrt_display_filter   = get_post_meta($id,'mgrt_display_filter',true);
		$mgrt_product_orderby  = get_post_meta($id,'mgrt_product_orderby',true);
		$mgrt_product_queryorder  = get_post_meta($id,'mgrt_product_queryorder',true);
		$mgrt_product_thumbnail_style = get_option('mgrt_thumbnail_style');
		
		$mgrt_display_rating  = get_post_meta($id,'mgrt_display_rating',true);
		$mgrt_display_description  = get_post_meta($id,'mgrt_display_description',true);
		$mgrt_display_order_button = get_post_meta($id,'mgrt_display_order_button',true);
		$mgrt_product_primary_color = get_option('mgrt_primary_color');
		$mgrt_thumbnail_width = is_numeric(get_option('mgrt_thumbnail_width'))?get_option('mgrt_thumbnail_width').'px' :get_option('mgrt_thumbnail_width') ; 
	    $mgrt_thumbnail_height = is_numeric(get_option('mgrt_thumbnail_height'))?get_option('mgrt_thumbnail_height').'px' : get_option('mgrt_thumbnail_height') ;
		$col_class .= 'mgrtcol-md-'.$mgrt_product_desktop_col;
		$col_class .= ' mgrtcol-sm-'.$mgrt_product_tablet_col;
		$col_class .= ' mgrtcol-xs-'.$mgrt_product_mobile_col;
		$html .= '<style type="text/css">
		         .'.$uniqid.' .mgrt-product .button.add_to_cart_button,.'.$uniqid.' .mgrt-product .button.add_to_cart_button:hover{
					  background:'.$mgrt_product_primary_color.';
					 }
				.'.$uniqid.' ul.mgrt-filter li.mgrt-filter-item:hover,.'.$uniqid.' ul.mgrt-filter li.mgrt-filter-item.active,.magee-cart .woocommerce-Price-amount.amount{
					color:'.$mgrt_product_primary_color.';
					}	 
				.'.$uniqid.' ul.mgrt-filter li.mgrt-filter-item:hover,.'.$uniqid.' ul.mgrt-filter li.mgrt-filter-item.active{
					border-color:'.$mgrt_product_primary_color.';
					}	 
				.'.$uniqid.' .mgrt-product.with_image .mgrt-product-img{
					width : '.$mgrt_thumbnail_width.';
					height : '.$mgrt_thumbnail_height.'
					}	
				.'.$uniqid.' .mgrt-product.with_image .mgrt-product-content{
					width: calc(100% - '.$mgrt_thumbnail_width.');
					}		 
					 ';
		if($mgrt_display_rating !== 'yes')
		$html .=  '.'.$uniqid.' .star-rating{visibility: hidden;}';
		if($mgrt_display_description !== 'yes')
		$html .=  '.'.$uniqid.' .mgrt-product-desc{visibility: hidden;}';
		if($mgrt_display_order_button !== 'yes')
		$html .=  '.'.$uniqid.' .add_to_cart_button{visibility: hidden;}';			 
		$html .=         ' </style>';
        $html .= '<div class="mgrt-menu '.$uniqid.' woocommerce">';
		
		if($mgrt_display_filter == 'yes'){
		$html .= '<div class="mgrt-filter-wrap">
                                        <ul class="mgrt-filter">								
                                            <li class="mgrt-filter-item active ">'.__('ALL','magee-restaurant').'</li>';
		if($mgrt_product_category && $mgrt_product_category !== ''):									
		foreach($mgrt_product_category as $key=>$val){
			$html .= '<li class="mgrt-filter-item">'.$key.'</li>';
			$cat  .= $key.',';
			}									
        endif;                                                                       
        $html .=                               '  </ul>
                                    </div>';
		}
		if($mgrt_product_category && $mgrt_product_category !== ''):
		foreach($mgrt_product_category as $key=>$val){
			$cat  .= $key.',';
			}
		endif;      	
		if($cat && $cat !== ''):		
        
		$html .='<ul class="mgrt-products '.$col_class.' ">';
		$args = array( 
		      'post_type' => 'product',
			  'orderby' => $mgrt_product_orderby,
			  'order' => $mgrt_product_queryorder,
			  'posts_per_page' => $mgrt_product_num,
			  'product_cat' => $cat 
			  );

		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) : $loop->the_post(); 
		global $product; 
		$thumbnail_id = get_post_thumbnail_id(get_the_ID());
		$thumbnail_url_a = wp_get_attachment_image_src($thumbnail_id,'full');
		$thumbnail_url = wp_get_attachment_image_src($thumbnail_id,'related-post');
		$rating = $product->get_average_rating();
	    $rating = (($rating/5)*100);
		$category =  strip_tags($product->get_categories());
	    $category = str_replace(',','',$category);
		$attachment_ids = $product->get_gallery_attachment_ids();
		$thumbnail_image_display = get_option('mgrt_thumbnail_image_display');
		if($thumbnail_image_display == 'yes' && isset($thumbnail_url[0]) && $thumbnail_url[0] !==''):
		$addclass = 'with_image';
		else:
		$addclass = '';
		endif;
		$html .= '<li class="mgrt-product '.$category.' '.$addclass.'">
                                            <div class="mgrt-product-inner">';
											if( isset($thumbnail_url[0]) && $thumbnail_url[0] !== '' && $thumbnail_image_display == 'yes'):
                                            $html .=  '<div class="mgrt-product-img '.$mgrt_product_thumbnail_style.' pswp">
												    <a href="'.$thumbnail_url_a[0].'" rel="prettyPhoto['.get_the_ID().']" data-id="'.get_the_ID().'">
                                                        <img src="'.$thumbnail_url[0].'" alt="">
                                                    </a>';
											if(isset($attachment_ids) && !empty($attachment_ids)){
												foreach( $attachment_ids as $attachment_id ) 
													{
													  $html .= ' <a href="'.wp_get_attachment_url( $attachment_id ).'" hidden rel="prettyPhoto['.get_the_ID().']" data-id="'.get_the_ID().'">
                                                        <img src="'.wp_get_attachment_url( $attachment_id ).'" alt="">
                                                    </a>';
													}
												}		
											$html .=  '';		
                                            $html .=  '    </div>';
											endif;	
         $html .= '                                       <div class="mgrt-product-content">
                                                    <div class="mgrt-product-content-inner">
                                                        <h4 class="mgrt-product-title">
                                                            <span class="title">'.get_the_title().'</span>
                                                            <span class="dots"></span>
                                                            <span class="price">$'.$product->get_price().'</span>
                                                        </h4>';
		 if($rating > 0):										
         $html .= '                                     <div class="star-rating">
                                                            <span style="width:'.$rating.'%">
                                                                <strong class="rating">4</strong> '.__('out of 5','magee-restaurant').'
                                                            </span>
                                                        </div>';
		 endif;	
		 										
         $html .= '                                     <p class="mgrt-product-desc">
                                                            '.get_the_excerpt().'
                                                        </p>';
   											
		 	
         $html .= '                                               <a href="'.$product->add_to_cart_url() .'" class="button add_to_cart_button">'.__('ORDER','magee-restaurant').'</a>';
		
         $html .= '                                            </div>
                                                </div>
                                            </div>
                                        </li>';
		endwhile; 
                                        
		wp_reset_query();
										
        $html .=   '                        </ul>'		;		
		endif;
		$html .= '</div>';

		
		return $html ;
		}
	
	
	
	
	}

new mgrt_shortcodes;