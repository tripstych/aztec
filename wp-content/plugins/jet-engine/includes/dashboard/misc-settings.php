<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Engine_Misc_Settings' ) ) {

	/**
	 * Define Jet_Engine_Misc_Settings class
	 */
	class Jet_Engine_Misc_Settings {

		private $nonce_action = 'jet-engine-dashboard';

		private $misc_key = 'jet-engine-misc-settings';

		private $misc_settings = false;

		/**
		 * Constructor for the class
		 */
		function __construct() {
			add_action( 'wp_ajax_jet_engine_update_misc_settings', array( $this, 'update_misc_settings' ) );
			add_action( 'admin_init', array( $this, 'set_direction' ), 9999 );
			add_action( 'jet-engine/dashboard/tabs', array( $this, 'print_template' ), 999999 );
		}

		public function print_template() {
			?>
			
			<cx-vui-tabs-panel
				name="misc_options"
				label="<?php _e( 'Advanced', 'jet-engine' ); ?>"
				key="misc_options"
			>
				<div class="jet-engine-misc">
					<p><?php
						_e( '', 'jet-engine' );
					?></p>
					
					<cx-vui-switcher
						label="<?php _e( 'Disable legacy User Meta processing', 'jet-engine' ); ?>"
						description="<?php _e( 'By default, if the meta key is a JetEngine User Meta key, the value will be taken from the current user, or from the listing object, if it is a user. To always get the meta from the object by context, enable this option.', 'jet-engine' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						@input="updateMiscSettings( $event, 'disable_legacy_user_meta' )"
						:value="miscSettings.disable_legacy_user_meta"
					></cx-vui-switcher>
					<cx-vui-switcher
						label="<?php _e( 'Disable Frontend Query Editor', 'jet-engine' ); ?>"
						description="<?php _e( '', 'jet-engine' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						@input="updateMiscSettings( $event, 'disable_frontend_query_editor' )"
						:value="miscSettings.disable_frontend_query_editor"
					></cx-vui-switcher>
					<cx-vui-switcher
						label="<?php _e( 'Force LTR on JetEngine pages', 'jet-engine' ); ?>"
						description="<?php _e( 'The page will be reloaded if this setting changes.', 'jet-engine' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						@input="updateMiscSettings( $event, 'force_ltr' )"
						:value="miscSettings.force_ltr"
					></cx-vui-switcher>
					<span
						class="cx-vui-inline-notice cx-vui-inline-notice--error cx-vui-component"
						v-if="reloadAfterSave"
					><?php _e( 'The page will be reloaded in a few seconds.', 'jet-engine' ); ?></span>
				</div>
			</cx-vui-tabs-panel>
			
			<?php
		}

		public function is_jet_engine_page() {
			$page_slugs = array(
				jet_engine()->admin_page,
			);

			if ( class_exists( '\Jet_Engine\Website_Builder\Manager' ) ) {
				$page_slugs[] = \Jet_Engine\Website_Builder\Manager::instance()->slug();
			}

			if ( jet_engine()->modules->is_module_active( 'profile-builder' ) ) {
				$page_slugs[] = \Jet_Engine\Modules\Profile_Builder\Module::instance()->slug;
			}

			$is_page = in_array( $_GET['page'] ?? '', $page_slugs );

			if ( $is_page ) {
				return true;
			}

			$post_types = array(
				jet_engine()->listings->post_type->post_type,
			);

			if ( jet_engine()->forms ) {
				$post_types[] = jet_engine()->forms->post_type;
			}

			$is_post_type = in_array( $_GET['post_type'] ?? '', $post_types );

			if ( $is_post_type ) {
				return true;
			}

			return ( ( isset( $_GET['page'] ) && 0 === strpos( $_GET['page'], jet_engine()->admin_page . '-' ) ) );
		}

		public function set_direction() {
			if ( ! is_admin() || ! $this->is_jet_engine_page() || ! $this->get_settings( 'force_ltr' ) ) {
				return;
			}

			global $wp_locale, $wp_styles;

			$wp_locale->text_direction = 'ltr';
			if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
				$wp_styles = new WP_Styles();
			}
			$wp_styles->text_direction = 'ltr';
		}

		public function get_nonce_action() {
			return $this->nonce_action;
		}

		public function get_settings( $setting = null, $settings = false ) {
			if ( ! is_array( $this->misc_settings ) ) {
				$this->misc_settings = get_option( $this->misc_key, array() );
			}

			if ( ! is_array( $this->misc_settings ) ) {
				$this->misc_settings = array();
			}

			$settings = false !== $settings ? $settings : $this->misc_settings;

			if ( isset( $setting ) ) {
				return $settings[ $setting ] ?? null;
			}

			return $settings;
		}

		public function get_reload_keys() {
			return array(
				'force_ltr',
			);
		}

		public function get_boolean_keys() {
			return array(
				'disable_legacy_user_meta',
				'dev_settings',
				'force_ltr',
				'disable_frontend_query_editor',
			);
		}

		public function update_misc_settings() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => __( 'Access denied', 'jet-engine' ) ) );
			}

			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			if ( ! $nonce || ! wp_verify_nonce( $nonce, $this->get_nonce_action() ) ) {
				wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'jet-engine' ) ) );
			}

			$settings = isset( $_POST['settings'] ) ? $_POST['settings'] : array();

			if ( empty( $settings ) ) {
				wp_send_json_error( array( 'message' => __( 'Empty settings', 'jet-engine' ) ) );
			}
			
			$boolean_keys = $this->get_boolean_keys();
			$reload_keys  = $this->get_reload_keys();

			$current_settings = $this->get_settings();

			foreach ( $current_settings as $key => $value ) {
				if ( in_array( $key, $boolean_keys ) ) {
					$current_settings[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
				}
			}

			$reload = false;
			
			foreach ( $settings as $key => $value ) {
				if ( in_array( $key, $boolean_keys ) ) {
					$settings[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
				}

				if ( ! $reload && in_array( $key, $reload_keys )
				     && $this->get_settings( $key, $current_settings ) !== $this->get_settings( $key, $settings )
				) {
					$reload = true;
				}
			}

			update_option( $this->misc_key, $settings, false );
			$this->misc_settings = $settings;

			$message = __( 'Settings saved', 'jet-engine' );

			if ( $reload ) {
				$message = __( 'Settings saved. The page will be reloaded in a few seconds.', 'jet-engine' );
			}

			wp_send_json_success(
				array(
					'message' => $message,
					'reload'  => $reload,
				)
			);

		}

	}

}
