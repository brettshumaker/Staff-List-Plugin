<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/public
 * @author     Brett Shumaker <brettshumaker@gmail.com>
 */
class Simple_Staff_List_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.17
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.17
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The default attributes for the shortcode
	 *
	 * @since  1.17
	 * @access  private
	 * @var array $simple_staff_list_shortcode_atts Attributes passed in with the shortcode.
	 */
	private $simple_staff_list_shortcode_atts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.17
	 * @param    string $plugin_name       The name of the plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name                               = $plugin_name;
		$this->version                                   = $version;
		$this->simple_staff_list_shortcode_atts          = array();
		$this->simple_staff_list_shortcode_atts_defaults = array(
			'id'         => '',
			'group'      => '',
			'wrap_class' => '',
			'order'      => 'ASC',
			'image_size' => 'full',
		);

		$this->staff_member_register_shortcodes();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.17
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

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/simple-staff-list-public.css',
			array(),
			$this->version,
			'all'
		);

		/**
		 * Check to see if we should load the external stylesheet
		 *
		 * @since 1.19
		 */
		if ( 'yes' === get_option( '_staff_listing_write_external_css' ) ) {
			wp_register_style( 'staff-list-custom-css', get_stylesheet_directory_uri() . '/simple-staff-list-custom.css' );
			wp_enqueue_style( 'staff-list-custom-css' );
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.17
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

	}

	/**
	 * Initialize staff member custom post type and taxonomies.
	 *
	 * @since 1.17
	 */
	public function staff_member_init() {

		global $wp_version;

		// Get user options for post type labels.
		if ( ! get_option( '_staff_listing_custom_slug' ) ) {
			$slug = get_option( '_staff_listing_default_slug' );
		} else {
			$slug = get_option( '_staff_listing_custom_slug' );
		}
		if ( ! get_option( '_staff_listing_custom_name_singular' ) ) {
			$singular_name = get_option( '_staff_listing_default_name_singular' );
		} else {
			$singular_name = get_option( '_staff_listing_custom_name_singular' );
		}
		if ( ! get_option( '_staff_listing_custom_name_plural' ) ) {
			$name = get_option( '_staff_listing_default_name_plural' );
		} else {
			$name = get_option( '_staff_listing_custom_name_plural' );
		}

		// TODO Instead of using "Staff" all through here...try to use the custom slug set in options.
		// Set up post type options.
		$labels = array(
			'name'                  => $name,
			'singular_name'         => $singular_name,
			'add_new'               => _x( 'Add New', 'staff member', $this->plugin_name ),
			'add_new_item'          => __( 'Add New Staff Member', $this->plugin_name ),
			'edit_item'             => __( 'Edit Staff Member', $this->plugin_name ),
			'new_item'              => __( 'New Staff Member', $this->plugin_name ),
			'view_item'             => __( 'View Staff Member', $this->plugin_name ),
			'search_items'          => __( 'Search Staff Members', $this->plugin_name ),
			'exclude_from_search'   => true,
			'not_found'             => __( 'No staff members found', $this->plugin_name ),
			'not_found_in_trash'    => __( 'No staff members found in Trash', $this->plugin_name ),
			'parent_item_colon'     => '',
			'all_items'             => __( 'All Staff Members', $this->plugin_name ),
			'menu_name'             => __( 'Staff Members', $this->plugin_name ),
			'featured_image'        => __( 'Staff Photo', $this->plugin_name ),
			'set_featured_image'    => __( 'Set Staff Photo', $this->plugin_name ),
			'remove_featured_image' => __( 'Remove Staff Photo', $this->plugin_name ),
			'use_featured_image'    => __( 'Use Staff Photo', $this->plugin_name ),
		);

		/**
		 * sslp_enable_staff_member_archive
		 * 
		 * Return false on this filter and flush your permalinks to disable the staff-members archive page.
		 * 
		 * @since 2.2.0
		 * 
		 * @param $enabled bool Whether or not the archive page is enabled. Default is true.
		 */
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => true,
			'capability_type'    => 'page',
			'has_archive'        => apply_filters( 'sslp_enable_staff_member_archive', true ),
			'hierarchical'       => false,
			'menu_position'      => 100,
			'rewrite'            => array(
				'slug'       => $slug,
				'with_front' => false,
			),
			'supports'           => array( 'title', 'thumbnail', 'excerpt' ),
			'menu_icon'          => 'dashicons-groups',
		);

		/**
		 * Removing this version check - only 4.7% of WordPress installs are now at or below version 3.8.
		 */
		// if ( version_compare( $wp_version, '3.8', '>=' ) ) {
		// 	$args['menu_icon'] = 'dashicons-groups';
		// }

		// Register post type.
		register_post_type( 'staff-member', $args );

		$group_labels = array(
			'name'              => _x( 'Groups', 'taxonomy general name', $this->plugin_name ),
			'singular_name'     => _x( 'Group', 'taxonomy singular name', $this->plugin_name ),
			'search_items'      => __( 'Search Groups', $this->plugin_name ),
			'all_items'         => __( 'All Groups', $this->plugin_name ),
			'parent_item'       => __( 'Parent Group', $this->plugin_name ),
			'parent_item_colon' => __( 'Parent Group:', $this->plugin_name ),
			'edit_item'         => __( 'Edit Group', $this->plugin_name ),
			'update_item'       => __( 'Update Group', $this->plugin_name ),
			'add_new_item'      => __( 'Add New Group', $this->plugin_name ),
			'new_item_name'     => __( 'New Group Name', $this->plugin_name ),
		);
		register_taxonomy(
			'staff-member-group', array( 'staff-member' ), array(
				'hierarchical' => true,
				'labels'       => $group_labels, /* NOTICE: Here is where the $labels variable is used */
				'show_ui'      => true,
				'query_var'    => true,
				'rewrite'      => array( 'slug' => 'group' ),
			)
		);

	}

	/**
	 * Maybe flush rewrite rules
	 *
	 * @since 2.0
	 */
	public function maybe_flush_rewrite_rules() {

		if ( get_option( '_staff_listing_flush_rewrite_rules_flag' ) ) {

			// Flush the rewrite rules.
			flush_rewrite_rules();

			// Remove our flag.
			delete_option( '_staff_listing_flush_rewrite_rules_flag' );

		}

	}


	/**
	 * Register plugin shortcode(s)
	 *
	 * @since 1.17
	 */
	public function staff_member_register_shortcodes() {

		add_shortcode( 'simple-staff-list', array( $this, 'staff_member_simple_staff_list_shortcode_callback' ) );

	}

	/**
	 * Callback for [simple-staff-list]
	 *
	 * @since 1.17
	 * @param array $atts Array of attributes passed in with the shortcode.
	 */
	public function staff_member_simple_staff_list_shortcode_callback( $atts = array() ) {

		global $sslp_sc_output;

		$this->simple_staff_list_shortcode_atts = shortcode_atts( $this->simple_staff_list_shortcode_atts_defaults, $atts, 'simple-staff-list' );
		include 'partials/simple-staff-list-shortcode-display.php';
		return $sslp_sc_output;

	}

}
