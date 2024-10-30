<?php

if(! defined('ABSPATH') ) return;

class Mgrt_Page_Selector_Walker extends Walker_page {
    private $selected_pages=array();

    public function __construct($selected_pages) {
        $this->selected_pages=$selected_pages;
    }

    /**
     * Start the element output.
     *
     * @param  string $output Passed by reference. Used to append additional content.
     * @param  object $item   Menu item data object.
     * @param  int $depth     Depth of menu item. May be used for padding.
     * @param  array $args    Additional strings.
     * @return void
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        if ( $depth )
            $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $depth);
        else
            $indent = '';
        $output.= '<option value="'.$item->ID.'"';
        if(is_array($this->selected_pages) && in_array($item->ID, $this->selected_pages)) {
            $output.= ' selected';
        }
        $output.= '>'.$indent.$item->post_title.'</option>';
    }
}

class mgrt_class_settings{
	
	public $mgrt_settings;
	
	public function __construct(){
		global $mgrt_settings;
		add_action( 'admin_menu', array($this,'menu_page'));
		add_action( 'admin_enqueue_scripts',  array($this,'admin_scripts' ));
			
		add_action('wp_ajax_save_options', array(&$this, 'save_options') );
		add_action( 'wp_ajax_nopriv_save_options', array(&$this, 'save_options') );
			
		//wp page list
           
		$this->mgrt_settings = array(   
		         'general' => array(
				    'id' => __('general','magee-restaurant'),  
				    'name' => __('General' ,'magee-restaurant'),
				    'title' => __('General Options','magee-restaurant'),
					'active' => 'yes',
					'params' => array(
					   array(
							'name'    => __( 'Primary Color', 'magee-restaurant' ),
							'desc'    => '', 
							'id'      => 'mgrt_primary_color',
							'type'    => 'colorpicker',
							'std'     => '',
						),
					
					
					),
				 ),
				 'menu' => array(
				    'id' => __('menu','magee-restaurant'),  
				    'name' => __('Menu' ,'magee-restaurant'),
				    'title' => __('Menu List','magee-restaurant'),
					'active' => 'no', 
					'params' => array( 
					    array(
							'name'    => __( 'Display Thumbnail Image', 'magee-restaurant' ),
							'desc'    => '', 
							'id'      => 'mgrt_thumbnail_image_display',
							'type'    => 'select',
							'std'     => 'yes',
							'options' => array(
									'yes'    => __( 'Yes', 'magee-restaurant' ),
									'no'  => __( 'No', 'magee-restaurant' ),
							),
						),
						array(
							'name' => __( 'Thumbnail Image Size', 'magee-restaurant' ),
							'desc' => __( 'Set thumbnail image size.', 'magee-restaurant' ),
							'id'   => array('mgrt_thumbnail_width','mgrt_thumbnail_height'),
							'type' => 'size',
							'std'  => array(
								'mgrt_thumbnail_width' => '100',
								'mgrt_thumbnail_height' => '100',
							),
						),
					   array(
							'name'    => __( 'Thumbnail Image Style', 'magee-restaurant' ),
							'desc'    => '', 
							'id'      => 'mgrt_thumbnail_style',
							'type'    => 'select',
							'std'     => 'squre',
							'options' => array(
									'squre'    => __( 'Squre', 'magee-restaurant' ),
									'rounded'  => __( 'Rounded', 'magee-restaurant' ),
									'circle'   => __( 'Circle','magee-restaurant'),
							),
						),
					
					),   
				 ),
				 'cart' => array(
				    'id' => __('cart','magee-restaurant'),  
				    'name' => __('Cart' ,'magee-restaurant'),
				    'title' => __('Shopping Cart','magee-restaurant'),
					'active' => 'no',
				    'params' => array(
					     
					   array(
							'name' => __( 'Display Cart', 'magee-restaurant' ),
							'desc' => __( 'Choose to display cart.', 'magee-restaurant' ),
							'id'   => 'mgrt_ct_display_cart',
							'type' => 'select',
							'std'  => 'yes',
							'options' => array(
								'yes' => __('Yes', 'magee-restaurant' ),
								'no' => __('No', 'magee-restaurant' ),
							),
						),
						array(
							'name'    => __( 'Position the cart tab on the right or left', 'magee-restaurant' ),
							'id'      => 'mgrt_ct_horizontal_position',
							'type'    => 'select',
							'std'     => 'inright',
							'options' => array(
									'inright'    => __( 'Right', 'magee-restaurant' ),
									'inleft' 	=> __( 'Left', 'magee-restaurant' ),
							),
						),
						array(
							'name'    => __( 'Cart coordinates', 'magee-restaurant' ),
							'id'      => array('mgrt_ct_specific_position_x','mgrt_ct_specific_position_y'),
							'type'    => 'size',
							'std'  => array(
								'mgrt_ct_specific_position_x' => '30',
								'mgrt_ct_specific_position_y' => '30',
							),
						),
						array(
							'name'    => __( 'Cart Tab Title', 'magee-restaurant' ),
							'desc' => __( 'Insert title for cart.', 'magee-restaurant' ),
							'id'      => 'mgrt_ct_tab_title',
							'type'    => 'text',
							'std'     => 'IN YOUR DISHES',
						),
						array(
							'name'    => __( 'Display Cart On Different Page', 'magee-restaurant' ),
							'desc' => __( 'Choose type of pages or posts to show cart.', 'magee-restaurant' ),
							'id'      => array(
							     'mgrt_ct_show_type_post' => __('Posts','magee-restaurant'),
								 'mgrt_ct_show_type_page' => __('Pages','magee-restaurant'),
								 'mgrt_ct_show_type_product' => __('Products','magee-restaurant'),
								 'mgrt_ct_show_front_page' => __('Front Page','magee-restaurant'),
								 'mgrt_ct_show_404_page' => __('404 Page','magee-restaurant')
							),
							'type'    => 'checkout_multi',
							'std'     => array(
							    'mgrt_ct_show_type_post' => 'yes',
								'mgrt_ct_show_type_page' => 'yes',
								'mgrt_ct_show_type_product' => 'yes',
								'mgrt_ct_show_front_page' => 'yes',
								'mgrt_ct_show_404_page' => 'no', 
							),
						), 
						array(
							'name'    => __( 'Display Cart On Pages', 'magee-restaurant' ),
							'desc' => __( 'Choose one or more page to show cart.', 'magee-restaurant' ),
							'id'      => 'mgrt_ct_show_page[selected_page]',
							'type'    => 'select_page',
							'std'     => array(),
						),
					),
				 )	
				);
		$mgrt_settings = $this->mgrt_settings;		
		add_action( 'init', array($this,'add_options'));
		}
		
	function admin_scripts() {
		wp_enqueue_script('thickbox');
	    wp_enqueue_style('thickbox');
	    wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style('mgrt_settings_css',MAGEE_RESTAURANT_URL.'assets/css/admin.css','',MAGEE_RESTAURANT_VER,false);
		wp_enqueue_script('mgrt_settings_js',MAGEE_RESTAURANT_URL.'assets/js/admin-settings.js',array( 'jquery','wp-color-picker'),'',false);	
		wp_localize_script( 'mgrt_settings_js', 'mgrt_params', array(
			'ajaxurl'    => admin_url('admin-ajax.php'),
			'loadurl'   => MAGEE_RESTAURANT_URL. 'assets',
			) );
	}
	
	function menu_page(){
		
	add_submenu_page('edit.php?post_type=magee-restaurant', __('Settings','magee-restaurant'), __('Settings','magee-restaurant'), 'manage_options','mgrt-setting',array($this,'magee_menu_page'));
	
    }
 
    function magee_menu_page(){
	 global $mgrt_settings;
	 $output = '';
	 $args = array(
            'public'   => true,
        );
     $post_types = get_post_types($args, 'objects');
	
	 if(isset($mgrt_settings) && is_array($mgrt_settings) )
	 {
		 $output .= '<form class="magee-restaurant-form" >';
		 $output .= '<nav class="nav-tab-wrapper mgrt-nav-tab-wrapper">';
		 foreach($mgrt_settings as $key => $attrs){
			if(isset($key) && $key !== ''){
				if(isset($_REQUEST['mgrt_tab'])){
					if($_REQUEST['mgrt_tab'] == $key){
						$active = 'nav-tab-active';
						$mgrt_settings[$key]['active'] = 'yes';
						}else{
						$active = '';
						$mgrt_settings[$key]['active'] = 'no';
						}
					}else{
					   if($key == 'general'){
						   $active = 'nav-tab-active';
						   $mgrt_settings[$key]['active'] = 'yes';
						   }else{
							 $active = '';  
							 $mgrt_settings[$key]['active'] = 'no';
							   }
							}		   		
			$output .= '<a href="'.admin_url().'edit.php?post_type=magee-restaurant&page=mgrt-setting&mgrt_tab='.$key.'" class="nav-tab '.$active.'">'.$attrs['name'].'</a>';
				}		 
			 }
		 $output .= '</nav>';	

		 foreach($mgrt_settings as $key => $attrs)
		 {
		  
		   if(! isset($attrs['active'])){
			   $attrs['active'] = 'no';
		   }
            
           //params set
		   if(isset($attrs['params']) && is_array($attrs['params']) && $attrs['active'] == 'yes'){
			   
			   if(isset($attrs['title'])){
			   $output .= '<h2 class="subtitle">'.$attrs['title'].'</h2>';	
				} 
				
			 
			   foreach($attrs['params'] as $param){
				   
				   if(! isset($param['std'])){
					   $param['std'] = '';
					   }	
				   // popup form row start
				   $row_start  = '<div class="mgrt-setting-items">' . "\n";  
				   if($param['type'] != 'info') {
				   $row_start .= '<div class="mgrt-label">';
				   $row_start .= '<span class="mgrt-form-label-title">' . $param['name'] . '</span>' . "\n";
				   if(isset($param['desc'])){
				   $row_start .= '<span class="mgrt-form-desc">' . $param['desc'] . '</span>' . "\n";
					   }
				   $row_start .= '</div>' . "\n";
				   }
				   $row_start .= '<div class="mgrt-field">' . "\n";
		
				   // popup form row end
				   $row_end   = '</div>' . "\n";
				   $row_end   .= '</div>' . "\n";  
					 
				   switch($param['type'])
				   {
					 
					 case 'text':
					 
					            $output .= $row_start;
								if(get_option($param['id']))
								{
									$param['std'] = get_option($param['id']);
									}
								$output .= '<input type="text" class="mgrt-form-text mgrt-input" name="' . $param['id']. '" id="' . $param['id'] . '" value="' . $param['std'] . '" />';
					            $output .= $row_end;
								
					            break;
					 
					 case 'select':
					 
								// prepare
								$output .= $row_start;
								$output .= '<div class="mgrt-form-select-field">';
								$output .= '<select name="' . $param['id'] . '" id="' . $param['id'] . '" class="magee-form-select magee-input">' . "\n";
								
								if(get_option($param['id']))
								{
									$param['std'] = get_option($param['id']);
									}
								foreach( $param['options'] as $value => $option )
								{
									$selected = (isset($param['std']) && $param['std'] == $value) ? 'selected="selected"' : '';
									$output .= '<option value="' . $value . '"' . $selected . '>' . $option . '</option>' . "\n";
								}
		
								$output .= '</select>' . "\n";
								$output .= '</div>';
								$output .= $row_end;
								
								break; 
								
					 case 'size':
							 // prepare
							 $output .= $row_start;
							 for($i=0;$i<2;$i++){
								 if(get_option($param['id'][$i]))
								{
									$param['std'][$param['id'][$i]] = get_option($param['id'][$i]);
									} 
								 }
							 $output .= '<input type="text" class="mgrt-form-size mgrt-input" name="' . $param['id'][0] . '" id="' . $param['id'][0] . '" value="' . $param['std'][$param['id'][0]] . '" />';	 
							 $output .= ' &#215; ';
							 $output .= '<input type="text" class="mgrt-form-size mgrt-input" name="' . $param['id'][1] . '" id="' . $param['id'][1] . '" value="' . $param['std'][$param['id'][1]] . '" /> px';	 
							 
							 $output .= $row_end;
							
								
							 break; 
							 
					case 'colorpicker':
							 
							 // prepare
							 $output .= $row_start;
							 if(get_option($param['id']))
								{
									$param['std'] = get_option($param['id']);
									}
							 $output .= '<input type="text" class="mgrt-form-text mgrt-input wp-color-picker-field" name="' . $param['id'] . '" id="' . $param['id'] . '" value="' . $param['std'] . '" />' . "\n";
							 $output .= $row_end;
							
								
							 break; 	
							 
					 case 'checkout_multi':	
					                    
							 // prepare
							 $output .= $row_start;
							 $status = '';
							 foreach( $param['id'] as $key => $value){
								 if(get_option($key)){
									 $status .= 'on';
									 }
								 }
					         foreach( $param['id'] as $sid => $name){
								
								 if($status == ''){
									 if($param['std'][$sid] == 'yes'){
										 $checked = 'checked';
										 }else{
											 $checked = '' ;
											 }
									 }else{
										$param['std'][$sid] = get_option($sid);
										if($param['std'][$sid] == 'yes'){
										 $checked = 'checked';
										 }else{
											 $checked = '' ;
											 }
										 }				  
								 $output .= '<div class="mgrt-checkout-multi-item">'. "\n";;
								 $output .= '<input type="checkbox" class="mgrt-form-checkout-multi mgrt-input" name="'.$sid.'" id="'.$sid.'" '.$checked.' value="'.$param['std'][$sid].'"/>'. "\n";
								 $output .= $name;
								 $output .= '</div>'. "\n";;
								 }
					 	 	 $output .= $row_end;
							
								
							 break; 
							 
					 case  'select_page':
					         $arrs = array();
					         if( get_option('mgrt_cart_data') ){
							 $data = get_option('mgrt_cart_data'); 
							 foreach($data as $arr){
								 if($arr['name'] == 'mgrt_ct_show_page[selected_page]'){
									  $arrs[] .= $arr['value'];
									 }						 
								 }  
							 }
					 	     
							 $walker = new Mgrt_Page_Selector_Walker($arrs);
                             $options_list= wp_list_pages( array('title_li'=>'', 'post-type'=>'page','sort_column' => 'menu_order, post_title', 'echo'=>0, 'walker'=>$walker));
                             $options_list=str_replace(array('</li>', "</ul>\n"), '', $options_list);
                             $options_list=str_replace("<ul class='children'>\n", '    ', $options_list);
							 $output .= $row_start;
							 $output .= '<div class="mgrt-form-select-page-field">';
							 $output .= '<select name="' . $param['id'] . '" id="' . $param['id'] . '" class="magee-form-select-page magee-input" multiple="multiple" size="10">' . "\n";
							 $output .= $options_list;
							 $output .= '</select>';
							 $output .= '</div>';
						     $output .= $row_end;
							 
							 break;
					   }	 
				   
				  
				   
				   
				   
				   }
			   
			   
			   
			   }
		   
			   
		   }
		 $output .= '<p class="submit"><input name="update" class="button-primary magee-restaurant-update" type="submit" value="'.__('Save Options','magee-restaurant').'"></p>';
		 $output .= '<form>'; 
		 
		 }
		 
	echo  $output;
		 
	}
	
	function add_options(){
		global $mgrt_settings;
		
		if(isset($mgrt_settings) && is_array($mgrt_settings) ){
			
			foreach($mgrt_settings as $attrs){
				if(isset($attrs['params']) && is_array($attrs['params']))
				{
					foreach($attrs['params'] as $param){
						
						if(!isset($param['std'])){
							$param['std'] = '';
							}
							
						switch($param['type'])
						{
							
					 
					 case 'text':
					 
					         add_option($param['id'],$param['std']);
					            
					         break;
					 
					 case 'select':
					 
					         add_option($param['id'],$param['std']);
								
							 break; 
								
					 case 'size':
							 
							 add_option($param['id'][0],$param['std'][$param['id'][0]]);
							 add_option($param['id'][1],$param['std'][$param['id'][1]]);	
							 							
							 break; 
							 
					case 'colorpicker':
					
							 add_option($param['id'],$param['std']);
								
							 break; 	
							 
					 case 'checkout_multi':	

							 foreach($param['std'] as $key => $val){
								 
								 add_option($key,$val);  
								 }
								
							 break; 
							 
					 case  'select_page':
					 
					         add_option('mgrt_cart_data',$param['std']); 
							 
							 break;
					   
							
							}
						
						}
					
					}
				
				
				}
			
			
			}
			
	}
	
	
    function save_options(){
		if(isset($_POST['cart_display'])){
			$show_type_options = array(
                    			'mgrt_ct_show_type_post' => 'no',
								'mgrt_ct_show_type_page' => 'no',
								'mgrt_ct_show_type_product' => 'no',
								'mgrt_ct_show_front_page' => 'no',
								'mgrt_ct_show_404_page' => 'no', 
								);
			foreach($show_type_options as $key => $val){
				
				update_option($key,$val);
				}			
			 update_option('mgrt_cart_data',$_POST['param']);
			}
		if(isset($_POST['param'])){
					
			foreach($_POST['param'] as $attrs ){
	          
			  update_option($attrs['name'],$attrs['value']);
			 
				}
             
			die();
			}
		
	}  
		
}
	
new mgrt_class_settings;