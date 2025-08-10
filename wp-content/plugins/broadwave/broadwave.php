<?php
   /*
    Plugin Name: Aztec Booking Integrator
    Plugin URI: https://broadwavestudios.com
    Description: Aztec Booking Integration 
    Version: 1.0
    Author: Your Name
    Author URI: https://broadwavestudios.com/
    License: GPL2
    */
	function booking_shortcut() {
		return "<h2>Success: Booking Shortcode</h2>";
	}

	function bw_activate() {
	}
	function bw_deactivate() {
	}

	add_shortcode("booking",'booking_shortcut');

	/* add a plugin settings section */
	add_action('admin_menu', 'bw_add_admin_menu');
	add_action('admin_init', 'bw_settings_init');

	function bw_add_admin_menu() {
		add_options_page('Aztec Booking Integrator', 'Aztec Booking Integrator', 'manage_options', 'aztec_booking_integrator', 'bw_options_page');
	}

	function bw_settings_init() {
		register_setting('pluginPage', 'bw_settings');

		add_settings_section(
			'bw_pluginPage_section',
			__('Your section description', 'wordpress'),
			'bw_settings_section_callback',
			'pluginPage'
		);

		add_settings_field(
			'bw_text_field_0',
			__('Settings Field 1', 'wordpress'),
			'bw_text_field_0_render',
			'pluginPage',
			'bw_pluginPage_section'
		);
	}

	function bw_text_field_0_render() {
		$options = get_option('bw_settings');
		?>
		<input type='text' name='bw_settings[bw_text_field_0]' value='<?php echo $options['bw_text_field_0']; ?>'>
		<?php
	}

	function bw_settings_section_callback() {
		echo __('This is a settings section description', 'wordpress');
	}

	function bw_options_page() {
		?>
		<form action='options.php' method='post'>
			<h2>Aztec Booking Integrator:</h2>
			<?php
			settings_fields('pluginPage');
			do_settings_sections('pluginPage');
			submit_button();
			?>
		</form>
		<?php
	}


	register_activation_hook(__FILE__,'bw_activate');
	register_deactivation_hook(__FILE__,'bw_deactivate');
?>
