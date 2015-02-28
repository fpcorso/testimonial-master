<?php
/**
* Plugin Name: Testimonial Master
* Plugin URI: http://mylocalwebstop.com
* Description: Use this plugin to enter and display testimonials
* Author: Frank Corso
* Author URI: http://mylocalwebstop.com
* Version: 0.1.0
* Text Domain: testmonial-master
* Domain Path: /languages
*
* Disclaimer of Warranties
* The plugin is provided "as is". My Local Webstop and its suppliers and licensors hereby disclaim all warranties of any kind,
* express or implied, including, without limitation, the warranties of merchantability, fitness for a particular purpose and non-infringement.
* Neither My Local Webstop nor its suppliers and licensors, makes any warranty that the plugin will be error free or that access thereto will be continuous or uninterrupted.
* You understand that you install, operate, and uninstall the plugin at your own discretion and risk.
*
* @author Frank Corso
* @version 0.1.0
* @copyright 2015 My Local Webstop
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This class is the main class of the plugin
*
* When loaded, it loads the included plugin files and add functions to hooks or filters. The class also handles the admin menu
*
* @since 0.1.0
*/
class MLWTestimonialMaster
{
    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.1.0
  	  * @uses MLWTestimonialMaster::load_dependencies() Loads required filed
  	  * @uses MLWTestimonialMaster::add_hooks() Adds actions to hooks and filters
  	  * @return void
  	  */
    function __construct()
    {
      $this->load_dependencies();
      $this->add_hooks();
    }

    /**
  	  * Load File Dependencies
  	  *
  	  * @since 0.1.0
  	  * @return void
  	  */
    public function load_dependencies()
    {
      include("php/tm_adverts.php");
      include("php/tm_admin_page.php");
      include("php/tm_shortcodes.php");
      include("php/mlw_tm_update.php");
      include("php/mlw_tm_widgets.php");
      include("php/mlw_tm_help.php");
    }

    /**
  	  * Add Hooks
  	  *
  	  * Adds functions to relavent hooks and filters
  	  *
  	  * @since 0.1.0
  	  * @return void
  	  */
    public function add_hooks()
    {
      add_action('admin_menu', array( $this, 'setup_admin_menu'));
      add_action('plugins_loaded',  array( $this, 'setup_translations'));
      add_action('admin_head', array( $this, 'admin_head'), 900);
      add_action('init', array( $this, 'register_post_types'));
      add_action('init', 'mlw_tm_update');
      add_action('widgets_init', create_function('', 'return register_widget("Mlw_Tm_Random_Widget");'));
      add_shortcode('mlw_tm_all', 'mlw_tm_all_shortcode');
      add_shortcode('mlw_tm_random', 'mlw_tm_random_shortcode');
    }

    /**
     * Creates Custom Post Types
     *
     * Creates custom post type for plugins
     *
     * @since 0.1.0
     */
    public function register_post_types()
    {
      $labels = array(
  			'name'               => 'Testimonials',
  			'singular_name'      => 'Testimonial',
  			'menu_name'          => 'Testimonial',
  			'name_admin_bar'     => 'Testimonial',
  			'add_new'            => 'Add New',
  			'add_new_item'       => 'Add New Testimonial',
  			'new_item'           => 'New Testimonial',
  			'edit_item'          => 'Edit Testimonial',
  			'view_item'          => 'View Testimonial',
  			'all_items'          => 'All Testimonials',
  			'search_items'       => 'Search Testimonials',
  			'parent_item_colon'  => 'Parent Testimonial:',
  			'not_found'          => 'No Testimonial Found',
  			'not_found_in_trash' => 'No Testimonial Found In Trash'
  		);

      $args = array(
  			'show_ui' => false,
  			'show_in_nav_menus' => false,
  			'labels' => $labels,
  			'publicly_queryable' => false,
  			'exclude_from_search' => true,
  			'label'  => 'Testimonials',
  			'rewrite' => array('slug' => 'testimonial'),
  			'has_archive'        => false,
  			'supports'           => array( 'title', 'editor', 'author' )
  		);

  		register_post_type( 'testimonial', $args );
    }

    /**
  	  * Setup Admin Menu
  	  *
  	  * Creates the admin menu and pages for the plugin and attaches functions to them
  	  *
  	  * @since 0.1.0
  	  * @return void
  	  */
  	public function setup_admin_menu()
  	{
  		if (function_exists('add_menu_page'))
  		{
        add_menu_page('Testimonial Master', 'Testimonials', 'manage_options', __FILE__, 'mlw_tm_generate_admin_page', 'dashicons-star-filled');
    		add_submenu_page(__FILE__, 'Help', 'Help', 'manage_options', 'mlw_tm_help', 'mlw_tm_generate_help_page');
      }
      /*
      add_dashboard_page(
				__( 'WPDT About', 'wordpress-developer-toolkit' ),
				__( 'WPDT About', 'wordpress-developer-toolkit' ),
				'manage_options',
				'wpdt_about',
				array('WPDTAboutPage', 'generate_page')
			);
      */
    }

    /**
  	 * Removes Unnecessary Admin Page
  	 *
  	 * Removes the update, quiz settings, and quiz results pages from the Quiz Menu
  	 *
  	 * @since 4.1.0
  	 * @return void
  	 */
  	public function admin_head()
  	{
  		//remove_submenu_page( 'index.php', 'wpdt_about' );
  	}

    /**
  	  * Loads the plugin language files
  	  *
  	  * @since 0.1.0
  	  * @return void
  	  */
  	public function setup_translations()
  	{
  		load_plugin_textdomain( 'testimonial-master', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  	}
}

$mlwTestimonialMaster = new MLWTestimonialMaster();
?>
