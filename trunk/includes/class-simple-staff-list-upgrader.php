<?php
/**
 * Runs one-time upgrade steps when the plugin is updated.
 *
 * Activation hooks don't run on plugin updates, so upgrade steps run on
 * admin_init instead. Each step must be safe to run more than once and must
 * never delete user data that a later release still needs.
 *
 * @link       http://www.brettshumaker.com
 * @since      2.2.6
 *
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/includes
 */

/**
 * Runs one-time upgrade steps when the plugin is updated.
 *
 * @since      2.2.6
 * @package    Simple_Staff_List
 * @subpackage Simple_Staff_List/includes
 */
class Simple_Staff_List_Upgrader {

	/**
	 * Option that stores the last plugin version whose upgrade steps ran.
	 *
	 * @since 2.2.6
	 * @var string
	 */
	const VERSION_OPTION = '_simple_staff_list_version';

	/**
	 * The current version of the plugin.
	 *
	 * @since 2.2.6
	 * @var string
	 */
	private $version;

	/**
	 * Initialize the class.
	 *
	 * @since 2.2.6
	 * @param string $version The current version of the plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
	}

	/**
	 * Run any upgrade steps newer than the stored version, then store the current version.
	 *
	 * @since 2.2.6
	 */
	public function maybe_upgrade() {
		$stored_version = get_option( self::VERSION_OPTION, '0' );

		if ( version_compare( $stored_version, $this->version, '>=' ) ) {
			return;
		}

		if ( version_compare( $stored_version, '2.2.6', '<' ) ) {
			$this->upgrade_2_2_6();
		}

		update_option( self::VERSION_OPTION, $this->version );
	}

	/**
	 * 2.2.6: Remove export files written by older versions.
	 *
	 * Exports are now sent straight to the browser, so these files are no
	 * longer needed. Only files this plugin created are removed.
	 *
	 * @since 2.2.6
	 */
	private function upgrade_2_2_6() {
		$uploads = wp_upload_dir( null, false );

		if ( empty( $uploads['basedir'] ) ) {
			return;
		}

		$dir = trailingslashit( $uploads['basedir'] ) . 'sslp';

		if ( ! is_dir( $dir ) ) {
			return;
		}

		$exports = glob( $dir . '/staff-member-export-*.csv' );

		if ( is_array( $exports ) ) {
			foreach ( $exports as $file ) {
				$this->delete_file( $file );
			}
		}

		// Remove the empty index.php the export created, then the folder if nothing else is in it.
		$index = $dir . '/index.php';
		if ( is_file( $index ) && 0 === filesize( $index ) ) {
			$this->delete_file( $index );
		}

		$remaining = glob( $dir . '/*' );
		if ( is_array( $remaining ) && 0 === count( $remaining ) ) {
			@rmdir( $dir ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_operations_rmdir -- Best effort; the folder is empty.
		}
	}

	/**
	 * Delete a file, using wp_delete_file() where it exists (WordPress 4.2+).
	 *
	 * @since 2.2.6
	 * @param string $file Absolute path to the file.
	 */
	private function delete_file( $file ) {
		if ( function_exists( 'wp_delete_file' ) ) {
			wp_delete_file( $file );
		} else {
			@unlink( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink -- Fallback for WordPress before 4.2.
		}
	}
}
