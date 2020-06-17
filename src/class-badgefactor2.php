<?php
/**
 * Badge Factor 2
 * Copyright (C) 2019 ctrlweb
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package Badge_Factor_2
 */

namespace BadgeFactor2;


/**
 * Badge Factor 2 Main Class.
 */
class BadgeFactor2 {

	/**
	 * Badge Factor 2 Version
	 *
	 * @var string
	 */
	public $version = '2.0.0-alpha';

	/**
	 * The single instance of the class.
	 *
	 * @var BadgeFactor2
	 * @since 2.0.0-alpha
	 */
	protected static $_instance = null;

	/**
	 * The plugin's required WordPress version.
	 *
	 * @var string
	 *
	 * @since 2.0.0-alpha
	 */
	public static $required_wp_version = '4.9.9';

	/**
	 * Whether or not the plugin is initialized.
	 *
	 * @var boolean
	 */
	private static $initialized = false;

	/**
	 * Main Badge Factor 2 Instance.
	 *
	 * Ensures only one instance of Badge Factor 2 is loaded or can be loaded.
	 *
	 * @return BadgeFactor2 - Main instance.
	 * @since 2.0.0-alpha
	 * @static
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * BadgeFactor2 Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Badge Factor 2 Init Hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
		self::$initialized = true;
	}

	/**
	 * Badge Factor 2 Includes.
	 *
	 * @return void
	 */
	public function includes() {
		require_once BF2_ABSPATH . 'lib/CMB2/init.php';
		require_once 'phar://' . BF2_ABSPATH . 'lib/league-oauth2-client.phar/vendor/autoload.php';
		require_once BF2_ABSPATH . 'src/core/trait-singleton.php';
		require_once BF2_ABSPATH . 'src/core/trait-paginatable.php';
		require_once BF2_ABSPATH . 'src/core/interface-badgr-entity.php';
		require_once BF2_ABSPATH . 'src/core/class-badgrclient.php';
		require_once BF2_ABSPATH . 'src/core/class-badgrprovider.php';
		require_once BF2_ABSPATH . 'src/models/class-issuer.php';
		require_once BF2_ABSPATH . 'src/models/class-badgeclass.php';
		require_once BF2_ABSPATH . 'src/models/class-assertion.php';
		require_once BF2_ABSPATH . 'src/core/class-badgruser.php';
		require_once BF2_ABSPATH . 'src/client/shortcodes/class-issuers.php';
		BadgrClient::pre_init_hooks();
		require_once BF2_ABSPATH . 'src/public/class-badgefactor2-public.php';
		BadgeFactor2_Public::init_hooks();

		add_action( 'init', array( BadgrProvider::class, 'init_hooks' ) );
		add_action( 'init', array( BadgrUser::class, 'init_hooks' ) );

		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			require_once BF2_ABSPATH . 'src/admin/class-badgefactor2-admin.php';
			require_once BF2_ABSPATH . 'src/admin/class-badgr-list.php';
			require_once BF2_ABSPATH . 'src/admin/lists/class-issuers.php';
			require_once BF2_ABSPATH . 'src/admin/lists/class-badges.php';
			require_once BF2_ABSPATH . 'src/admin/lists/class-assertions.php';
			BadgeFactor2_Admin::init_hooks();
			add_action( 'init', array( BadgrClient::class, 'init_hooks' ) );
		}

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once BF2_ABSPATH . 'src/cli/class-badgefactor2-cli.php';
			require_once BF2_ABSPATH . 'src/cli/class-badgr-cli.php';
		}
	}

	/**
	 * Define BadgeFactor2 Constants.
	 *
	 * @return void
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir( null, false );

		$this->define( 'BF2_ABSPATH', dirname( BF2_FILE ) . '/' );
		$this->define( 'BF2_BASEURL', plugin_dir_url( BF2_FILE ) );
		$this->define( 'BF2_PLUGIN_BASENAME', plugin_basename( BF2_FILE ) );
		$this->define( 'BF2_VERSION', $this->version );
		$this->define( 'BF2_LOG_DIR', $upload_dir['basedir'] . '/bf2-logs/' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name Constant name.
	 * @param string|bool $value Constant value.
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

}

// Fix New User Email Notification.
$badgefactor2_options = get_option( 'badgefactor2' );
if ( ! function_exists( 'wp_new_user_notification' ) && ( ! isset( $badgefactor2_options['bf2_block_wp_registration_emails'] ) || 'on' === $badgefactor2_options['bf2_block_wp_registration_emails'] ) ) {

	/**
	 * Overriding new user notifications.
	 *
	 * @param int    $user_id User ID.
	 * @param string $notify Notify.
	 * @return void
	 */
	function wp_new_user_notification( $user_id, $notify = '' ) { }
}
