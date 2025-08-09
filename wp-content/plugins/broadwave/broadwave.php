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
		return "<h1>Success: Booking Shortcode</h1>";
	}

	function bw_activate() {
	}
	function bw_deactivate() {
	}

	add_shortcode("booking",'booking_shortcut');

	register_activation_hook(__FILE__,'bw_activate');
	register_deactivation_hook(__FILE__,'bw_deactivate');
?>
