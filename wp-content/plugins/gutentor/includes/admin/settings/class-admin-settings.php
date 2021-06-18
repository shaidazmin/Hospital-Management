<?php
/**
 * Do things related with Gutentor settings
 *
 * @since 3.0.3
 */

if ( ! class_exists( 'Gutentor_Admin_Settings' ) ) {
	/**
	 * Class Gutentor_Admin_Settings.
	 */
	class Gutentor_Admin_Settings {

		protected static $page_slug = 'gutentor-settings';

		public function __construct() {

			add_action( 'admin_menu', array( __CLASS__, 'admin_pages' ) );
			add_action( 'admin_init', array( $this, 'gutentor_settings_init' ) );
			add_filter( 'register_post_type_args', array( $this, 'enable_rest_api' ), 20, 2 );
			add_filter( 'use_block_editor_for_post_type', array( $this, 'enable_gutenberg_post_type' ), 999, 2 );

			add_action( 'init', array( $this, 'add_page_templates_in_post_types' ), 999 );

		}


		/**
		 * Admin Page Menu and submenu page
		 *
		 * @since 3.0.3
		 */
		public static function admin_pages() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			add_submenu_page(
				'gutentor',
				esc_html__( 'Settings', 'gutentor' ),
				esc_html__( 'Settings', 'gutentor' ),
				'manage_options',
				'gutentor-settings',
				array( __CLASS__, 'gutentor_settings_template' )
			);
		}

		/**
		 * Render Settings Template
		 *
		 * @since 3.0.3
		 */
		public static function gutentor_settings_template() {
			require_once GUTENTOR_PATH . 'includes/admin/templates/settings.php';
		}

		/**
		 * Gutentor settings
		 */
		public static function gutentor_settings_init() {

			// Register a new setting for "gutentor_settings" page.
			register_setting(
				'gutentor_settings',
				'gutentor_settings_options',
				array(
					'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				)
			);

			/*General Settings*/
			add_settings_section(
				'gutentor_general_settings',
				'',
				'',
				'gutentor_general_settings'
			);

			// Register a new setting filed in the "gutentor_general_settings" section.
			add_settings_field(
				'gutentor_enable_editor_in_pt',
				esc_html__( 'Enable Gutenberg Editor in Post Types.', 'gutentor' ),
				array( __CLASS__, 'post_types_list_field' ),
				'gutentor_general_settings',
				'gutentor_general_settings'
			); // id, title, display cb, page, section

			/*
			 * How to add Page Templates to Post or Custom Post Types
			 * */
			add_settings_field(
				'gutentor_enable_g_page_templates_in_pt',
				esc_html__( 'Enable Gutentor Page Templates in Post Types.', 'gutentor' ),
				array( __CLASS__, 'post_types_page_template_field' ),
				'gutentor_general_settings',
				'gutentor_general_settings'
			); // id, title, display cb, page, section

			// Register gutentor_enable_import_block filed in the "gutentor_general_settings" section.
			add_settings_field(
				'gutentor_enable_import_block',
				esc_html__( 'Enable Template library in editor.', 'gutentor' ),
				array( __CLASS__, 'enable_import_block' ),
				'gutentor_general_settings',
				'gutentor_general_settings'
			); // id, title, display cb, page, section

			// Register gutentor_enable_export_block filed in the "gutentor_general_settings" section.
			add_settings_field(
				'gutentor_enable_export_block',
				esc_html__( 'Enable Export Template Button.', 'gutentor' ),
				array( __CLASS__, 'enable_export_block' ),
				'gutentor_general_settings',
				'gutentor_general_settings'
			); // id, title, display cb, page, section

			/*Optimization Setting*/
			add_settings_section(
				'gutentor_optimization_settings',
				'',
				'',
				'gutentor_optimization_settings'
			);
			add_settings_field(
				'gutentor_optimization_settings',
				esc_html__( 'Resources Load', 'gutentor' ),
				array( __CLASS__, 'resource_load_optimization' ),
				'gutentor_optimization_settings',
				'gutentor_optimization_settings'
			); // id, title, display cb, page, section

			/*Edd Settings*/
			add_settings_section(
				'gutentor_edd_settings',
				'',
				'',
				'gutentor_edd_settings'
			);

			// Register a new setting filed in the "gutentor_general_settings" section.
			add_settings_field(
				'gutentor_enable_edd_demo_url',
				esc_html__( 'Enable Demo URL.', 'gutentor' ),
				array( __CLASS__, 'edd_demo_url' ),
				'gutentor_edd_settings',
				'gutentor_edd_settings'
			); // id, title, display cb, page, section
		}

		/**
		 * Callback Function for Settings Field
		 * gutentor_enable_editor_in_pt
		 *
		 * @since 3.0.3
		 */
		public static function post_types_list_field() {
			$options = get_option( 'gutentor_settings_options' );
			$value   = array();
			if ( isset( $options['gutentor_enable_editor_in_pt'] ) && ! empty( $options['gutentor_enable_editor_in_pt'] ) ) {
				$value = $options['gutentor_enable_editor_in_pt'];
			}
			?>
			<label>
				<input type="checkbox" name="post" value="post" checked disabled/>
				<?php esc_attr_e( 'Post', 'gutentor' ); ?>
			</label>
			<br>
			<label>
				<input type="checkbox" name="page" value="page" checked disabled/>
				<?php esc_attr_e( 'Page', 'gutentor' ); ?>
			</label>
			<br>
			<?php
			$contents = gutentor_admin_get_post_types();
			foreach ( $contents as $post_type ) :
				?>
				<label>
					<input type="checkbox" name="gutentor_settings_options[gutentor_enable_editor_in_pt][]" value="<?php echo esc_attr( $post_type['value'] ); ?>" <?php echo in_array( $post_type['value'], $value ) ? 'checked' : ''; ?>/>
					<?php echo $post_type['label']; ?>
				</label>
				<br>
				<?php
			endforeach;
			esc_html_e( 'Enabling Gutenberg Editor will also enable show_in_rest in post types.', 'gutentor' );
		}

		/**
		 * Callback Function for Settings Field
		 * gutentor_enable_g_page_templates_in_pt
		 *
		 * @since 3.0.3
		 */
		public static function post_types_page_template_field() {
			$options = get_option( 'gutentor_settings_options' );
			$value   = array();
			if ( isset( $options['gutentor_enable_page_templates_in_pt'] ) && ! empty( $options['gutentor_enable_page_templates_in_pt'] ) ) {
				$value = $options['gutentor_enable_page_templates_in_pt'];
			}
			?>
			<label>
				<input type="checkbox" name="page" value="page" checked disabled/>
				<?php esc_attr_e( 'Page', 'gutentor' ); ?>
			</label>
			<br>
			<?php
			$contents = gutentor_admin_get_post_types( array(), array( 'post' ) );
			foreach ( $contents as $post_type ) :
				?>
				<label>
					<input type="checkbox" name="gutentor_settings_options[gutentor_enable_page_templates_in_pt][]" value="<?php echo esc_attr( $post_type['value'] ); ?>" <?php echo in_array( $post_type['value'], $value ) ? 'checked' : ''; ?>/>
					<?php echo $post_type['label']; ?>
				</label>
				<br>
				<?php
			endforeach;
		}

		/**
		 * Enable Template Library
		 * gutentor_enable_import_block
		 *
		 * @since 3.0.3
		 */
		public static function enable_import_block() {
			$value = gutentor_setting_enable_template_library();
			?>
			<label>
				<input type="checkbox" name="gutentor_settings_options[gutentor_enable_import_block]" value="1" <?php checked( $value, 1 ); ?>/>
				<?php esc_attr_e( 'Check to enable', 'gutentor' ); ?>
			</label>
			<br>
			<?php
		}

		/**
		 * Enable Export Template
		 * gutentor_enable_export_block
		 *
		 * @since 3.0.3
		 */
		public static function enable_export_block() {
			$value = gutentor_setting_enable_export_template_button();
			?>
			<label>
				<input type="checkbox" name="gutentor_settings_options[gutentor_enable_export_block]" value="1" <?php checked( $value, 1 ); ?>/>
				<?php esc_attr_e( 'Check to enable', 'gutentor' ); ?>
			</label>
			<br>
			<?php
		}

		/**
		 * Optimization Setting
		 * gutentor_enable_edd_demo_url
		 *
		 * @since 3.0.3
		 */
		public static function resource_load_optimization() {
			$resources = array(
				array(
					'label' => esc_html__( 'AcmeTicker', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Post (Type) News Ticker won't work.", 'gutentor' ),
					'key'   => 'acmeticker',
				),
				array(
					'label' => esc_html__( 'Animate.css', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource animation on all blocks won't work.", 'gutentor' ),
					'key'   => 'animatecss',
				),
				array(
					'label' => esc_html__( 'CountUp.js', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Count UP block won't work.", 'gutentor' ),
					'key'   => 'countup',
				),
				array(
					'label' => esc_html__( 'EASY PIE CHART', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Progress Bar block won't work.", 'gutentor' ),
					'key'   => 'easypiechart',
				),
				array(
					'label' => esc_html__( 'flexMenu', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Flex Menu won't work on Advanced Post (Type) block.", 'gutentor' ),
					'key'   => 'flexmenu',
				),
				array(
					'label' => esc_html__( 'Font Awesome', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource icon on all blocks won't work.", 'gutentor' ),
					'key'   => 'fontawesome',
				),
				array(
					'label' => esc_html__( 'Grid', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource grid on all blocks won't work.", 'gutentor' ),
					'key'   => 'wpnessgrid',
				),
				array(
					'label' => esc_html__( 'Isotope', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Isotope on Gallery and Filter blocks won't work.", 'gutentor' ),
					'key'   => 'isotope',
				),
				array(
					'label' => esc_html__( 'Magnific Popup', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Popup Features won't work on all blocks.", 'gutentor' ),
					'key'   => 'magnificpopup',
				),
				array(
					'label' => esc_html__( 'Masonry', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Masonry on Gallery and Filter blocks won't work.", 'gutentor' ),
					'key'   => 'masonry',
				),
				array(
					'label' => esc_html__( 'slick', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Slider and Carousel won't work.", 'gutentor' ),
					'key'   => 'slick',
				),
				array(
					'label' => esc_html__( 'Theia Sticky Sidebar', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource Sticky Sidebar on the Advanced Columns block won't work.", 'gutentor' ),
					'key'   => 'theiastickysidebar',
				),
				array(
					'label' => esc_html__( 'WOW.js', 'gutentor' ),
					'desc'  => esc_html__( "By disabling this resource animation on all blocks won't work.", 'gutentor' ),
					'key'   => 'wow',
				),
			);

			$options       = get_option( 'gutentor_settings_options' );
			$resource_load = array();
			if ( isset( $options['resource_load'] ) && ! empty( $options['resource_load'] ) ) {
				$resource_load = $options['resource_load'];
			}
			echo "<div class='g-resource-load-options'>";
			foreach ( $resources as $resource ) {
				$value = isset( $resource_load[ $resource['key'] ] ) ? $resource_load[ $resource['key'] ] : 'default';
				$key   = 'gutentor_settings_options[resource_load][' . $resource['key'] . ']';
				?>
					<div class="g-resource-load-option">
						<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $resource['label'] ); ?></label>

						<select name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">
							<option value="default" <?php selected( $value, 'default' ); ?>><?php esc_html_e( 'Default', 'gutentor' ); ?></option>
							<option value="not-load" <?php selected( $value, 'not-load' ); ?>><?php esc_html_e( 'Disable', 'gutentor' ); ?></option>
							<option value="force-load" <?php selected( $value, 'force-load' ); ?>><?php esc_html_e( 'Force Load', 'gutentor' ); ?></option>
						</select>
						<span class="g-desc-wrap">
							<span class="g-desc-icon">
								<span class="dashicons dashicons-warning"></span>
							</span>
							<span class="g-resource-desc">
								<?php echo esc_html( $resource['desc'] ); ?>
							</span>						
						</span>
					</div>

				<?php
			}
			echo '</div>';
		}

		/**
		 * EDD Demo URL
		 * gutentor_enable_edd_demo_url
		 *
		 * @since 3.0.3
		 */
		public static function edd_demo_url() {
			$options = get_option( 'gutentor_settings_options' );
			$value   = false;
			if ( isset( $options['gutentor_enable_edd_demo_url'] ) && ! empty( $options['gutentor_enable_edd_demo_url'] ) ) {
				$value = $options['gutentor_enable_edd_demo_url'];
			}
			?>
			<label>
				<input type="checkbox" name="gutentor_settings_options[gutentor_enable_edd_demo_url]" value="1" <?php checked( $value, 1 ); ?>/>
				<?php esc_attr_e( 'Check to enable', 'gutentor' ); ?>
			</label>
			<br>
			<?php
		}

		/**
		 * Enable rest api post types
		 *
		 * @since 3.0.3
		 */
		public static function enable_rest_api( $args, $post_type ) {

			$gutentor_settings = get_option( 'gutentor_settings_options' );
			if ( isset( $gutentor_settings['gutentor_enable_editor_in_pt'] ) ) {
				$gutenberg_enable_post_types = $gutentor_settings['gutentor_enable_editor_in_pt'];
				if ( in_array( $post_type, $gutenberg_enable_post_types ) ) {
					$args['show_in_rest'] = true;
				}
			}
			return $args;
		}

		/**
		 * Enable Gutenberg Editor in Post Types
		 *
		 * @since 3.0.3
		 */
		public static function enable_gutenberg_post_type( $can_edit, $post_type ) {

			$gutentor_settings = get_option( 'gutentor_settings_options' );
			if ( isset( $gutentor_settings['gutentor_enable_editor_in_pt'] ) ) {
				$gutenberg_enable_post_types = $gutentor_settings['gutentor_enable_editor_in_pt'];
				if ( in_array( $post_type, $gutenberg_enable_post_types ) ) {
					return true;
				}
			}
			return $can_edit;
		}

		/**
		 * How to add Page Templates to Post or Custom Post Types
		 * https://www.gutentor.com/documentation/article/how-to-add-page-templates-to-post-or-custom-post-types/
		 *
		 * @since 3.0.3
		 */
		public function add_page_templates_in_post_types() {
			$pts               = array();
			$pts[]             = 'page';
			$gutentor_settings = get_option( 'gutentor_settings_options' );
			if ( isset( $gutentor_settings['gutentor_enable_page_templates_in_pt'] ) ) {
				$pt = maybe_unserialize( $gutentor_settings['gutentor_enable_page_templates_in_pt'] );
				if ( is_array( $pt ) && ! empty( $pt ) ) {
					foreach ( $pt as $p ) {
						$pts[] = $p;
					}
				}
			}
			if ( ! is_array( $pts ) ) {
				return;
			}
			foreach ( $pts as $p ) {
				add_filter( 'theme_' . $p . '_templates', array( gutentor_hooks(), 'gutentor_add_page_template' ) );
			}
			if ( in_array( 'page', $pts ) ) {
				add_filter( 'page_template', array( gutentor_hooks(), 'gutentor_redirect_page_template' ), 999 );
			}
			if ( count( array_diff( $pts, array( 'page' ) ) ) > 0 ) {
				add_filter( 'single_template', array( gutentor_hooks(), 'gutentor_redirect_page_template' ), 999 );
			}
		}

		/**
		 * Add sanitize
		 * sanitize
		 *
		 * @since 3.0.3
		 */
		public static function sanitize( $setting ) {
			$new_setting        = array();
			$check_import_block = $check_export_block = false;
			if ( is_array( $setting ) ) {
				foreach ( $setting as $key => $value ) {
					if ( 'gutentor_enable_import_block' == $key ) {
						$new_setting[ $key ] = ( isset( $value ) && '1' == $value ) ? '1' : '0';
						$check_import_block  = true;
					} elseif ( 'gutentor_enable_export_block' == $key ) {
						$new_setting[ $key ] = ( isset( $value ) && '1' == $value ) ? '1' : '0';
						$check_export_block  = true;
					} else {
						$new_setting[ $key ] = $value;
					}
				}
			}
			if ( ! $check_import_block ) {
				$new_setting['gutentor_enable_import_block'] = '0';
			}
			if ( ! $check_export_block ) {
				$new_setting['gutentor_enable_export_block'] = '0';
			}
			return $new_setting;
		}
	}
}
new Gutentor_Admin_Settings();
