<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.brettshumaker.com
 * @since      1.17
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
	 * Hook suffixes of the plugin's admin pages, as returned by add_submenu_page().
	 *
	 * @since    2.2.7
	 * @access   private
	 * @var      string[] $page_hooks The plugin's admin page hook suffixes.
	 */
	private $page_hooks = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.17
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * Loaded on Staff Member screens only. WordPress before 3.8 also needs it on every
	 * other screen, because it holds the admin menu icon.
	 *
	 * @since    1.17
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( $hook_suffix = '' ) {

		if ( ! $this->is_staff_member_screen( $hook_suffix ) && version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) {
			return;
		}

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/simple-staff-list-admin.css',
			array(),
			$this->version,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * Only the plugin's own pages (Order, Templates, Export…) use it.
	 *
	 * @since    1.17
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_scripts( $hook_suffix = '' ) {

		if ( ! in_array( $hook_suffix, $this->page_hooks, true ) ) {
			return;
		}

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/simple-staff-list-admin.js',
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ),
			$this->version,
			false
		);

	}

	/**
	 * Whether the current admin screen is one of the plugin's pages or a Staff Member screen.
	 *
	 * @since 2.2.7
	 *
	 * @param string $hook_suffix The current admin page.
	 *
	 * @return bool
	 */
	private function is_staff_member_screen( $hook_suffix ) {

		if ( in_array( $hook_suffix, $this->page_hooks, true ) ) {
			return true;
		}

		// The staff list, add/edit screens and the Groups screen.
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		return $screen && 'staff-member' === $screen->post_type;

	}

	/**
	 * Flush Rewrite Rules after saving plugin options
	 *
	 * @since   2.0
	 */
	public function ajax_flush_rewrite_rules() {
		// Check the security nonce before doing anything.
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'sslp_flush_rewrite_rules' ) ) {
			wp_send_json_error();
		}

		// Same capability as the Options page that triggers this request.
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_send_json_error();
		}

		flush_rewrite_rules();
		wp_send_json_success();
	}

	/**
	 * Register admin menu items.
	 *
	 * @since   1.17
	 */
	public function register_menu() {

		// Order page.
		$this->page_hooks[] = add_submenu_page(
			'edit.php?post_type=staff-member',
			__( 'Simple Staff List Order', 'simple-staff-list' ),
			__( 'Order', 'simple-staff-list' ),
			'edit_pages',
			'staff-member-order',
			array( $this, 'display_order_page' )
		);

		// Templates page.
		$this->page_hooks[] = add_submenu_page(
			'edit.php?post_type=staff-member',
			__( 'Display Templates', 'simple-staff-list' ),
			__( 'Templates', 'simple-staff-list' ),
			'edit_pages',
			'staff-member-template',
			array( $this, 'display_templates_page' )
		);

		// Usage page.
		$this->page_hooks[] = add_submenu_page(
			'edit.php?post_type=staff-member',
			__( 'Simple Staff List Usage', 'simple-staff-list' ),
			__( 'Usage', 'simple-staff-list' ),
			'edit_pages',
			'staff-member-usage',
			array( $this, 'display_usage_page' )
		);

		// Options page.
		$this->page_hooks[] = add_submenu_page(
			'edit.php?post_type=staff-member',
			__( 'Simple Staff List Options', 'simple-staff-list' ),
			__( 'Options', 'simple-staff-list' ),
			'edit_pages',
			'staff-member-options',
			array( $this, 'display_options_page' )
		);

		// Export.
		$this->page_hooks[] = add_submenu_page(
			'edit.php?post_type=staff-member',
			__( 'Simple Staff List Export', 'simple-staff-list' ),
			__( 'Export', 'simple-staff-list' ),
			'export',
			'staff-member-export',
			array( $this, 'display_export_page' )
		);

	}

	/**
	 * Display Order page content.
	 *
	 * @since   1.17
	 */
	public function display_order_page() {
		include_once 'partials/simple-staff-list-order-display.php';
	}

	/**
	 * Display Template page content.
	 *
	 * @since   1.17
	 */
	public function display_templates_page() {
		include_once 'partials/simple-staff-list-template-display.php';
	}

	/**
	 * Display Usage page content.
	 *
	 * @since   1.17
	 */
	public function display_usage_page() {
		include_once 'partials/simple-staff-list-usage-display.php';
	}

	/**
	 * Display Usage page content.
	 *
	 * @since   1.17
	 */
	public function display_options_page() {
		include_once 'partials/simple-staff-list-options-display.php';
	}

	/**
	 * Display Usage page content.
	 *
	 * @since   1.20
	 */
	public function display_export_page() {
		include_once 'partials/simple-staff-list-export-display.php';
	}

	/**
	 * Hide unwanted meta boxes on staff member screen.
	 *
	 * @param array  $hidden Array of hidden meta boxes.
	 * @param object $screen Contains information about the current screen.
	 *
	 * @since 1.17
	 *
	 * @return array
	 */
	public function hide_meta_boxes( $hidden, $screen ) {

		if ( 'staff-member' === $screen->id ) {
			$hidden = array( 'postexcerpt' );
		}

		return $hidden;

	}

	/**
	 * Change name of title meta box on staff member screen.
	 *
	 * @since 1.17
	 *
	 * @param string $title Title box text.
	 *
	 * @return mixed Title box text
	 */
	public function staff_member_change_title( $title ) {

		$screen = get_current_screen();

		if ( 'staff-member' === $screen->post_type ) {
			$title = __( 'Staff Name', 'simple-staff-list' );
		}

		return $title;
	}

	/**
	 * Add theme support for post thumbnails
	 *
	 * @since 2.0
	 */
	public function add_featured_image_support() {

		add_theme_support( 'post-thumbnails', array( 'staff-member' ) );

	}

	/**
	 * Register staff member meta boxes.
	 *
	 * @since 1.17
	 */
	public function staff_member_add_meta_boxes() {

		add_meta_box(
			'staff-member-info',
			__( 'Staff Member Info', 'simple-staff-list' ),
			array( $this, 'staff_member_info_meta_box' ),
			'staff-member',
			'normal',
			'high'
		);

		add_meta_box(
			'staff-member-bio',
			__( 'Staff Member Bio', 'simple-staff-list' ),
			array( $this, 'staff_member_bio_meta_box' ),
			'staff-member',
			'normal',
			'high'
		);

	}

	/**
	 * Register staff member custom columns.
	 *
	 * @since 1.17
	 *
	 * @param array $cols Array that holds the column data.
	 *
	 * @return array Custom columns
	 */
	public function staff_member_custom_columns( $cols ) {

		$cols = array(
			'cb'                  => '<input type="checkbox" />',
			'id'                  => __( 'Staff ID', 'simple-staff-list' ),
			'title'               => __( 'Name', 'simple-staff-list' ),
			'photo'               => __( 'Photo', 'simple-staff-list' ),
			'_staff_member_title' => __( 'Position', 'simple-staff-list' ),
			'_staff_member_email' => __( 'Email', 'simple-staff-list' ),
			'_staff_member_phone' => __( 'Phone', 'simple-staff-list' ),
			'_staff_member_bio'   => __( 'Bio', 'simple-staff-list' ),
		);

		return $cols;
	}

	/**
	 * Display staff member info meta box.
	 *
	 * @since 1.17
	 */
	public function staff_member_info_meta_box() {

		global $post;

		$custom              = get_post_custom( $post->ID );
		$_staff_member_title = isset( $custom['_staff_member_title'][0] ) ? $custom['_staff_member_title'][0] : '';
		$_staff_member_email = isset( $custom['_staff_member_email'][0] ) ? $custom['_staff_member_email'][0] : '';
		$_staff_member_phone = isset( $custom['_staff_member_phone'][0] ) ? $custom['_staff_member_phone'][0] : '';
		$_staff_member_fb    = isset( $custom['_staff_member_fb'][0] ) ? $custom['_staff_member_fb'][0] : '';
		$_staff_member_tw    = isset( $custom['_staff_member_tw'][0] ) ? $custom['_staff_member_tw'][0] : '';
		?>

		<div class="sslp_admin_wrap">
			<label for="_staff_member_title">
				<?php esc_html_e( 'Position:', 'simple-staff-list' ); ?>
				<input type="text"
						name="_staff_member_title"
						id="_staff_member_title"
						placeholder="<?php esc_attr_e( 'Staff Member\'s Position', 'simple-staff-list' ); ?>"
						value="<?php echo esc_attr( $_staff_member_title ); ?>"/>
			</label>
			<label for="_staff_member_email">
				<?php esc_html_e( 'Email:', 'simple-staff-list' ); ?>
				<?php // An older saved value that isn't a valid email would fail the email field's validation and block saving, so keep a text field for it. ?>
				<input type="<?php echo ( '' === $_staff_member_email || is_email( $_staff_member_email ) ) ? 'email' : 'text'; ?>"
						name="_staff_member_email"
						id="_staff_member_email"
						placeholder="<?php esc_attr_e( 'Staff Member\'s Email', 'simple-staff-list' ); ?>"
						value="<?php echo esc_attr( $_staff_member_email ); ?>"/>
			</label>
			<label for="_staff_member_phone">
				<?php esc_html_e( 'Phone:', 'simple-staff-list' ); ?>
				<input type="tel"
						name="_staff_member_phone"
						id="_staff_member_phone"
						placeholder="<?php esc_attr_e( 'Staff Member\'s Phone', 'simple-staff-list' ); ?>"
						value="<?php echo esc_attr( $_staff_member_phone ); ?>"/>
			</label>
			<label for="_staff_member_fb">
				<?php esc_html_e( 'Facebook URL:', 'simple-staff-list' ); ?>
				<?php // A text field with the URL keyboard, not type="url": "facebook.com/name" is accepted and saved with https:// added. ?>
				<input type="text"
						inputmode="url"
						name="_staff_member_fb"
						id="_staff_member_fb"
						placeholder="<?php esc_attr_e( 'Staff Member\'s Facebook URL', 'simple-staff-list' ); ?>"
						value="<?php echo esc_url( sslp_add_url_scheme( $_staff_member_fb ) ); ?>"/>
			</label>
			<label for="_staff_member_tw">
				<?php esc_html_e( 'Twitter Username:', 'simple-staff-list' ); ?>
				<input type="text"
						name="_staff_member_tw"
						id="_staff_member_tw"
						placeholder="<?php esc_attr_e( 'Staff Member\'s Twitter Name', 'simple-staff-list' ); ?>"
						value="<?php echo esc_attr( $_staff_member_tw ); ?>"/>
			</label>
			
			<?php do_action( 'sslp_after_staff_member_admin_fields', absint( $post->ID ) ); ?>
			
		</div>
		<?php

	}

	/**
	 * Display staff member bio meta box.
	 *
	 * @since 1.17
	 */
	public function staff_member_bio_meta_box() {

		global $post;

		$custom            = get_post_custom( $post->ID );
		$_staff_member_bio = isset( $custom['_staff_member_bio'][0] ) ? $custom['_staff_member_bio'][0] : '';

		wp_editor(
			$_staff_member_bio,
			'_staff_member_bio',
			$settings = array(
				'textarea_rows' => 8,
				'media_buttons' => false,
				'tinymce'       => true, // Disables actual TinyMCE buttons // This makes the rich content editor.
				'quicktags'     => true, // Use QuickTags for formatting    // work within a metabox.
			)
		);
		?>

		<p class="sslp-note">**Note: HTML is allowed.</p>

		<?php wp_nonce_field( 'sslp_post_nonce', 'sslp_add_edit_staff_member_noncename' ); ?>

		<?php

	}

	/**
	 * Display staff member custom columns.
	 *
	 * @since 1.17
	 *
	 * @param string $column The column id string.
	 */
	public function staff_member_display_custom_columns( $column ) {

		global $post;

		$custom              = get_post_custom();
		$_staff_member_title = isset( $custom['_staff_member_title'][0] ) ? $custom['_staff_member_title'][0] : '';
		$_staff_member_email = isset( $custom['_staff_member_email'][0] ) ? $custom['_staff_member_email'][0] : '';
		$_staff_member_phone = isset( $custom['_staff_member_phone'][0] ) ? $custom['_staff_member_phone'][0] : '';
		$_staff_member_bio   = isset( $custom['_staff_member_bio'][0] ) ? $custom['_staff_member_bio'][0] : '';

		switch ( $column ) {
			case 'id':
				echo esc_html( $post->ID );
				break;
			case 'photo':
				if ( has_post_thumbnail() ) {
					echo get_the_post_thumbnail( $post->ID, array( 75, 75 ) );
				}
				break;
			case '_staff_member_title':
				echo esc_html( $_staff_member_title );
				break;
			case '_staff_member_email':
				echo '<a href="mailto:' . esc_attr( $_staff_member_email ) . '">' . esc_html( $_staff_member_email ) . '</a>';
				break;
			case '_staff_member_phone':
				echo esc_html( $_staff_member_phone );
				break;
			case '_staff_member_bio':
				// The excerpt is HTML (the_excerpt adds <p>), so strip it to plain text rather than showing the tags.
				echo esc_html( wp_strip_all_tags( $this->get_staff_bio_excerpt( $_staff_member_bio, 10 ) ) );
				break;
		}

	}

	/**
	 * Save the staff member details post meta.
	 *
	 * @since 1.17
	 */
	public function save_staff_member_details( $post_id = 0 ) {

		if ( ! isset( $_POST['sslp_add_edit_staff_member_noncename'] ) || ! wp_verify_nonce( $_POST['sslp_add_edit_staff_member_noncename'], 'sslp_post_nonce' ) ) {
			return;
		}

		// Use the ID save_post passes in, not global $post, which can point at a different post.
		if ( ! $post_id && isset( $GLOBALS['post']->ID ) ) {
			$post_id = $GLOBALS['post']->ID;
		}

		$post = get_post( $post_id ); // Local variable; the global $post is left alone.

		if ( ! $post || 'staff-member' !== $post->post_type ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post->ID;
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		update_post_meta(
			$post->ID,
			'_staff_member_bio',
			isset( $_POST['_staff_member_bio'] ) ? wp_kses( $_POST['_staff_member_bio'], apply_filters( 'sslp_staff_member_bio_kses_allowed_html', 'post' ) ) : ''
		);
		update_post_meta(
			$post->ID,
			'_staff_member_title',
			isset( $_POST['_staff_member_title'] ) ? sanitize_text_field( $_POST['_staff_member_title'] ) : ''
		);
		update_post_meta(
			$post->ID,
			'_staff_member_email',
			isset( $_POST['_staff_member_email'] ) ? sanitize_email( $_POST['_staff_member_email'] ) : ''
		);
		update_post_meta(
			$post->ID,
			'_staff_member_phone',
			isset( $_POST['_staff_member_phone'] ) ? sanitize_text_field( $_POST['_staff_member_phone'] ) : ''
		);
		update_post_meta(
			$post->ID,
			'_staff_member_fb',
			isset( $_POST['_staff_member_fb'] ) ? esc_url_raw( sslp_add_url_scheme( $_POST['_staff_member_fb'] ) ) : ''
		);
		update_post_meta(
			$post->ID,
			'_staff_member_tw',
			isset( $_POST['_staff_member_tw'] ) ? sanitize_text_field( $_POST['_staff_member_tw'] ) : ''
		);
		
		do_action( 'sslp_save_staff_member_details', absint( $post->ID ), $_POST );

	}

	/**
	 * Get staff bio excerpt.
	 *
	 * @since 1.17
	 *
	 * @param string $text Bio text.
	 * @param int    $excerpt_length Excerpt length.
	 *
	 * @return mixed
	 */
	public function get_staff_bio_excerpt( $text, $excerpt_length ) {

		global $post;

		if ( ! $excerpt_length || ! is_int( $excerpt_length ) ) {
			$excerpt_length = 20;
		}

		if ( '' !== $text ) {
			$text         = strip_shortcodes( $text );
			$text         = apply_filters( 'the_content', $text );
			$text         = str_replace( ']]>', ']]>', $text );
			$excerpt_more = ' ...';
			$text         = wp_trim_words( $text, $excerpt_length, $excerpt_more );
		}

		return apply_filters( 'the_excerpt', $text );

	}

	/**
	 * Update Staff Member order.
	 *
	 * @since 1.17
	 *
	 * @return mixed
	 */
	public function update_staff_member_order() {
		global $wpdb;

		if ( ! isset( $_POST['nonce'] ) || ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'sslp-order' ) ) ) {
			wp_send_json_error( "Cheatin' uh?" );
		}

		// Same capability as the Order page that sends this request.
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_send_json_error( "Cheatin' uh?" );
		}

		if ( ! isset( $_POST['order'] ) || ! is_array( $_POST['order'] ) ) {
			wp_send_json_error( 'Invalid order.' );
		}

		$order = $_POST['order'];

		/**
		 *    Expect: $sorted = array(
		 *                menu_order => post-XX
		 *            );
		 */
		foreach ( $order as $menu_order => $post_id ) {
			$post_id    = intval( str_ireplace( 'post-', '', $post_id ) );
			$menu_order = intval( $menu_order );

			// Only ever reorder staff members.
			if ( 'staff-member' !== get_post_type( $post_id ) ) {
				continue;
			}
			wp_update_post(
				array(
					'ID'         => $post_id,
					'menu_order' => $menu_order,
				)
			);
		}

		wp_send_json_success( 'Order updated' );
	}

	/**
	 * Staff Member Export
	 *
	 * @since 1.20
	 *
	 * @return mixed
	 */
	public function staff_member_export() {

		// Check the security nonce.
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'sslp-export-nonce' ) ) {
			wp_send_json_error( 'Refresh the page and try again.' );
		}

		// Same capability as the Export page.
		if ( ! current_user_can( 'export' ) ) {
			wp_send_json_error( 'Sorry, you are not allowed to export staff members.' );
		}

		$staff_ids = get_posts(
			array(
				'post_type'      => 'staff-member',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'fields'         => 'ids',
			)
		);

		if ( ! $staff_ids ) {
			wp_send_json_error( 'No data to export.' );
		}

		// The plugin's own fields, in the order earlier exports usually listed them.
		$meta_keys = array(
			'_staff_member_bio',
			'_staff_member_title',
			'_staff_member_email',
			'_staff_member_phone',
			'_staff_member_fb',
			'_staff_member_tw',
		);

		// Extra fields saved through the sslp_save_staff_member_details hook get their own columns.
		// They're collected from every staff member so each row has the same columns.
		$extra_keys = array();
		foreach ( $staff_ids as $staff_id ) {
			foreach ( array_keys( get_post_custom( $staff_id ) ) as $key ) {
				if ( 0 === strpos( $key, '_staff_member_' ) && ! in_array( $key, $meta_keys, true ) ) {
					$extra_keys[ $key ] = true;
				}
			}
		}
		$extra_keys = array_keys( $extra_keys );
		sort( $extra_keys );
		$meta_keys = array_merge( $meta_keys, $extra_keys );

		$header = array( 'Staff Member Name', 'Staff Member Image URL' );
		foreach ( $meta_keys as $key ) {
			$header[] = ucwords( trim( str_replace( '_', ' ', $key ) ) );
		}
		$header[] = 'Groups';

		$rows = array( $header );

		foreach ( $staff_ids as $staff_id ) {
			$custom = get_post_custom( $staff_id );

			$image_obj = wp_get_attachment_image_src( get_post_thumbnail_id( $staff_id ), 'full', false );

			$row = array(
				get_the_title( $staff_id ),
				$image_obj ? $image_obj[0] : '',
			);

			foreach ( $meta_keys as $key ) {
				$row[] = isset( $custom[ $key ][0] ) ? trim( $custom[ $key ][0] ) : '';
			}

			$group_names = array();
			$groups      = get_the_terms( $staff_id, 'staff-member-group' );
			if ( is_array( $groups ) ) {
				foreach ( $groups as $group ) {
					$group_names[] = $group->name;
				}
			}
			$row[] = implode( ', ', $group_names );

			$rows[] = $row;
		}

		/**
		 * Filters whether exported cells that a spreadsheet app would read as a formula
		 * are prefixed with an apostrophe so they're shown as text.
		 *
		 * @since 2.2.7
		 *
		 * @param bool $escape Whether to prefix the cells. Default true.
		 */
		$escape_formulas = apply_filters( 'sslp_export_escape_formulas', true );

		$csv = '';
		foreach ( $rows as $row ) {
			$cells = array();
			foreach ( $row as $cell ) {
				$cell = (string) $cell;
				if ( $escape_formulas ) {
					$cell = $this->escape_csv_formula( $cell );
				}
				// RFC 4180: quote every cell and double any quotes inside it. Line breaks stay inside the quotes.
				$cells[] = '"' . str_replace( '"', '""', $cell ) . '"';
			}
			$csv .= implode( ',', $cells ) . "\n";
		}

		// Send the CSV straight back to the browser.
		wp_send_json_success(
			array(
				'created_file' => false,
				'content'      => $csv,
				'filename'     => 'staff-member-export-' . gmdate( 'Y-m-d-G-i' ) . '.csv',
			)
		);

	}

	/**
	 * Prefix a CSV cell with an apostrophe if a spreadsheet app would read it as a formula.
	 *
	 * Values made only of digits, spaces and + - ( ) . are left alone, so phone numbers
	 * such as "+44 20 7946 0958" export unchanged. They can't call a function.
	 *
	 * @since 2.2.7
	 *
	 * @param string $cell The cell value.
	 *
	 * @return string
	 */
	private function escape_csv_formula( $cell ) {
		if ( '' === $cell || false === strpos( "=+-@\t\r", $cell[0] ) ) {
			return $cell;
		}

		if ( preg_match( '/^[+\-][0-9 ().+\-]*$/', $cell ) ) {
			return $cell;
		}

		return "'" . $cell;
	}

}
