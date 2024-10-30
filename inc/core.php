<?php 

if(! defined('ABSPATH') ) return;

function mgrt_posttype_register(){
	
	    $labels = array(
                'name' => __('Magee Restaurant', 'magee-restaurant'),
                'singular_name' => __('Magee Restaurant', 'magee-restaurant'),
				'all_items' => __('Restaurant Menus','magee-restaurant'),
                'add_new' => __('Add New Menu', 'magee-restaurant'),
                'add_new_item' => __('Add New Menu','magee-restaurant'),
                'edit_item' => __('Edit Restaurant Menu','magee-restaurant'),
                'new_item' => __('New Restaurant Menu','magee-restaurant'),
                'view_item' => __('View Restaurant Menu','magee-restaurant'),
                'search_items' => __('Search Restaurant Menu','magee-restaurant'),
                'not_found' =>  __('Nothing found','magee-restaurant'),
                'not_found_in_trash' => __('Nothing found in Trash','magee-restaurant'),
        );
 
        $args = array(
                'labels' => $labels,
                'public' => false,
                'publicly_queryable' => true,
                'show_ui' => true,
				'menu_icon' => 'dashicons-admin-post',
                'query_var' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => null,
				'rewrite' => true,
                'supports' => array('title'),
				
				
          );
 
        register_post_type( 'magee-restaurant' , $args );

	}

add_action('init','mgrt_posttype_register');

/**
 * Page item menu controls 
 */

add_filter("manage_edit-magee-restaurant_columns", "mgrt_edit_columns");
function mgrt_edit_columns($columns){
	  $columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __("Title",'magee-restaurant'),
		"code" => __("Shortcode",'magee-restaurant'),
		"date" => __("Date",'magee-restaurant'),
	  );
	 
	  return $columns;
	}
	
add_action( 'manage_magee-restaurant_posts_custom_column' , 'mgrt_posts_shortcode_display', 10,1 );
function mgrt_posts_shortcode_display( $column) {
	global $post;
    if ($column == 'code'){
		?>
        <input style="background:#bfefff" type="text" onClick="this.select();" value="[mgrt <?php echo 'id=&quot;'.$post->ID.'&quot;';?>]" /><br />
        <textarea cols="50" rows="1" style="background:#bfefff" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[mgrt id='; echo "'".$post->ID."']"; echo '"); ?>'; ?></textarea>
        <?php		
		
    }
}


/**
 * Adds a box to restaurant on the Post and Page edit screens.
 */
function mgrt_meta_box(){
	add_meta_box('mgrt_metabox',__( 'Magee Restaurant Options','magee-restaurant' ),'mgrt_meta_boxes_mgrt_form','magee-restaurant');
	}
	
add_action( 'add_meta_boxes', 'mgrt_meta_box' );	

function mgrt_meta_boxes_mgrt_form($post){
	global $post;
	wp_nonce_field( 'meta_boxes_mgrt_form', 'meta_boxes_mgrt_form_nonce' );
	
	$args = array(
	  'taxonomy'     => 'product_cat',
	  'orderby'      => 'name',
	  'show_count'   => 0,
	  'pad_counts'   => 0,
	  'hierarchical' => 0,
	  'title_li'     => '',
	  'hide_empty'   => 0
	);

    $all_categories = get_categories( $args );
	$mgrt_product_category = get_post_meta( $post->ID, 'mgrt_product_category', true );
	$mgrt_product_desktop_col = get_post_meta( $post->ID, 'mgrt_product_desktop_col', true );
	$mgrt_product_tablet_col = get_post_meta( $post->ID, 'mgrt_product_tablet_col', true );
	$mgrt_product_mobile_col = get_post_meta( $post->ID, 'mgrt_product_mobile_col', true );
	if(empty($mgrt_product_desktop_col))  $mgrt_product_desktop_col = '2';	
	if(empty($mgrt_product_tablet_col))  $mgrt_product_tablet_col = '1';	
	if(empty($mgrt_product_mobile_col))  $mgrt_product_mobile_col = '1';	
	$mgrt_product_num = get_post_meta( $post->ID, 'mgrt_product_num', true );
	if(empty($mgrt_product_num))  $mgrt_product_num = '10';	
	$mgrt_display_filter = get_post_meta( $post->ID, 'mgrt_display_filter', true );
	$mgrt_product_orderby = get_post_meta( $post->ID, 'mgrt_product_orderby', true );
	$mgrt_product_queryorder = get_post_meta( $post->ID, 'mgrt_product_queryorder', true );
	if(empty($mgrt_product_thumbnail_size)) $mgrt_product_thumbnail_size = '110px' ;
	$mgrt_display_rating = get_post_meta( $post->ID, 'mgrt_display_rating', true );
	$mgrt_display_description = get_post_meta( $post->ID, 'mgrt_display_description', true );
	$mgrt_display_order_button = get_post_meta( $post->ID, 'mgrt_display_order_button', true );
   ?>

<div class="mgrt-meta-box" data-autosave="false">
 
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Shortcode','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <textarea type="textarea" class="mgrt-textarea" cols="50" onClick="this.select();">[mgrt <?php echo 'id="'.$post->ID.'"';?>]</textarea>
    </div>
  </div>
  <div class="mgrt-field mgrt-checkbox_list-wrapper">
    <div class="mgrt-label">
      <label for="gender"><?php echo __('Categories Options','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
    
      <ul class="mgrt-input-list collapse ">
       <?php foreach($all_categories as $cat){
        echo '<li>
          <label>';
		  if(!empty($mgrt_product_category[$cat->name])){
			  $checked = 'checked';
			  }else{
			  $checked = '';
			  }
        echo    '<input value="1" type="checkbox" class="mgrt-checkbox_list" name="mgrt_product_category['.$cat->name.']" '.$checked.'>';
        echo    $cat->name.'</label>
        </li>';
        }?>
     
      </ul>
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Show Product Number','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <input type="text" class="magrt_product_number" name="mgrt_product_num" value="<?php echo $mgrt_product_num;?>">
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Display Filter','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <select name="mgrt_display_filter">
      <option value="yes" <?php if($mgrt_display_filter == 'yes') echo "selected";?>><?php _e('Yes','magee-restaurant');?></option>
      <option value="no" <?php if($mgrt_display_filter == 'no') echo "selected";?>><?php _e('No','magee-restaurant');?></option>
      </select> 
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Slider Column Number','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <p><?php echo __('In Desktop(&gt;920px)','magee-restaurant');?></p>
      <select name="mgrt_product_desktop_col">
      <option value="1" <?php if($mgrt_product_desktop_col == '1') echo "selected";?>>1</option>
      <option value="2" <?php if($mgrt_product_desktop_col == '2') echo "selected";?>>2</option>
      <option value="3" <?php if($mgrt_product_desktop_col == '3') echo "selected";?>>3</option>
      <option value="4" <?php if($mgrt_product_desktop_col == '4') echo "selected";?>>4</option>
      <option value="5" <?php if($mgrt_product_desktop_col == '5') echo "selected";?>>5</option>
      </select>
      <p><?php echo __('In Tablet(&gt;480px,&le;920px)','magee-restaurant');?></p>
      <select name="mgrt_product_tablet_col">
      <option value="1" <?php if($mgrt_product_tablet_col == '1') echo "selected";?>>1</option>
      <option value="2" <?php if($mgrt_product_tablet_col == '2') echo "selected";?>>2</option>
      <option value="3" <?php if($mgrt_product_tablet_col == '3') echo "selected";?>>3</option>
      <option value="4" <?php if($mgrt_product_tablet_col == '4') echo "selected";?>>4</option>
      <option value="5" <?php if($mgrt_product_tablet_col == '5') echo "selected";?>>5</option>
      </select>
      <p><?php echo __('In Mobile(&le;480px)','magee-restaurant');?></p>
      <select name="mgrt_product_mobile_col">
      <option value="1" <?php if($mgrt_product_mobile_col == '1') echo "selected";?>>1</option>
      <option value="2" <?php if($mgrt_product_mobile_col == '2') echo "selected";?>>2</option>
      <option value="3" <?php if($mgrt_product_mobile_col == '3') echo "selected";?>>3</option>
      <option value="4" <?php if($mgrt_product_mobile_col == '4') echo "selected";?>>4</option>
      <option value="5" <?php if($mgrt_product_mobile_col == '5') echo "selected";?>>5</option>
      </select>
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Query Orderby','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <select name="mgrt_product_orderby">
      <option value="" <?php if($mgrt_product_orderby == '') echo "selected";?>><?php _e('None','magee-restaurant');?></option>
      <option value="ID" <?php if($mgrt_product_orderby=="ID") echo "selected"; ?>><?php _e('ID','magee-restaurant');?></option>
      <option value="date" <?php if($mgrt_product_orderby=="date") echo "selected"; ?>><?php _e('Date','magee-restaurant');?></option>
      <option value="rand" <?php if($mgrt_product_orderby=="rand") echo "selected"; ?>><?php _e('Rand','magee-restaurant');?></option>
      <option value="comment_count" <?php if($mgrt_product_orderby=="comment_count") echo "selected"; ?>><?php _e('Comment Count','magee-restaurant');?></option>        
      <option value="author" <?php if($mgrt_product_orderby=="author") echo "selected"; ?>><?php _e('Author','magee-restaurant');?></option>
      <option value="title" <?php if($mgrt_product_orderby=="title") echo "selected"; ?>><?php _e('Title','magee-restaurant');?></option>
      <option value="name" <?php if($mgrt_product_orderby=="name") echo "selected"; ?>><?php _e('Name','magee-restaurant');?></option>
      <option value="type" <?php if($mgrt_product_orderby=="type") echo "selected"; ?>><?php _e('Type','magee-restaurant');?></option>
      </select>
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Query Order','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <select name="mgrt_product_queryorder">
        <option value="DESC" <?php if($mgrt_product_queryorder == 'DESC') echo "selected";?>><?php _e('Descending','magee-restaurant');?></option>
        <option value="ASC" <?php if($mgrt_product_queryorder == 'ASC') echo "selected";?>><?php _e('Ascending','magee-restaurant');?></option>
      </select>
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Display Star Rating','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <select name="mgrt_display_rating">
         <option value="yes" <?php if($mgrt_display_rating == 'yes') echo "selected";?>><?php _e('Yes','magee-restaurant');?></option> 
         <option value="no" <?php if($mgrt_display_rating == 'no') echo "selected";?>><?php _e('No','magee-restaurant');?></option>
      </select>
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Display Description','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <select name="mgrt_display_description">
         <option value="yes" <?php if($mgrt_display_description == 'yes') echo "selected";?>><?php _e('Yes','magee-restaurant');?></option> 
         <option value="no" <?php if($mgrt_display_description == 'no') echo "selected";?>><?php _e('No','magee-restaurant');?></option>
      </select>
    </div>
  </div>
  <div class="mgrt-field mgrt-text-wrapper">
    <div class="mgrt-label">
      <label for="name"><?php echo __('Display Order Button','magee-restaurant');?></label>
    </div>
    <div class="mgrt-input">
      <select name="mgrt_display_order_button">
         <option value="yes" <?php if($mgrt_display_order_button == 'yes') echo "selected";?>><?php _e('Yes','magee-restaurant');?></option> 
         <option value="no" <?php if($mgrt_display_order_button == 'no') echo "selected";?>><?php _e('No','magee-restaurant');?></option>
      </select>
    </div>
  </div>
 
</div>
<?php }

add_action ('save_post','mgrt_meta_boxes_save')	;

function mgrt_meta_boxes_save($post_id){
	
	if ( ! isset( $_POST['meta_boxes_mgrt_form_nonce'] ) )
    return $post_id;
	
	$nonce = $_POST['meta_boxes_mgrt_form_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'meta_boxes_mgrt_form' ) )
      return $post_id;
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;
	  
    $mgrt_product_category = stripslashes_deep( $_POST['mgrt_product_category']);
	$mgrt_product_desktop_col      = sanitize_text_field( $_POST['mgrt_product_desktop_col']);
	$mgrt_product_tablet_col      = sanitize_text_field( $_POST['mgrt_product_tablet_col']);
	$mgrt_product_mobile_col      = sanitize_text_field( $_POST['mgrt_product_mobile_col']);
	$mgrt_product_num      = sanitize_text_field( $_POST['mgrt_product_num']);
	$mgrt_display_filter  = sanitize_text_field( $_POST['mgrt_display_filter']);
	$mgrt_product_orderby = sanitize_text_field( $_POST['mgrt_product_orderby']);
	$mgrt_product_queryorder = sanitize_text_field( $_POST['mgrt_product_queryorder']);
	
	$mgrt_display_rating = sanitize_text_field( $_POST['mgrt_display_rating']);
	$mgrt_display_description = sanitize_text_field( $_POST['mgrt_display_description']);
	$mgrt_display_order_button = sanitize_text_field( $_POST['mgrt_display_order_button']);
	
	update_post_meta($post_id,'mgrt_product_category',$mgrt_product_category);
	update_post_meta($post_id,'mgrt_product_desktop_col',$mgrt_product_desktop_col);
	update_post_meta($post_id,'mgrt_product_tablet_col',$mgrt_product_tablet_col);
	update_post_meta($post_id,'mgrt_product_mobile_col',$mgrt_product_mobile_col);
	update_post_meta($post_id,'mgrt_product_num',$mgrt_product_num);
	update_post_meta($post_id,'mgrt_display_filter',$mgrt_display_filter);
	update_post_meta($post_id,'mgrt_product_orderby',$mgrt_product_orderby);
	update_post_meta($post_id,'mgrt_product_queryorder',$mgrt_product_queryorder);

	update_post_meta($post_id,'mgrt_display_rating',$mgrt_display_rating);
	update_post_meta($post_id,'mgrt_display_description',$mgrt_display_description);
	update_post_meta($post_id,'mgrt_display_order_button',$mgrt_display_order_button);

	}
	
