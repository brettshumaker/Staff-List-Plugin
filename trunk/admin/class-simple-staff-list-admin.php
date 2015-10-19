<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.2
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/admin
 * @author     Brett Shumaker <brettshumaker@gmail.com>
 */
class Simple_Staff_List_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.2
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.2
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.2
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Staff_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Staff_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/simple-staff-list-admin.css',
			array(),
			$this->version,
			'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.2
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Staff_List_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Staff_List_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/simple-staff-list-admin.js',
			array( 'jquery' ),
			$this->version,
			false );

	}


	/**
	 * Register admin menu items.
	 *
	 * @since   1.2
	 */
	public function register_menu() {

		// Usage page
		add_submenu_page(
			'edit.php?post_type=staff-member',
			__( 'Simple Staff List Usage', $this->plugin_name ),
			__( 'Usage', $this->plugin_name ),
			'edit_pages',
			'staff-member-usage',
			array( $this, 'display_usage_page' )
		);

	}

	/**
	 * Display Usage page content.
	 *
	 * @since   1.2
	 */
	public function display_usage_page() {
		include_once( 'partials/simple-staff-list-usage-display.php' );
	}

}
