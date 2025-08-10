<?php
   /*
    Plugin Name: Aztec Booking Integrator
    Description: Aztec Booking Integration
    Version: 1.0
    License: GPL2
    */

/* Examine the web page https://vpic.nhtsa.dot.gov/api/
   and the available endpoints for vehicle information.
   You can use the following endpoint to get a list of all makes:
   https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json
   This will return a JSON response with all vehicle makes.
   You can then use this data to populate the make dropdown in your booking form.
   Similarly, you can explore other endpoints to get models and years.
   Make sure to handle the API responses and errors appropriately.
   */

/* TODO:
   (ensure unique names for each manufacturer, make, and type)
   Get all manufacturers from
   https://vpic.nhtsa.dot.gov/api/vehicles/getallmanufacturers?format=json

   get all makes for each manufacturer
   https://vpic.nhtsa.dot.gov/api/vehicles/GetMakeForManufacturer/honda?format=json

   get all types for each make
   https://vpic.nhtsa.dot.gov/api/vehicles/GetVehicleTypesForMake/merc?format=json 

    Store all of it in the database
*/


/* Initialize our booking form */
function aztec_initialize_booking_form() {
    // Enqueue necessary scripts and styles
    wp_enqueue_script('aztec-booking-script', plugins_url('js/booking.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('aztec-booking-style', plugins_url('css/booking.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'aztec_initialize_booking_form');

// Add settings link to plugin row
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'aztec_bookings_plugin_action_links');
function aztec_bookings_plugin_action_links($links) {
    $settings_link = '<a href="options-general.php?page=aztec-bookings-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Add settings page to admin
add_action('admin_menu', function() {
    add_options_page('Aztec Bookings Settings', 'Aztec Bookings', 'manage_options', 'aztec-bookings-settings', 'aztec_bookings_settings_page');
});

function aztec_bookings_settings_page() {
    global $wpdb;
    echo '<div class="wrap"><h1>Aztec Bookings Data</h1>';
    echo '<h2>Manufacturers</h2>';
    $manufacturers = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}aztec_manufacturers LIMIT 20");
    if ($manufacturers) {
        echo '<ul>';
        foreach ($manufacturers as $m) {
            echo '<li>' . esc_html($m->name) . ' (ID: ' . esc_html($m->manufacturer_id) . ')</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No manufacturers found.</p>';
    }

    echo '<h2>Makes</h2>';
    $makes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}aztec_makes LIMIT 20");
    if ($makes) {
        echo '<ul>';
        foreach ($makes as $m) {
            echo '<li>' . esc_html($m->name) . ' (ID: ' . esc_html($m->make_id) . ')</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No makes found.</p>';
    }

    echo '<h2>Types</h2>';
    $types = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}aztec_types LIMIT 20");
    if ($types) {
        echo '<ul>';
        foreach ($types as $t) {
            echo '<li>' . esc_html($t->name) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No types found.</p>';
    }
    echo '</div>';
}

// Create custom tables for manufacturers, makes, and types
function aztec_create_vehicle_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $tables = [
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}aztec_manufacturers (
            id INT NOT NULL AUTO_INCREMENT,
            manufacturer_id INT,
            name VARCHAR(255) UNIQUE,
            PRIMARY KEY (id)
        ) $charset_collate;",
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}aztec_makes (
            id INT NOT NULL AUTO_INCREMENT,
            make_id INT,
            manufacturer_id INT,
            name VARCHAR(255) UNIQUE,
            PRIMARY KEY (id)
        ) $charset_collate;",
        "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}aztec_types (
            id INT NOT NULL AUTO_INCREMENT,
            make_id INT,
            name VARCHAR(255) UNIQUE,
            PRIMARY KEY (id)
        ) $charset_collate;"
    ];
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    foreach ($tables as $sql) {
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'aztec_create_vehicle_tables');

// Fetch and store manufacturers, makes, and types
function aztec_fetch_and_store_vehicle_data() {
    global $wpdb;
    // Fetch manufacturers
    $manu_response = wp_remote_get('https://vpic.nhtsa.dot.gov/api/vehicles/getallmanufacturers?format=json');
    if (is_wp_error($manu_response)) return;
    $manu_data = json_decode(wp_remote_retrieve_body($manu_response), true);
    if (!empty($manu_data['Results'])) {
        foreach ($manu_data['Results'] as $manufacturer) {
            $manufacturer_id = intval($manufacturer['Mfr_ID']);
            $manufacturer_name = sanitize_text_field($manufacturer['Mfr_CommonName'] ?: $manufacturer['Mfr_Name']);
            if ($manufacturer_name) {
                $wpdb->insert(
                    $wpdb->prefix . 'aztec_manufacturers',
                    [
                        'manufacturer_id' => $manufacturer_id,
                        'name' => $manufacturer_name
                    ],
                    [
                        '%d', '%s'
                    ]
                );
            }
        }
    }

    // Fetch makes for each manufacturer
    $manufacturers = $wpdb->get_results("SELECT manufacturer_id, name FROM {$wpdb->prefix}aztec_manufacturers");
    foreach ($manufacturers as $manu) {
        $makes_response = wp_remote_get('https://vpic.nhtsa.dot.gov/api/vehicles/GetMakeForManufacturer/' . urlencode($manu->name) . '?format=json');
        if (is_wp_error($makes_response)) continue;
        $makes_data = json_decode(wp_remote_retrieve_body($makes_response), true);
        if (!empty($makes_data['Results'])) {
            foreach ($makes_data['Results'] as $make) {
                $make_id = intval($make['Make_ID']);
                $make_name = sanitize_text_field($make['Make_Name']);
                if ($make_name) {
                    $wpdb->insert(
                        $wpdb->prefix . 'aztec_makes',
                        [
                            'make_id' => $make_id,
                            'manufacturer_id' => $manu->manufacturer_id,
                            'name' => $make_name
                        ],
                        [
                            '%d', '%d', '%s'
                        ]
                    );
                }
            }
        }
    }

    // Fetch types for each make
    $makes = $wpdb->get_results("SELECT make_id, name FROM {$wpdb->prefix}aztec_makes");
    foreach ($makes as $make) {
        $types_response = wp_remote_get('https://vpic.nhtsa.dot.gov/api/vehicles/GetVehicleTypesForMake/' . urlencode($make->name) . '?format=json');
        if (is_wp_error($types_response)) continue;
        $types_data = json_decode(wp_remote_retrieve_body($types_response), true);
        if (!empty($types_data['Results'])) {
            foreach ($types_data['Results'] as $type) {
                $type_name = sanitize_text_field($type['VehicleTypeName']);
                if ($type_name) {
                    $wpdb->insert(
                        $wpdb->prefix . 'aztec_types',
                        [
                            'make_id' => $make->make_id,
                            'name' => $type_name
                        ],
                        [
                            '%d', '%s'
                        ]
                    );
                }
            }
        }
    }
}

// Admin tool to trigger data fetch manually
add_action('admin_menu', function() {
    add_submenu_page('tools.php', 'Fetch Vehicle Data', 'Fetch Vehicle Data', 'manage_options', 'fetch-vehicle-data', function() {
        if (isset($_POST['fetch_vehicle_data'])) {
            aztec_fetch_and_store_vehicle_data();
            echo '<div class="updated"><p>Vehicle data fetched and stored!</p></div>';
        }
        echo '<div class="wrap"><h1>Fetch Vehicle Data</h1><form method="post"><input type="submit" name="fetch_vehicle_data" class="button-primary" value="Fetch Now"></form></div>';
    });
});
