<?php
   /*
    Plugin Name: Aztec Booking Integrator
    Plugin URI: https://broadwavestudios.com
    Description: Aztec Booking Integration 
    Version: 1.0
    Author: Broadwave Studios
    Author URI: https://broadwavestudios.com/
    License: GPL2
    */

// Add settings menu
add_action('admin_menu', 'broadwave_add_settings_page');
function broadwave_add_settings_page() {
    add_options_page(
        'Broadwave Settings',
        'Broadwave',
        'manage_options',
        'broadwave-settings',
        'broadwave_render_settings_page'
    );
}

// Render settings page
function broadwave_render_settings_page() {
    // Handle form submission
    if (isset($_POST['broadwave_option_submit'])) {
        check_admin_referer('broadwave_settings_save');
        $value = sanitize_text_field($_POST['broadwave_option_value']);
        update_option('broadwave_option', $value);
        echo '<div class="updated"><p>Option saved!</p></div>';
    }
    $current_value = get_option('broadwave_option', '');
    ?>
    <div class="wrap">
        <h1>Broadwave Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('broadwave_settings_save'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Option Value</th>
                    <td><input type="text" name="broadwave_option_value" value="<?php echo esc_attr($current_value); ?>" /></td>
                </tr>
            </table>
            <input type="submit" name="broadwave_option_submit" class="button-primary" value="Save Changes" />
        </form>
    </div>
    <?php
}

?>
// Dynamic booking form shortcode
add_shortcode('broadwave_booking_form', 'broadwave_booking_form_shortcode');
function broadwave_booking_form_shortcode() {
    // Hardcoded demo data
    $data = array(
        '2022' => array(
            'Toyota' => array('Camry', 'Corolla'),
            'Honda' => array('Civic', 'Accord'),
        ),
        '2023' => array(
            'Ford' => array('F-150', 'Escape'),
            'Chevrolet' => array('Silverado', 'Malibu'),
        ),
    );
    ob_start();
    ?>
    <form id="broadwave-booking-form">
        <label for="year">Year:</label>
        <select id="year" name="year">
            <option value="">Select Year</option>
            <?php foreach ($data as $year => $makes): ?>
                <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="make">Make:</label>
        <select id="make" name="make" disabled>
            <option value="">Select Make</option>
        </select>
        <br>
        <label for="model">Model:</label>
        <select id="model" name="model" disabled>
            <option value="">Select Model</option>
        </select>
        <br>
        <input type="submit" value="Book Now">
    </form>
    <script>
    const data = <?php echo json_encode($data); ?>;
    const yearSelect = document.getElementById('year');
    const makeSelect = document.getElementById('make');
    const modelSelect = document.getElementById('model');

    yearSelect.addEventListener('change', function() {
        makeSelect.innerHTML = '<option value="">Select Make</option>';
        modelSelect.innerHTML = '<option value="">Select Model</option>';
        modelSelect.disabled = true;
        if (this.value && data[this.value]) {
            Object.keys(data[this.value]).forEach(function(make) {
                makeSelect.innerHTML += `<option value="${make}">${make}</option>`;
            });
            makeSelect.disabled = false;
        } else {
            makeSelect.disabled = true;
        }
    });

    makeSelect.addEventListener('change', function() {
        modelSelect.innerHTML = '<option value="">Select Model</option>';
        if (yearSelect.value && this.value && data[yearSelect.value][this.value]) {
            data[yearSelect.value][this.value].forEach(function(model) {
                modelSelect.innerHTML += `<option value="${model}">${model}</option>`;
            });
            modelSelect.disabled = false;
        } else {
            modelSelect.disabled = true;
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
