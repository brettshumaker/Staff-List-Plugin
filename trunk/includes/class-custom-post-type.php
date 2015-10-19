<?php
/**
 * The custom post type helper class is based on the tutorial here:
 * http://code.tutsplus.com/articles/custom-post-type-helper-class--wp-25104
 *
 * For more information about custom post types and the arguments used visit:
 * 		http://justintadlock.com/archives/2010/04/29/custom-post-types-in-wordpress
 *		http://codex.wordpress.org/Post_Types
 */

class Custom_Post_Type
{
	public $post_type_name;
	public $post_type_args;
	public $post_type_labels;
	public $post_type_nicename;
	public $post_type_prefix;

	/* Class constructor */
	public function __construct( $name='', $prefix='wpmon', $args = array(), $labels = array(), $nicename = '', $hide_extra = false )
	{
		$post_type_prefix = $this->post_type_prefix = $prefix;

		// Don't do any of this if $name comes in empty
		// (means the user just wants access to public functions like metaboxes or taxonomies)
		if ($name != '') {
			// Set some important variables
			$this->post_type_name    	= $post_type_prefix . '_' . strtolower( str_replace( ' ', '_', $name ) );
			$this->post_type_args    	= $args;
			$this->post_type_labels  	= $labels;
			$this->post_type_nicename	= $nicename;

			// Add action to register the post type, if the post type does not already exist & the name is set.
			if( isset($this->post_type_name) && ! post_type_exists( $this->post_type_name ) )
			{
				add_action( 'init', array( &$this, 'register_post_type' ) );
			}

			if( $hide_extra === true ) {
				add_action('admin_print_styles-post.php', array( $this, 'hide_extras') );
				add_action('admin_print_styles-post-new.php', array( $this, 'hide_extras') );
			}
		}

		// Listen for the save post hook
		$this->save();
	}

	/* Method which registers the post type */
	public function register_post_type()
	{
		//Capitilize the words and make it plural
		//$name       = ucwords( str_replace( '_', ' ', $this->post_type_name ) );
		$name 		= $this->post_type_nicename;
		$plural     = self::pluralize( $name );

		// We set the default labels based on the post type name and plural. We overwrite them with the given labels.
		$labels = array_merge(

		// Default
			array(
				'name'                  => _x( $plural, 'post type general name' ),
				'singular_name'         => _x( $name, 'post type singular name' ),
				'add_new'               => _x( 'Add New', strtolower( $name ) ),
				'add_new_item'          => __( 'Add New ' . $name ),
				'edit_item'             => __( 'Edit ' . $name ),
				'new_item'              => __( 'New ' . $name ),
				'all_items'             => __( 'All ' . $plural ),
				'view_item'             => __( 'View ' . $name ),
				'search_items'          => __( 'Search ' . $plural ),
				'not_found'             => __( 'No ' . strtolower( $plural ) . ' found'),
				'not_found_in_trash'    => __( 'No ' . strtolower( $plural ) . ' found in Trash'),
				'parent_item_colon'     => '',
				'menu_name'             => $plural
			),

			// Given labels
			$this->post_type_labels

		);

		// Same principle as the labels. We set some defaults and overwrite them with the given arguments.
		$args = array_merge(

		// Default
			array(
				'label'                 => $plural,
				'labels'                => $labels,
				'public'                => true,
				'show_ui'               => true,
				'supports'              => array( 'title', 'editor' ),
				'show_in_nav_menus'     => true,
				'_builtin'              => false,
			),

			// Given args
			$this->post_type_args

		);

		// Register the post type
		register_post_type( $this->post_type_name, $args );
	}

	/* Method to attach the taxonomy to the post type */
	public function add_taxonomy( $name, $args = array(), $labels = array() )
	{
		if( ! empty( $name ) )
		{
			// We need to know the post type name, so the new taxonomy can be attached to it.
			$post_type_name = $this->post_type_name;
			$post_type_prefix = $this->post_type_prefix;

			// Taxonomy properties
			$taxonomy_name      = $post_type_prefix . '_' . strtolower( str_replace( ' ', '_', $name ) );
			$taxonomy_labels    = $labels;
			$taxonomy_args      = $args;

			if( ! taxonomy_exists( $taxonomy_name ) )
			{
				//Capitilize the words and make it plural
				$name       = ucwords( str_replace( '_', ' ', $name ) );
				$plural     = self::pluralize( $name );

				// Default labels, overwrite them with the given labels.
				$labels = array_merge(

				// Default
					array(
						'name'                  => _x( $plural, 'taxonomy general name' ),
						'singular_name'         => _x( $name, 'taxonomy singular name' ),
						'search_items'          => __( 'Search ' . $plural ),
						'all_items'             => __( 'All ' . $plural ),
						'parent_item'           => __( 'Parent ' . $name ),
						'parent_item_colon'     => __( 'Parent ' . $name . ':' ),
						'edit_item'             => __( 'Edit ' . $name ),
						'update_item'           => __( 'Update ' . $name ),
						'add_new_item'          => __( 'Add New ' . $name ),
						'new_item_name'         => __( 'New ' . $name . ' Name' ),
						'menu_name'             => __( $name ),
					),

					// Given labels
					$taxonomy_labels

				);

				// Default arguments, overwritten with the given arguments
				$args = array_merge(

				// Default
					array(
						'label'                 => $plural,
						'labels'                => $labels,
						'public'                => true,
						'show_ui'               => true,
						'show_in_nav_menus'     => true,
						'_builtin'              => false,
					),

					// Given
					$taxonomy_args

				);

				// Add the taxonomy to the post type
				add_action( 'init',
					function() use( $taxonomy_name, $post_type_name, $args )
					{
						register_taxonomy( $taxonomy_name, $post_type_name, $args );
					}
				);
			}
			else
			{
				add_action( 'init',
					function() use( $taxonomy_name, $post_type_name )
					{
						register_taxonomy_for_object_type( $taxonomy_name, $post_type_name );
					}
				);
			}
		}
	}

	/* Attaches meta boxes to the post type */
	public function add_meta_box( $title, $fields = array(), $context = 'normal', $priority = 'default', $atts = array(), $shortcode_text = '', $seamless = false )
	{
		if( ! empty( $title ) )
		{
			// We need to know the Post Type name again
			$post_type_name = $this->post_type_name;
			$post_type_nicename = $this->post_type_nicename;

			// Meta variables
			$box_id         = strtolower( str_replace( ' ', '_', $title ) );
			$box_title      = ucwords( str_replace( '_', ' ', $title ) );
			$box_context    = $context;
			$box_priority   = $priority;

			// Make the fields global
			global $custom_fields;
			$custom_fields[$title] = $fields;

			add_action( 'admin_init',
				function() use( $box_id, $box_title, $post_type_name, $box_context, $box_priority, $fields, $shortcode_text, $post_type_nicename, $atts )
				{
					add_meta_box(
						$box_id,
						$box_title,
						function( $post, $data ) use($box_context, $shortcode_text, $post_type_name, $post_type_nicename, $atts)
						{
							global $post;

							if ( $shortcode_text !== '' ) {
								echo 'Shortcode to display ' . $post_type_nicename . ': <span class="shortcode-display">[' . $shortcode_text . ' id=' . $post->post_name . ' <span id="sc-atts-user-input"></span>]</span>';
							}

							// Nonce field for some validation
							wp_nonce_field( plugin_basename( __FILE__ ), 'custom_post_type' );

							// Get all inputs from $data
							$custom_fields = $data['args'][0];

							// Get the saved values
							$meta = get_post_custom( $post->ID );

							// Check the array and loop through it
							if( ! empty( $custom_fields ) )
							{
								echo '<input type="hidden" name="custom_post_type_value" value="' . $post_type_name . '" />';

								/* Loop through $custom_fields */
								foreach( $custom_fields as $label => $type )
								{
									$field_id_name  = strtolower( str_replace( ' ', '_', $data['id'] ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );

									if(is_array($type)) {
										$field_data = $type['data'];
										$type = $type['type'];
									}

									switch($type) {
										// Add new input types here
										case 'text':
											echo '<label class="wpmon-cpt-text-field ' . $field_id_name . '" for="' . $field_id_name . '">' . $label . '</label> <input type="text" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $meta[$field_id_name][0] . '" />';
											break;
										case 'hidden-text':
											echo '<input type="hidden" class="wpmon-cpt-text-field ' . $field_id_name . '" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value=\'' . $meta[$field_id_name][0] . '\' />';
											break;
										case 'textarea':
											echo '<label class="wpmon-cpt-textarea ' . $field_id_name . '" for="' . $field_id_name . '">' . $label . '</label> <textarea name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '">' . $meta[$field_id_name][0] . '</textarea>';
											break;
										case 'checkbox':
											if ($meta[$field_id_name][0] == '1' || $field_data['checked'] == 'on' ) {
												$checked = 'checked="checked"';
												if ( $meta[$field_id_name][0] == '' ) {
													$not_saved = '<span class="wpmon-cpt-checkbox-span">*Not Saved</span>';
												} else {
													$not_saved = '';
												}
											} else {
												$checked = '';
												$not_saved = '';
											}

											echo '<label class="wpmon-cpt-checkbox-field ' . $field_id_name . '" for="' . $field_id_name . '">' . $label . '</label> <input type="checkbox" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="1" '.$checked.' />' .$not_saved;
											break;
										case 'select':
											if (!is_array($atts['select_options'])) {
												echo '<p>Please add options for the "' . $label . '" select box!</p>';
												break;
											}
											$output = '';
											foreach ($atts['select_options'] as $name => $options) {
												if ($label == $name) {
													$found_select_match = true;
													$output .= '<label class="wpmon-select-box ' . $field_id_name . '" for="' . $field_id_name . '"><br />' . $label . '</label>';
													$output .= '<select name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '">';
													$output .= '<option>- Select -</option>';
													foreach ($options as $label=>$value) {
														if (isset($meta[$field_id_name][0]) && $meta[$field_id_name][0] == $value) {
															$selected = 'selected';
														} else {
															$selected = '';
														}
														$output .= '<option value="' . $value . '" ' . $selected . '>' . $label . '</option>';
													}
													$output .= '</select>';
													echo $output;
												} else {
													$found_select_match = false;
												}
											}
											if (!$found_select_match && $output == '') {
												echo '<p>Please add options for the "' . $label . '" select box!</p>';
											}

											break;
										case 'category-select':
											if (!is_array($atts['category_select'])) {
												echo '<p>Please add options for the "' . $label . '" category select box!</p>';
												break;
											}
											$output = '';
											foreach ($atts['category_select'] as $name => $settings) {
												if ($label == $name) {
													$found_select_match = true;

													if (isset($settings['post_type']) && isset($settings['taxonomy'])) {
														$selected_tax = '';
														if (isset($meta[$field_id_name][0])) $selected_tax = $meta[$field_id_name][0];
														$output  = '<label class="wpmon-cat-select-box ' . $field_id_name . '" for="' . $field_id_name . '"><br />' . $label . '</label>';

														$terms = get_terms($settings['taxonomy'], array('hide_empty' => 0,'order_by' => 'name',));

														$output .= wp_dropdown_categories(array(
															'echo' => 0,
															'hide_empty' => 0,
															'name' => 'custom_meta[' . $field_id_name . ']',
															'order_by' => 'name',
															'selected' => $selected_tax,
															'show_count' => 1,
															'show_option_none' => '- Select -',
															'taxonomy' => $settings['taxonomy']
														));

													}
													echo $output;
												} else {
													$found_select_match = false;
												}
											}
											if (!$found_select_match && $output == '') {
												echo '<p>Please add options for the "' . $label . '" category select box!</p>';
											}

											break;
										case 'category-multi-select':
											if (!is_array($atts['category_multi_select'])) {
												echo '<p>Please add options for the "' . $label . '" category multiple select box!</p>';
												break;
											}
											$output = '';
											foreach ($atts['category_multi_select'] as $name => $settings) {
												if ($label == $name) {
													$found_select_match = true;

													if (isset($settings['post_type']) && isset($settings['taxonomy'])) {
														$selected_tax = '';
														if (isset($meta[$field_id_name][0])) $selected_tax = $meta[$field_id_name][0];
														$output  = '<label class="wpmon-cat-multi-select-box ' . $field_id_name . '" for="' . $field_id_name . '"><br />' . $label . '</label>';

														$terms = get_terms($settings['taxonomy'], array('hide_empty' => 0,'order_by' => 'name',));

														$output .= '<select class="wpmon-cat-multi-select-box" multiple name="custom_meta[' . $field_id_name . '][]" id="' . $field_id_name . '">';
														$output .= '<option value="">- Select -</option>';

														foreach ($terms as $term) {
															if (isset($meta[$field_id_name][0]) && in_array($term->term_id, maybe_unserialize($meta[$field_id_name][0]))) {
																$selected = 'selected';
															} else {
																$selected = '';
															}
															$output .= '<option value="' . $term->term_id . '" ' . $selected . '>' . $term->name . '</option>';
														}
														$output .= '</select>';
													}
													echo $output;
												} else {
													$found_select_match = false;
												}
											}
											if (!$found_select_match && $output == '') {
												echo '<p>Please add options for the "' . $label . '" category multiple select box!</p>';
											}

											break;
										case 'date':
											echo '<label class="wpmon-cpt-date-field ' . $field_id_name . '" for="' . $field_id_name . '">' . $label . '</label> <input type="text" class="wpmon-cpt-date-field" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $meta[$field_id_name][0] . '" />';
											break;
										case 'time':
											echo '<label class="wpmon-cpt-time-field ' . $field_id_name . '" for="' . $field_id_name . '">' . $label . '</label> <input type="text" class="wpmon-cpt-time-field" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $meta[$field_id_name][0] . '" />';
											break;
										case 'custom-post-type-select':
											if (!is_array($atts['custom-post-type-select'])) {
												echo '<p>Please add content for this Custom Post Type Select Box!</p>';
												break;
											}

											foreach ($atts['custom-post-type-select'] as $cpt_select_id => $select_opts) {
												if ($label == $cpt_select_id) {
													$found_select_match = true;

													$post_type_select = $select_opts['post_type'];
													$default_label = (!isset($select_opts['default_label']) ? '- Select -' : $select_opts['default_label']);

													$output = '<label class="wpmon-cpt-select"><span class="wpmon-cpt-select customize-post-dropdown">' . esc_html( $label ) . '</span>
											                    <select name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '">
											                    	<option>' . $default_label . '</option>';

													$args = array('post_type'=>$post_type_select);

													foreach ($select_opts['args'] as $key => $value) {
														$args[$key] = $value;
													}

													global $post;
													$temp_post = $post;

													$cpt_query = new WP_Query($args);
													if ( $cpt_query->have_posts() ) {
														while ( $cpt_query->have_posts() ) : $cpt_query->the_post();
															if ($meta[$field_id_name][0] == $post->ID) {
																$selected = 'selected';
															} else {
																$selected = '';
															}
															$output .= '<option value="' . $post->ID . '" ' . $selected . '>' . $post->post_title . '</option>';
														endwhile;
													}
													$output .= '</select>';
													$output .= '</label>';
													//echo '<pre>' . print_r($post, true) . '</pre>';
													wp_reset_postdata();
													$post = $temp_post;
													//echo '<pre>' . print_r($post, true) . '</pre>';
													echo $output;
												} else {
													$found_select_match = false;
												}
											}
											if (!$found_select_match && $output == '') {
												echo '<p>Please add content for this Custom Post Type Select Box!</p>';
											}
											break;
										case 'content-url':
											if (!isset($meta[$field_id_name][0])) $meta[$field_id_name][0] = '';
											echo '<div class="wpmon-link-selector-outer">';
											echo '<label class="wpmon-cpt-content-url ' . $field_id_name . '" for="' . $field_id_name . '">' . $label . '</label>';
											echo '<input class="content_link_field" type="text" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $meta[$field_id_name][0] . '" />';
											echo '<button class="url-link-btn button-primary">Set URL</button>';
											echo '</div>';
											break;
										case 'html-block':
											if (!is_array($atts['html_block'])) {
												echo '<p>Please add content for this HTML block area!</p>';
												break;
											}
											$output = '';
											foreach ($atts['html_block'] as $field_id => $content) {
												if ($label == $field_id) {
													$found_select_match = true;

													$output  = '<div class="html-block '.sanitize_title($field_id).'">';
													$output .= '<h3>' . $content['heading'] . '</h3>';
													$output .= apply_filters('the_content', $content['html']);
													$output .= '</div>';

													echo $output;
												} else {
													$found_select_match = false;
												}
											}
											if (!$found_select_match && $output == '') {
												echo '<p>Please add content for this HTML block area!</p>';
											}
											break;
										case 'media':
											$has_image = false;

											// Do this to prevent 'undefined index' warning
											if (isset($meta[$field_id_name])) {
												$has_image = true;

												$image_attributes = wp_get_attachment_image_src( $meta[$field_id_name][0], 'medium');
											} else {
												$meta[$field_id_name][0] = '';
											}
											echo '<div class="custom_uploader">';
											if ($has_image) {
												echo '<a href="#" class="custom_media_add"><img class="custom_media_image" src="'. $image_attributes[0] .'" style="'. ( ! $meta[$field_id_name][0] ? 'display:none;' : '' ) .'" /></a>';
											}
											echo '<a href="#" class="custom_media_add" style="'. (! $meta[$field_id_name][0] ? '' : 'display:none;') .'">Set ' . $label . ' Image</a>';
											echo '<a href="#" class="custom_media_remove" style="'. ( ! $meta[$field_id_name][0] ? 'display:none;' : '' ) .'">Remove ' . $label . ' Image</a>';
											echo '<input class="custom_media_id" type="hidden" name="custom_meta[' . $field_id_name . ']" value="'. $meta[$field_id_name][0] .'">';
											echo '</div>';
											break;
									}
								}
							}
						},
						$post_type_name,
						$box_context,
						$box_priority,
						array( $fields )
					);
				}
			);

			if  ( $seamless === true ) {
				add_filter( 'postbox_classes_' . $post_type_name . '_' . $box_id, array( $this, 'add_metabox_classes' ) );
			}
		}

	}

	public function add_metabox_classes( $classes = array() ) {
		$add_classes = array('acf_postbox', 'no_box');
		foreach ( $add_classes as $class ) {
			if ( !in_array( $class, $classes ) ) {
				$classes[] = sanitize_html_class( $class );
			}
		}
		return $classes;
	}

	/* Listens for when the post type being saved */
	public function save()
	{
		// Need the post type name again
		$post_type_name = $this->post_type_name;

		add_action( 'save_post',
			function() use( $post_type_name )
			{
				// Deny the WordPress autosave function
				if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

				if ( !isset($_POST['custom_post_type']) || ! wp_verify_nonce( $_POST['custom_post_type'], plugin_basename(__FILE__) ) ) return;

				global $post;

				if( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == $_POST['custom_post_type_value'] )
				{
					global $custom_fields;

					// Loop through each meta box
					foreach( $custom_fields as $title => $fields )
					{
						// Loop through all fields
						foreach( $fields as $label => $type )
						{
							$field_id_name  = strtolower( str_replace( ' ', '_', $title ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );

							update_post_meta( $post->ID, $field_id_name, $_POST['custom_meta'][$field_id_name] );
						}

					}
				}
			}
		);
	}

	/* Hides permalink, Edit Permalink, View Post section */
	public function hide_extras() {
		global $post_type;
		if($post_type == $this->post_type_name) {
			echo '<style type="text/css">#edit-slug-box,#view-post-btn,#post-preview,.updated p a{display: none;}</style>';
		}
	}

	public static function pluralize( $string )
	{
		$last = $string[strlen( $string ) - 1];

		if( $last == 'y' )
		{
			$cut = substr( $string, 0, -1 );
			//convert y to ies
			$plural = $cut . 'ies';
		}
		else
		{
			// just attach an s
			$plural = $string . 's';
		}

		return $plural;
	}
}