<?php
/**
* Plugin Name: Testimonial Master
* Plugin URI: http://mylocalwebstop.com
* Description: Use this plugin to enter and display testimonials
* Author: Frank Corso
* Author URI: http://mylocalwebstop.com
* Version: 0.2.1
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
* @version 0.2.1
* @copyright 2015 My Local Webstop
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This class is the main class of the plugin
*
* When loaded, it loads the included plugin files and add functions to hooks or filters. The class also handles the admin menu
*
* @since 0.2.0
*/
class MLWTestimonialMaster
{
    /**
     * TM Version Number
     *
     * @var string
     * @since 0.2.0
     */
    public $version = '0.2.0';
    
    /**
  	  * Main Construct Function
  	  *
  	  * Call functions within class
  	  *
  	  * @since 0.2.0
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
  	  * @since 0.2.0
  	  * @return void
  	  */
    public function load_dependencies()
    {
      include("php/tm_adverts.php");
      include("php/tm_admin_page.php");
      include("php/tm_shortcodes.php");
      include("php/tm_update.php");
      include("php/tm_widgets.php");
      include("php/tm_help_page.php");
      include("php/tm_about_page.php");
    }

    /**
  	  * Add Hooks
  	  *
  	  * Adds functions to relavent hooks and filters
  	  *
  	  * @since 0.2.0
  	  * @return void
  	  */
    public function add_hooks()
    {
      add_action('admin_menu', array( $this, 'setup_admin_menu'));
      add_action('plugins_loaded',  array( $this, 'setup_translations'));
      add_action('admin_head', array( $this, 'admin_head'), 900);
      add_action('init', array( $this, 'register_post_types'));
      add_action('init', 'tm_update');
      add_action('widgets_init', create_function('', 'return register_widget("TMRandomWidget");'));
    }

    /**
     * Creates Custom Post Types
     *
     * Creates custom post type for plugins
     *
     * @since 0.2.0
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
  	  * @since 0.2.0
  	  * @return void
  	  */
  	public function setup_admin_menu()
  	{
  		if (function_exists('add_menu_page'))
  		{
        add_menu_page('Testimonial Master', __('Testimonials','testmonial-master'), 'manage_options', __FILE__, array('TMAdminPage','generate_page'), 'dashicons-star-filled');
    		add_submenu_page(__FILE__, __('Help','testmonial-master'), __('Help','testmonial-master'), 'manage_options', 'tm_help', array('TMHelpPage','generate_page'));
      }
      add_dashboard_page(
				__( 'TM About', 'testmonial-master' ),
				__( 'TM About', 'testmonial-master' ),
				'manage_options',
				'tm_about',
				array('TMAboutPage', 'generate_page')
			);
    }

    /**
  	 * Removes Unnecessary Admin Page
  	 *
  	 * Removes the update, quiz settings, and quiz results pages from the Quiz Menu
  	 *
  	 * @since 0.2.0
  	 * @return void
  	 */
  	public function admin_head()
  	{
  		remove_submenu_page( 'index.php', 'tm_about' );
  	}

    /**
  	  * Loads the plugin language files
  	  *
  	  * @since 0.2.0
  	  * @return void
  	  */
  	public function setup_translations()
  	{
  		load_plugin_textdomain( 'testimonial-master', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  	}
}

$mlwTestimonialMaster = new MLWTestimonialMaster();
?>
