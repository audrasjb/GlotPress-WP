<?php
/**
 * The Settings Route Class
 *
 * Handles the settings page display and saving of settings.
 *
 * @link http://glotpress.org
 *
 * @package GlotPress
 * @since 1.1.0
 */

/**
 * The Settings Route Class.
 *
 * @since 1.1.0
 */
class GP_Route_Settings extends GP_Route_Main {

	/**
	 * Displays the settings page, requires a user to be logged in.
	 */
	public function settings_get() {
		if ( ! is_user_logged_in() ) {
			$this->redirect( wp_login_url( gp_url_profile() ) );
			return;
		}

		$this->tmpl( 'settings' );
	}

	/**
	 * Saves settings for a user and redirects back to the settings page.
	 *
	 * @param int $user_id Optional. A user id, if not provided the id of the currently logged in user will be used.
	 */
	public function settings_post( $user_id = null ) {
		if ( isset( $_POST['submit'] ) ) {
			// Sometimes we get null, sometimes we get 0, depending on where it comes from.
			// Let's make sure we have a consistent value to test against and that it's an integer.
			$user_id = (int) $user_id;

			if ( 0 === $user_id ) {
				$user_id = get_current_user_id();
			}

			$per_page = (int) $_POST['per_page'];
			update_user_option( $user_id, 'gp_per_page', $per_page );

			$default_sort = array(
				'by'  => 'priority',
				'how' => 'desc',
			);

			$user_sort = wp_parse_args( $_POST['default_sort'], $default_sort );
			update_user_option( $user_id, 'gp_default_sort', $user_sort );
		}

		$this->redirect( gp_url( '/settings' ) );
	}
}
