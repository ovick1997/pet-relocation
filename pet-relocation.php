<?php
/*
Plugin Name: Pet Relocation Form
Description: Multi-step form for pet relocation services
Version: 1.0.8
Author: Md Shorov Abedin
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin activation hook
register_activation_hook(__FILE__, 'pet_relocation_activate');

function pet_relocation_activate() {
    pet_relocation_create_tables();
}

// Function to create tables
function pet_relocation_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    // Main submissions table
    $table_name = $wpdb->prefix . 'pet_relocation';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        number_of_pets int NOT NULL,
        relocation_type varchar(20) NOT NULL,
        departure_address text NOT NULL,
        departure_city varchar(100) NOT NULL,
        departure_country varchar(100) NOT NULL,
        travel_date date NOT NULL,
        same_flight varchar(10) NOT NULL,
        flight_info text,
        arrival_address text NOT NULL,
        arrival_city varchar(100) NOT NULL,
        arrival_country varchar(100) NOT NULL,
        emergency_contact varchar(100),
        grooming_required varchar(10) NOT NULL,
        post_arrival_support text,
        health_certificate varchar(20) NOT NULL,
        is_microchipped varchar(10) NOT NULL,
        vaccination_status varchar(100) NOT NULL,
        health_issues text,
        iata_crate varchar(10) NOT NULL,
        submission_date datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    // Pets table
    $pets_table_name = $wpdb->prefix . 'pet_relocation_pets';
    $pets_sql = "CREATE TABLE IF NOT EXISTS $pets_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_id mediumint(9) NOT NULL,
        pet_type varchar(100) NOT NULL,
        breed varchar(100) NOT NULL,
        age varchar(50) NOT NULL,
        weight varchar(50) NOT NULL,
        spaying_status varchar(50) NOT NULL,
        vaccination_report varchar(100) NOT NULL,
        health_condition text,
        specific_medicine text,
        behaviour_training text,
        additional_info text,
        PRIMARY KEY  (id),
        FOREIGN KEY (submission_id) REFERENCES $table_name(id) ON DELETE CASCADE
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);
    error_log('Main table creation result: ' . print_r($result, true));
    
    $result = dbDelta($pets_sql);
    error_log('Pets table creation result: ' . print_r($result, true));
}

// Check and create tables on plugin load
add_action('plugins_loaded', 'pet_relocation_check_tables');

function pet_relocation_check_tables() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pet_relocation';
    $pets_table_name = $wpdb->prefix . 'pet_relocation_pets';
    
    // Check if tables exist
    $main_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    $pets_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$pets_table_name'") == $pets_table_name;
    
    if (!$main_table_exists || !$pets_table_exists) {
        error_log('One or more tables missing. Creating tables...');
        pet_relocation_create_tables();
        
        // Verify creation
        $main_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
        $pets_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$pets_table_name'") == $pets_table_name;
        
        if (!$main_table_exists) {
            error_log("Failed to create table $table_name");
        }
        if (!$pets_table_exists) {
            error_log("Failed to create table $pets_table_name");
        }
    }
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'pet_relocation_enqueue_scripts');

function pet_relocation_enqueue_scripts() {
    wp_enqueue_style('pet-relocation-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('pet-relocation-script', plugins_url('js/script.js', __FILE__), array('jquery'), '1.0.8', true);
    wp_localize_script('pet-relocation-script', 'petRelocation', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('pet_relocation_nonce')
    ));
}

// Add menu item to admin
add_action('admin_menu', 'pet_relocation_admin_menu');

function pet_relocation_admin_menu() {
    add_menu_page(
        'Pet Relocation Requests',
        'Pet Relocation',
        'manage_options',
        'pet-relocation',
        'pet_relocation_admin_page',
        'dashicons-pets',
        30
    );
}

// Admin page display
function pet_relocation_admin_page() {
    include plugin_dir_path(__FILE__) . 'admin/admin-page.php';
}

// Shortcode for the form
add_shortcode('pet_relocation_form', 'pet_relocation_form_shortcode');

function pet_relocation_form_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/form.php';
    return ob_get_clean();
}

// Ajax handler for form submission
add_action('wp_ajax_submit_pet_relocation', 'handle_pet_relocation_submission');
add_action('wp_ajax_nopriv_submit_pet_relocation', 'handle_pet_relocation_submission');

function handle_pet_relocation_submission() {
    error_log('POST Data: ' . print_r($_POST, true));

    if (!check_ajax_referer('pet_relocation_nonce', 'nonce', false)) {
        error_log('Nonce verification failed');
        wp_send_json_error('Nonce verification failed');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'pet_relocation';
    $pets_table_name = $wpdb->prefix . 'pet_relocation_pets';
    
    // Verify that the pets table exists before proceeding
    $pets_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$pets_table_name'") == $pets_table_name;
    if (!$pets_table_exists) {
        error_log("Pets table $pets_table_name still does not exist during submission");
        wp_send_json_error("Pets table $pets_table_name does not exist");
    }
    
    // Sanitize main submission data
    $submission_data = array(
        'number_of_pets' => isset($_POST['number_of_pets']) ? intval($_POST['number_of_pets']) : 0,
        'relocation_type' => sanitize_text_field($_POST['relocation_type'] ?? ''),
        'departure_address' => sanitize_text_field($_POST['departure_address'] ?? ''),
        'departure_city' => sanitize_text_field($_POST['departure_city'] ?? ''),
        'departure_country' => sanitize_text_field($_POST['departure_country'] ?? ''),
        'travel_date' => sanitize_text_field($_POST['travel_date'] ?? ''),
        'same_flight' => sanitize_text_field($_POST['same_flight'] ?? ''),
        'flight_info' => sanitize_text_field($_POST['flight_info'] ?? ''),
        'arrival_address' => sanitize_text_field($_POST['arrival_address'] ?? ''),
        'arrival_city' => sanitize_text_field($_POST['arrival_city'] ?? ''),
        'arrival_country' => sanitize_text_field($_POST['arrival_country'] ?? ''),
        'emergency_contact' => sanitize_text_field($_POST['emergency_contact'] ?? ''),
        'grooming_required' => sanitize_text_field($_POST['grooming_required'] ?? ''),
        'post_arrival_support' => sanitize_text_field($_POST['post_arrival_support'] ?? ''),
        'health_certificate' => sanitize_text_field($_POST['health_certificate'] ?? ''),
        'is_microchipped' => sanitize_text_field($_POST['is_microchipped'] ?? ''),
        'vaccination_status' => sanitize_text_field($_POST['vaccination_status'] ?? ''),
        'health_issues' => sanitize_text_field($_POST['health_issues'] ?? ''),
        'iata_crate' => sanitize_text_field($_POST['iata_crate'] ?? '')
    );
    
    error_log('Submission Data: ' . print_r($submission_data, true));
    
    // Validate required fields
    $required_fields = [
        'number_of_pets',
        'relocation_type',
        'departure_address',
        'departure_city',
        'departure_country',
        'travel_date',
        'same_flight',
        'arrival_address',
        'arrival_city',
        'arrival_country',
        'grooming_required',
        'health_certificate',
        'is_microchipped',
        'vaccination_status',
        'iata_crate'
    ];
    
    foreach ($required_fields as $field) {
        if (empty($submission_data[$field])) {
            error_log("Missing required field: $field");
            wp_send_json_error("Missing required field: $field");
        }
    }
    
    // Insert main submission
    $result = $wpdb->insert($table_name, $submission_data);
    $submission_id = $wpdb->insert_id;
    
    if ($result === false) {
        error_log('Main table insertion failed: ' . $wpdb->last_error);
        wp_send_json_error('Database error: ' . $wpdb->last_error);
    }
    
    if (!$submission_id) {
        error_log('No submission ID returned after main table insertion');
        wp_send_json_error('Failed to retrieve submission ID');
    }
    
    // Handle pets
    $pets = isset($_POST['pets']) && is_array($_POST['pets']) ? $_POST['pets'] : [];
    error_log('Pets Data: ' . print_r($pets, true));
    
    if (!isset($pets[0])) {
        error_log('No pet data provided for the first pet');
        wp_send_json_error('No pet data provided for the first pet');
    }
    
    // Process the first pet (required)
    $pet_data = array(
        'submission_id' => $submission_id,
        'pet_type' => sanitize_text_field($pets[0]['pet_type'] ?? ''),
        'breed' => sanitize_text_field($pets[0]['breed'] ?? ''),
        'age' => sanitize_text_field($pets[0]['age'] ?? ''),
        'weight' => sanitize_text_field($pets[0]['weight'] ?? ''),
        'spaying_status' => sanitize_text_field($pets[0]['spaying_status'] ?? ''),
        'vaccination_report' => sanitize_text_field($pets[0]['vaccination_report'] ?? ''),
        'health_condition' => sanitize_text_field($pets[0]['health_condition'] ?? ''),
        'specific_medicine' => sanitize_text_field($pets[0]['specific_medicine'] ?? ''),
        'behaviour_training' => sanitize_text_field($pets[0]['behaviour_training'] ?? ''),
        'additional_info' => sanitize_text_field($pets[0]['additional_info'] ?? '')
    );
    
    $required_pet_fields = ['pet_type', 'breed', 'age', 'weight', 'spaying_status', 'vaccination_report'];
    foreach ($required_pet_fields as $field) {
        if (empty($pet_data[$field])) {
            error_log("First pet missing required field: $field");
            wp_send_json_error("First pet missing required field: $field");
        }
    }
    
    $pet_result = $wpdb->insert($pets_table_name, $pet_data);
    if ($pet_result === false) {
        error_log('First pet insertion failed: ' . $wpdb->last_error);
        wp_send_json_error('First pet insertion failed: ' . $wpdb->last_error);
    }
    
    // Process additional pets (optional, only if additional_info exists)
    for ($i = 1; $i < count($pets); $i++) {
        if (!isset($pets[$i]['additional_info']) || empty($pets[$i]['additional_info'])) {
            continue; // Skip if no additional info
        }
        
        $pet_data = array(
            'submission_id' => $submission_id,
            'pet_type' => 'N/A',
            'breed' => 'N/A',
            'age' => 'N/A',
            'weight' => 'N/A',
            'spaying_status' => 'N/A',
            'vaccination_report' => 'N/A',
            'health_condition' => '',
            'specific_medicine' => '',
            'behaviour_training' => '',
            'additional_info' => sanitize_text_field($pets[$i]['additional_info'])
        );
        
        $pet_result = $wpdb->insert($pets_table_name, $pet_data);
        if ($pet_result === false) {
            error_log("Additional pet $i insertion failed: " . $wpdb->last_error);
            wp_send_json_error("Additional pet $i insertion failed: " . $wpdb->last_error);
        }
    }
    
    // Send email notification
    $admin_email = get_option('admin_email');
    $subject = 'New Pet Relocation Request';
    $message = "A new pet relocation request has been submitted.\n\n";
    $message .= "Number of Pets: " . $submission_data['number_of_pets'] . "\n";
    $message .= "Departure: " . $submission_data['departure_city'] . "\n";
    $message .= "Arrival: " . $submission_data['arrival_city'] . "\n";
    
    wp_mail($admin_email, $subject, $message);
    
    wp_send_json_success('Form submitted successfully');
    
    wp_die();
}

// Ajax handler for request details
add_action('wp_ajax_get_request_details', 'handle_get_request_details');

function handle_get_request_details() {
    check_ajax_referer('get_request_details', 'nonce');
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if (!$id) {
        wp_send_json_error('Invalid ID');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'pet_relocation';
    $pets_table_name = $wpdb->prefix . 'pet_relocation_pets';
    
    $request = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    $pets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $pets_table_name WHERE submission_id = %d", $id));
    
    if ($request) {
        $details = '<div class="request-detail-group">';
        foreach ($request as $key => $value) {
            if ($key !== 'id' && !empty($value)) {
                $details .= '<div class="request-detail-label">' . esc_html(ucwords(str_replace('_', ' ', $key))) . '</div>';
                $details .= '<div class="request-detail-value">' . esc_html($value) . '</div>';
            }
        }
        $details .= '</div>';
        
        if ($pets) {
            $details .= '<div class="request-detail-group">';
            $details .= '<div class="request-detail-label">Pets</div>';
            foreach ($pets as $index => $pet) {
                $details .= '<div class="request-detail-value">';
                $details .= '<strong>Pet ' . ($index + 1) . '</strong><br>';
                $details .= 'Type: ' . esc_html($pet->pet_type) . '<br>';
                $details .= 'Breed: ' . esc_html($pet->breed) . '<br>';
                $details .= 'Age: ' . esc_html($pet->age) . '<br>';
                $details .= 'Weight: ' . esc_html($pet->weight) . '<br>';
                if (!empty($pet->additional_info)) {
                    $details .= 'Additional Info: ' . esc_html($pet->additional_info) . '<br>';
                }
                $details .= '</div>';
            }
            $details .= '</div>';
        }
        
        wp_send_json_success($details);
    } else {
        wp_send_json_error('Request not found');
    }
    
    wp_die();
}
?>