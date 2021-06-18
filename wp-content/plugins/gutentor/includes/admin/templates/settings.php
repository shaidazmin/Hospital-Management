<?php
// check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

// add error/update messages

// check if the user have submitted the settings
// WordPress will add the "settings-updated" $_GET parameter to the url
if ( isset( $_GET['settings-updated'] ) ) {
	// add settings saved message with the class of "updated"
	add_settings_error( 'gutentor_settings_messages', 'gutentor_settings_message', __( 'Settings Saved', 'gutentor_settings' ), 'updated' );
}

// show error/update messages
settings_errors( 'gutentor_settings_messages' );

$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
?>
<div class="g-settings-wrap">
	<h2><?php esc_attr_e( 'Gutentor Settings', 'gutentor' ); ?></h2>
	<h2 class="nav-tab-wrapper">
		<a href="?page=gutentor-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>" data-action="gutentor-general">
			<?php esc_attr_e( 'General', 'gutentor' ); ?>
		</a>
		<a href="?page=gutentor-settings&tab=optimization" class="nav-tab <?php echo $active_tab == 'optimization' ? 'nav-tab-active' : ''; ?>" data-action="gutentor-optimization">
			<?php esc_attr_e( 'Optimization', 'gutentor' ); ?>
		</a>
		<?php
		if ( gutentor_is_edd_active() ) {
			?>
			<a href="?page=gutentor-settings&tab=edd" class="nav-tab <?php echo $active_tab == 'edd' ? 'nav-tab-active' : ''; ?>" data-action="gutentor-edd">
				<?php esc_attr_e( 'EDD', 'gutentor' ); ?>
			</a>
			<?php
		}
		?>
	</h2>
	<form class="g-settings-form" action="options.php" method="post">
		<?php
		// output security fields for the registered setting "gutentor_settings"
		settings_fields( 'gutentor_settings' );
		// output setting sections and their fields
		// (sections are registered for "gutentor_settings", each field is registered to a specific section)
		?>
		<div id="g-settings-general" <?php echo $active_tab != 'general' ? 'style="display:none"' : ''; ?>>
			<?php do_settings_sections( 'gutentor_general_settings' ); ?>
		</div>
        <div id="g-settings-optimization" <?php echo $active_tab != 'optimization' ? 'style="display:none"' : ''; ?>>
            <?php do_settings_sections( 'gutentor_optimization_settings' ); ?>
        </div>
        <div id="g-settings-edd" <?php echo $active_tab != 'edd' ? 'style="display:none"' : ''; ?>>
            <?php do_settings_sections( 'gutentor_edd_settings' ); ?>
        </div>
		<?php
		// output save settings button
		submit_button( 'Save Settings' );
		?>
	</form>
</div>
