<?php 

if(! defined('ABSPATH') ) return;

add_action( 'wp_footer',  'mgrt_cart_tab'  );

		
function mgrt_cart_tab() {
				global $woocommerce;
				$position    =  get_option( 'mgrt_ct_horizontal_position' );
				$position_x  =  get_option( 'mgrt_ct_specific_position_x');
				$position_y  =  get_option( 'mgrt_ct_specific_position_y');
				$display_widget = get_option( 'mgrt_ct_display_cart' );
				$title       =  get_option( 'mgrt_ct_tab_title');
				
				//cart show data 
				$mgrt_ct_show_type_post = get_option('mgrt_ct_show_type_post');	
				$mgrt_ct_show_type_page = get_option('mgrt_ct_show_type_page');	
				$mgrt_ct_show_type_product = get_option('mgrt_ct_show_type_product');	
				$mgrt_ct_show_front_page = get_option('mgrt_ct_show_front_page');	
				$mgrt_ct_show_404_page = get_option('mgrt_ct_show_404_page');
				$mgrt_cart_primary_color = get_option('mgrt_primary_color');
				$show_status = '';
				
				if( get_option('mgrt_cart_data') ){	
				$mgrt_cart_data = get_option('mgrt_cart_data');
				$arrs = array();
				foreach($mgrt_cart_data as $arr){
					if($arr['name'] == 'mgrt_ct_show_page[selected_page]')
					{
						$arrs[] .= $arr['value'];
					    }						 
				    }  					
				if($mgrt_ct_show_type_page == 'yes' && get_post_type() == 'page' && !empty($arrs)){
					if(is_page($arrs)){
						$show_status .='yes';
						}
					}		
				}	
				if($mgrt_ct_show_type_post == 'yes' && get_post_type() == 'post')
				$show_status .='yes';
				
				if($mgrt_ct_show_type_page == 'yes' && get_post_type() == 'page'){
					if($mgrt_ct_show_front_page == 'yes' && is_front_page()){
						$show_status .='yes';
						}
					if($mgrt_ct_show_404_page == 'yes' && is_404()){
						$show_status .='yes';
						}	
					}
				
				if($mgrt_ct_show_type_product == 'yes' && get_post_type() == 'product')
				$show_status .='yes';
				
                if(is_numeric($position_x))
				$position_x = $position_x.'px';
				if(is_numeric($position_y))
				$position_y = $position_y.'px';
				
				echo '<style type="text/css">';
				switch ($position){
					case 'inleft':
					echo '
					.magee-cart.inleft,.magee-cart.inleft .magee-cart-control{
						left:'.$position_x.';
						}';
					
 					break;
					case 'inright':
					echo '
					.magee-cart.inright,.magee-cart.inright .magee-cart-control{
						right:'.$position_x.';
						}';
					
					break;
					}
				echo '.magee-cart,.magee-cart .magee-cart-control{
					bottom:'.$position_y.';
					}'	;
				echo '.magee-cart .magee-cart-control,.magee-cart .woocommerce.widget_shopping_cart .cart_list li a.hover,.magee-cart-buttons.woocommerce a.button.wc-forward{                   
				    background-color:'.$mgrt_cart_primary_color.';
					}	';
				echo '</style>'; 	
				if ( 0 == $woocommerce->cart->get_cart_contents_count() || 'no' == $display_widget ) {		
					$display	= '';
				} else {
					$display	= 'active';
				}

				if ( ! is_cart() && ! is_checkout() && $show_status !== '') {
					if ( ! wp_is_mobile() ) {
						echo '<div class="magee-cart ' . esc_attr( $position ) . ' ' . esc_attr( $display ) . '">';
					} else {
						echo '<div class="magee-cart ' . esc_attr( $position ) . 'no-animation ' . esc_attr( $display ) . '">';
					}
                    
					echo '<div class="magee-cart-control"></div>';
                   
					if ( ! wp_is_mobile() ) {
						
						if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0' ) >= 0 ) {
							echo '<div class="magee-cart-main">';
							echo '<div class="magee-cart-close" title="Close it">&#215;</div>
                            <h4 class="magee-cart-title">'.$title.'</h4>';
							the_widget( 'WC_Widget_Cart', 'title=' );
							echo '<div class="magee-cart-buttons woocommerce"><a href="'.$woocommerce->cart->get_checkout_url().'?mgrt_orderway=dine" class="button mgrt-dine wc-forward">'.__('Dine','magee-restaurant').'</a>';
							echo '<a href="'.$woocommerce->cart->get_checkout_url().'?mgrt_orderway=takeout" class="button mgrt-takeout wc-forward">'.__('Takeout','magee-restaurant').'</a></div>';
							echo '</div>';
						} else {
							echo '<div class="magee-cart-main">';
							echo '<div class="magee-cart-close" title="Close it">&#215;</div>
                            <h4 class="magee-cart-title">'.$title.'</h4>';
							the_widget( 'WooCommerce_Widget_Cart', 'title=' );
							echo '<div class="magee-cart-buttons woocommerce"><a href="'.$woocommerce->cart->get_checkout_url().'?mgrt_orderway=dine" class="button mgrt-dine wc-forward">'.__('Dine','magee-restaurant').'</a>';
							echo '<a href="'.$woocommerce->cart->get_checkout_url().'?mgrt_orderway=takeout" class="button mgrt-takeout wc-forward">'.__('Takeout','magee-restaurant').'</a></div>';
							echo '</div>';
						}
					}
					echo '</div>';
				}
				
			}
			
add_action( 'after_setup_theme', 'mgrt_thumbnail_setup' );	
		
function mgrt_thumbnail_setup(){
	$width = get_option('mgrt_thumbnail_width');
	$height = get_option('mgrt_thumbnail_height');
	if( is_numeric($width) || is_numeric($height)){
		 add_image_size('related-post',$width,$height,true);
		}
	}		
	
	        
if( isset($_REQUEST['mgrt_orderway'])){
		$orders = $_REQUEST['mgrt_orderway'];
		switch($orders){
			case 'dine':
			add_filter( 'woocommerce_checkout_fields' , 'mgrt_custom_override_checkout_fields' );
            add_action( 'woocommerce_before_checkout_billing_form', 'mgrt_custom_order_dine_field' );
			add_action( 'woocommerce_checkout_process', 'mgrt_custom_order_dine_field_process');
			add_action( 'woocommerce_checkout_update_order_meta', 'mgrt_custom_order_dine_field_update_order_meta' );
			
			break;
			case 'takeout':
			add_action( 'woocommerce_after_order_notes', 'mgrt_custom_order_takeout_field' );
			add_action( 'woocommerce_checkout_update_order_meta', 'mgrt_custom_order_takeout_field_update_order_meta' );
			
			break;
			} 
}else{
	add_filter( 'woocommerce_checkout_fields' , 'mgrt_custom_override_checkout_fields' );
    add_action( 'woocommerce_before_checkout_billing_form', 'mgrt_custom_order_dine_field' );
	add_action( 'woocommerce_checkout_process', 'mgrt_custom_order_dine_field_process');
	add_action( 'woocommerce_checkout_update_order_meta', 'mgrt_custom_order_dine_field_update_order_meta' );
	}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'mgrt_custom_order_dine_display_admin_order_meta');	
	           
// Our hooked in function - $fields is passed via the filter!
function mgrt_custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_first_name']);
	unset($fields['billing']['billing_last_name']);
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_address_1']);
	unset($fields['billing']['billing_address_2']);
	unset($fields['billing']['billing_city']);
	unset($fields['billing']['billing_postcode']);
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_state']);
	unset($fields['billing']['billing_phone']);
	unset($fields['order']['order_comments']);
	unset($fields['billing']['billing_email']);
	unset($fields['account']['account_username']);
	unset($fields['account']['account_password']);
	unset($fields['account']['account_password-2']);
    

     return $fields;
}

/*
 Dine Way
 */
function mgrt_custom_order_dine_field( $checkout ){
	
	echo '<div id="mgrt_field_dine"><h2>' . __('Order Information','magee-restaurant') . '</h2>';

    woocommerce_form_field( 'mgrt_field_dine', array(
        'type'          => 'select',
        'class'         => array('form-row-wide'),
        'label'         => __('Order Way','magee-restaurant'),
        'placeholder'   => '',
		'options'        => array(
		   'Dine' => __('Dine','magee-restaurant'),
		   )  
        ), $checkout->get_value( 'mgrt_field_dine' ));
    
	woocommerce_form_field( 'mgrt_table_num_dine', array(
        'type'          => 'text',
        'class'         => array('form-row-wide'),
        'label'         => __('Table Number','magee-restaurant'),
		'required'      => true,  
        'placeholder'   => __('Insert your table number.','magee-restaurant'),
        ), $checkout->get_value( 'mgrt_table_num_dine' ));  
    echo '</div>';
	
}

function mgrt_custom_order_dine_field_process() { 
   
    if ( ! isset($_POST['mgrt_table_num_dine'])  || empty($_POST['mgrt_table_num_dine'])){
		 wc_add_notice( __( 'Please enter number into table number field.','magee-restaurant' ), 'error' );
		}
       
}

function mgrt_custom_order_dine_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['mgrt_field_dine'] ) ) {
		
        if(!update_post_meta( $order_id, __('Order Way','magee-restaurant'), sanitize_text_field( $_POST['mgrt_field_dine'] ) )){
			add_post_meta( $order_id, __('Order Way','magee-restaurant'), sanitize_text_field( $_POST['mgrt_field_dine'] ) );
			};
    }
	if ( ! empty( $_POST['mgrt_table_num_dine'] ) ) {
		
        if(!update_post_meta( $order_id, __('Table Number','magee-restaurant'), sanitize_text_field( $_POST['mgrt_table_num_dine'] ) )){
			add_post_meta( $order_id, __('Table Number','magee-restaurant'), sanitize_text_field( $_POST['mgrt_table_num_dine'] ) );
			};
    }
}

function mgrt_custom_order_dine_display_admin_order_meta($order){
	if(get_post_meta( $order->id, __('Order Way','magee-restaurant'), true ) !== '')
    echo '<p><strong>'.__('Order Way','magee-restaurant').':</strong> ' . get_post_meta( $order->id, __('Order Way','magee-restaurant'), true ) . '</p>';
	if(get_post_meta( $order->id, __('Table Number','magee-restaurant'), true ) !== '')
	echo '<p><strong>'.__('Table Number','magee-restaurant').':</strong> ' . get_post_meta( $order->id, __('Table Number','magee-restaurant'), true ) . '</p>';
}

/*
 Takeout Way
 */


function mgrt_custom_order_takeout_field( $checkout ){
	
	echo '<div id="mgrt_field_takeout"><h2>' . __('Order Information','magee-restaurant') . '</h2>';

    woocommerce_form_field( 'mgrt_field_takeout', array(
        'type'          => 'select',
        'class'         => array('form-row-wide'),
        'label'         => __('Order Way','magee-restaurant'),
        'placeholder'   => '',
		'options'        => array(
		   'Takeout' => __('Takeout','magee-restaurant'),
		   )  
        ), $checkout->get_value( 'mgrt_field_takeout' ));

    echo '</div>';
	
}

function mgrt_custom_order_takeout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['mgrt_field_takeout'] ) ) {
		
        if(!update_post_meta( $order_id, __('Order Way','magee-restaurant'), sanitize_text_field( $_POST['mgrt_field_takeout'] ) )){
			add_post_meta( $order_id, __('Order Way','magee-restaurant'), sanitize_text_field( $_POST['mgrt_field_takeout'] ) );
			}
    }
}

/*
 **  admin order column
 */
add_filter( 'manage_edit-shop_order_columns', 'mgrt_columns_function' );
function mgrt_columns_function($columns){
    $new_columns = (is_array($columns)) ? $columns : array();
    unset( $new_columns['order_actions'] );

    //edit this for you column(s)
    //all of your columns will be added before the actions column
    $new_columns['column_order_way'] = __('Order Way','magee-restaurant');
    //stop editing

    $new_columns['order_actions'] = $columns['order_actions'];
    return $new_columns;
}
add_action( 'manage_shop_order_posts_custom_column', 'mgrt_columns_values_function', 2 );
function mgrt_columns_values_function($column){
    global $post;
    $data = get_post_meta( $post->ID ,__('Order Way','magee-restaurant'),true);

    //start editing, I was saving my fields for the orders as custom post meta
    //if you did the same, follow this code
    if ( $column == 'column_order_way' ) {    
        echo (isset($data) ? $data : '');
    }
    //stop editing
}
add_filter( "manage_edit-shop_order_sortable_columns", 'mgrt_columns_sort_function' );
function mgrt_columns_sort_function( $columns ) {
    $custom = array(
        //start editing

        'column_order_way'    => __('Order Way','magee-restaurant'),

        //stop editing
    );
    return wp_parse_args( $custom, $columns );
}	
	