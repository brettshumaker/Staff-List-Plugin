<?php


/*
// Save Post Data
//////////////////////////////*/

//////  Register and write the AJAX callback function to actually update the posts on sort.
/////	
		
		add_action( 'wp_ajax_staff_member_update_post_order', 'sslp_staff_member_update_post_order' );
		
		function sslp_staff_member_update_post_order() {
			global $wpdb;
		
			$post_type     = $_POST['postType'];
			$order        = $_POST['order'];
		
			/**
			*    Expect: $sorted = array(
			*                menu_order => post-XX
			*            );
			*/
			foreach( $order as $menu_order => $post_id )
			{
				$post_id         = intval( str_ireplace( 'post-', '', $post_id ) );
				$menu_order     = intval($menu_order);
				wp_update_post( array( 'ID' => $post_id, 'menu_order' => $menu_order ) );
			}
		
			die( '1' );
		}


//////  Save Custom Post Type Fields
//////
		
		add_action('save_post', 'sslp_save_staff_member_details');
		
		function sslp_save_staff_member_details(){
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return;	
			global $post;
			
			update_post_meta($post->ID, "_staff_member_bio", $_POST["_staff_member_bio"]);
			update_post_meta($post->ID, "_staff_member_title", $_POST["_staff_member_title"]);
			update_post_meta($post->ID, "_staff_member_email", $_POST["_staff_member_email"]);
			update_post_meta($post->ID, "_staff_member_phone", format_phone($_POST["_staff_member_phone"])); //Format the phone number before saving it.
		}
		
?>