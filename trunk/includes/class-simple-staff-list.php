<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.17
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/includes
 * @author     Brett Shumaker <brettshumaker@gmail.com>
 */
class Simple_Staff_List {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.17
	 * @access   protected
	 * @var      Simple_Staff_List_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.17
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.17
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.17
	 */
	public function __construct() {

		$this->plugin_name = 'simple-staff-list';
		$this->version     = '2.2.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Simple_Staff_List_Loader. Orchestrates the hooks of the plugin.
	 * - Simple_Staff_List_I18n. Defines internationalization functionality.
	 * - Simple_Staff_List_Admin. Defines all hooks for the admin area.
	 * - Simple_Staff_List_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.17
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * General core functions available everywhere.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sslp-core-functions.php';

		/**
		 * Templating functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sslp-template-functions.php';

		/**
		 * Templating hooks.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/sslp-template-hooks.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-staff-list-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simple-staff-list-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-staff-list-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-simple-staff-list-public.php';

		$this->loader = new Simple_Staff_List_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Simple_Staff_List_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.17
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Simple_Staff_List_I18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.17
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Simple_Staff_List_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_menu' );

		// Maybe flush rewrite rules after staff_member_init.
		$this->loader->add_action( 'wp_ajax_sslp_flush_rewrite_rules', $plugin_admin, 'ajax_flush_rewrite_rules', 20 );

		$this->loader->add_action( 'after_setup_theme', $plugin_admin, 'add_featured_image_support', 10 );
		$this->loader->add_filter( 'default_hidden_meta_boxes', $plugin_admin, 'hide_meta_boxes', 10, 2 );
		$this->loader->add_filter( 'enter_title_here', $plugin_admin, 'staff_member_change_title' );
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'staff_member_featured_image_text' );
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'staff_member_add_meta_boxes' );
		$this->loader->add_filter( 'manage_staff-member_posts_columns', $plugin_admin, 'staff_member_custom_columns' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'staff_member_display_custom_columns' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_staff_member_details' );
		$this->loader->add_action( 'wp_ajax_staff_member_update_post_order', $plugin_admin, 'update_staff_member_order' );
		$this->loader->add_action( 'wp_ajax_staff_member_export', $plugin_admin, 'staff_member_export' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.17
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Simple_Staff_List_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'staff_member_init', 10 );
		$this->loader->add_action( 'init', $plugin_public, 'maybe_flush_rewrite_rules', 20 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.17
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.17
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.17
	 * @return    Simple_Staff_List_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.17
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
