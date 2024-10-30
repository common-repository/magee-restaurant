<?php
/*
Plugin Name: Magee Restaurant
Plugin URI:  http://www.mageewp.com/magee-restaurant.html
Description: Magee Restaurant plugin is an easy-to-manage modern solution for building online menus of restaurants, cafes and other typical food establishments. It is built with woocommerce, which can help you sell food and drinks online without any technical help. The plugin uses default WordPress functionality. You can create and edit menu items inside the Magee Restaurant Options, use a shortcode to display it in posts and pages. It is also the perfect plugin for receiving, managing and handling bookings easily. 
Version: 1.0.0
Author: MageeWP
Author URI: http://www.mageewp.com
Text Domain: magee-restaurant
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) return;  

class Magee_Restaurant{
	var $settings ;
	
	public function __construct(){
		
		define('MAGEE_RESTAURANT_URL', plugin_dir_url( __FILE__)  );
		define('MAGEE_RESTAURANT_DIR', plugin_dir_path( __FILE__) );
		define('MAGEE_RESTAURANT_VER','1.0.0');
		if ( ! function_exists( 'tgmpa' ) ) {
			require_once ( plugin_dir_path( __FILE__ ).'inc/class-tgm-plugin-activation.php');
			}
		require_once ( plugin_dir_path( __FILE__ ).'inc/core.php');
		require_once ( plugin_dir_path( __FILE__ ).'inc/class-shortcodes.php');
		require_once ( plugin_dir_path( __FILE__ ).'inc/class-settings.php');
		require_once ( plugin_dir_path( __FILE__ ).'inc/cart-tab.php');
		add_action('tgmpa_register',array( $this, 'mgrt_plugin_register_required_plugins' ));
		add_filter('widget_text', 'do_shortcode'); 
		if(is_admin()){
		add_action( 'admin_enqueue_scripts', array( $this, 'mgrt_admin_scripts' ) );	
		}else{
			add_action( 'wp_enqueue_scripts', array( $this, 'mgrt_front_scripts' ) );	
			}
		
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		
		}

	public static function init() {
		
	    load_plugin_textdomain( 'magee-restaurant', false,  dirname( plugin_basename( dirname( __FILE__ ) ) ). '/languages/'  );
		
        }
	
	public function mgrt_admin_scripts(){
		wp_enqueue_script('thickbox');
	    wp_enqueue_style('thickbox');
	    wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('mgrt_admin_js',MAGEE_RESTAURANT_URL.'assets/js/admin.js',array( 'jquery'),'',false);	
		}
	public function mgrt_front_scripts(){
		
		wp_enqueue_style('mgrt_style',MAGEE_RESTAURANT_URL.'assets/css/magee-restaurant.css','',MAGEE_RESTAURANT_VER,false);
		wp_enqueue_style('prettyPhoto',MAGEE_RESTAURANT_URL.'assets/css/prettyPhoto.css','',MAGEE_RESTAURANT_VER,false);
			
		wp_enqueue_script('mgrt_front_js',MAGEE_RESTAURANT_URL.'assets/js/magee-restaurant.js',array( 'jquery'),'',false);	
		wp_enqueue_script('jquery.prettyPhoto',MAGEE_RESTAURANT_URL.'assets/js/jquery.prettyPhoto.js',array( 'jquery'),'3.1.6',false);

		}	
	/**
		 * Function to get the default shortcode param values applied.
		 */
    public static function set_shortcode_defaults( $defaults, $args ) {
			
			if( ! $args ) {
				$$args = array();
			}
		
			$args = shortcode_atts( $defaults, $args );		
		
			foreach( $args as $key => $value ) {
				if( $value == '' || 
					$value == '|' 
				) {
					$args[$key] = $defaults[$key];
				}
			}

			return $args;
		
		}	
	
	
		/**
 * Register the required plugins for this plugin.
 *
 *  <snip />
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function mgrt_plugin_register_required_plugins(){
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin bundled with a theme.
		array(
			'name'               => __('WooCommerce','magee-restaurant'), // The plugin name.
			'slug'               => 'woocommerce', // The plugin slug (typically the folder name).
			'source'             => esc_url('https://downloads.wordpress.org/plugin/woocommerce.zip'), // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'id'           => 'woocommerce', 
		),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'magee-restaurant',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'magee-restaurant-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );

}
		
	
    }
	
new Magee_Restaurant();