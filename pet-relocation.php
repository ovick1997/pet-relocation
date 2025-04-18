<?php
/*
Plugin Name: Pet Relocation Form
Plugin URI: https://github.com/ovick1997/pet-relocation
Description: A multi-step form plugin for managing pet relocation requests, including pet details, travel information, and additional services. Ideal for pet relocation businesses. Use the shortcode [pet_relocation_form] to display the form on any page or post.
Version: 1.0.15
Author: Md Shorov Abedin
Author URI: https://shorovabedin
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: pet-relocation
Domain Path: /languages
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Activation hook to create or update database tables.
 */
register_activation_hook(__FILE__, 'pet_relocation_activate');

function pet_relocation_activate() {
    pet_relocation_create_tables();
    pet_relocation_update_schema();
}

/**
 * Creates database tables for main submissions and pet details.
 */
function pet_relocation_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    // Main submissions table
    $table_name = $wpdb->prefix . 'pet_relocation';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        number_of_pets int NOT NULL,
        relocation_type varchar(20) NOT NULL,
        departure_address_line text NOT NULL,
        departure_city varchar(100) NOT NULL,
        departure_country varchar(100) NOT NULL,
        travel_date date NOT NULL,
        same_flight varchar(10) NOT NULL,
        flight_information text,
        arrival_address_line text NOT NULL,
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

/**
 * Updates database schema for existing installations.
 */
function pet_relocation_update_schema() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pet_relocation';
    $pets_table_name = $wpdb->prefix . 'pet_relocation_pets';
    
    // Check and rename columns in main table
    $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name");
    $column_names = array_column($columns, 'Field');
    
    // Rename departure_address to departure_address_line
    if (in_array('departure_address', $column_names) && !in_array('departure_address_line', $column_names)) {
        $wpdb->query("ALTER TABLE $table_name CHANGE departure_address departure_address_line TEXT NOT NULL");
        error_log('Renamed departure_address to departure_address_line');
    }
    
    // Rename arrival_address to arrival_address_line
    if (in_array('arrival_address', $column_names) && !in_array('arrival_address_line', $column_names)) {
        $wpdb->query("ALTER TABLE $table_name CHANGE arrival_address arrival_address_line TEXT NOT NULL");
        error_log('Renamed arrival_address to arrival_address_line');
    }
    
    // Rename flight_info to flight_information
    if (in_array('flight_info', $column_names) && !in_array('flight_information', $column_names)) {
        $wpdb->query("ALTER TABLE $table_name CHANGE flight_info flight_information TEXT");
        error_log('Renamed flight_info to flight_information');
    }
    
    // Add health_certificate if missing
    if (!in_array('health_certificate', $column_names)) {
        $wpdb->query("ALTER TABLE $table_name ADD health_certificate VARCHAR(20) NOT NULL AFTER post_arrival_support");
        error_log('Added health_certificate column');
    }
    
    // Remove health_certificate from pets table if it exists
    $pet_columns = $wpdb->get_results("SHOW COLUMNS FROM $pets_table_name");
    $pet_column_names = array_column($pet_columns, 'Field');
    if (in_array('health_certificate', $pet_column_names)) {
        $wpdb->query("ALTER TABLE $pets_table_name DROP COLUMN health_certificate");
        error_log('Dropped health_certificate from pets table');
    }
}

/**
 * Verifies schema on plugin load to catch missed updates.
 */
add_action('plugins_loaded', 'pet_relocation_verify_schema');

function pet_relocation_verify_schema() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pet_relocation';
    $pets_table_name = $wpdb->prefix . 'pet_relocation_pets';
    
    // Check if tables exist
    $main_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    $pets_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$pets_table_name'") == $pets_table_name;
    
    if (!$main_table_exists || !$pets_table_exists) {
        error_log('One or more tables missing. Creating tables...');
        pet_relocation_create_tables();
        pet_relocation_update_schema();
        return;
    }
    
    // Verify main table columns
    $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name");
    $column_names = array_column($columns, 'Field');
    $required_columns = [
        'id', 'number_of_pets', 'relocation_type', 'departure_address_line', 'departure_city',
        'departure_country', 'travel_date', 'same_flight', 'flight_information', 'arrival_address_line',
        'arrival_city', 'arrival_country', 'emergency_contact', 'grooming_required', 'post_arrival_support',
        'health_certificate', 'is_microchipped', 'vaccination_status', 'health_issues', 'iata_crate',
        'submission_date'
    ];
    
    foreach ($required_columns as $column) {
        if (!in_array($column, $column_names)) {
            error_log("Missing column $column in $table_name. Running schema update...");
            pet_relocation_update_schema();
            break;
        }
    }
}

/**
 * Enqueues front-end scripts and styles.
 */
add_action('wp_enqueue_scripts', 'pet_relocation_enqueue_scripts');

function pet_relocation_enqueue_scripts() {
    wp_enqueue_style('pet-relocation-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('pet-relocation-script', plugins_url('js/script.js', __FILE__), array('jquery'), '1.0.15', true);
    wp_localize_script('pet-relocation-script', 'petRelocation', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('pet_relocation_nonce')
    ));
}

/**
 * Adds admin menu for managing requests.
 */
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

/**
 * Displays the admin page.
 */
function pet_relocation_admin_page() {
    include plugin_dir_path(__FILE__) . 'admin/admin-page.php';
}

/**
 * Shortcode for the front-end form.
 */
add_shortcode('pet_relocation_form', 'pet_relocation_form_shortcode');

function pet_relocation_form_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/form.php';
    return ob_get_clean();
}

/**
 * Handles AJAX form submission.
 */
add_action('wp_ajax_submit_pet_relocation', 'handle_pet_relocation_submission');
add_action('wp_ajax_nopriv_submit_pet_relocation', 'handle_pet_relocation_submission');

function handle_pet_relocation_submission() {
    try {
        error_log('POST Data Received: ' . print_r($_POST, true));

        if (!check_ajax_referer('pet_relocation_nonce', 'nonce', false)) {
            $error = 'Nonce verification failed';
            error_log($error);
            wp_send_json_error($error);
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'pet_relocation';
        $pets_table_name = $wpdb->prefix . 'pet_relocation_pets';
        
        $pets_table_exists = $wpdb->get_var("SHOW TABLES LIKE '$pets_table_name'") == $pets_table_name;
        if (!$pets_table_exists) {
            $error = "Pets table $pets_table_name does not exist";
            error_log($error);
            wp_send_json_error($error);
        }
        
        $submission_data = array(
            'number_of_pets' => isset($_POST['number_of_pets']) ? intval($_POST['number_of_pets']) : 0,
            'relocation_type' => sanitize_text_field($_POST['relocation_type'] ?? ''),
            'departure_address_line' => sanitize_text_field($_POST['departure_address_line'] ?? ''),
            'departure_city' => sanitize_text_field($_POST['departure_city'] ?? ''),
            'departure_country' => sanitize_text_field($_POST['departure_country'] ?? ''),
            'travel_date' => sanitize_text_field($_POST['travel_date'] ?? ''),
            'same_flight' => sanitize_text_field($_POST['same_flight'] ?? ''),
            'flight_information' => sanitize_text_field($_POST['flight_information'] ?? ''),
            'arrival_address_line' => sanitize_text_field($_POST['arrival_address_line'] ?? ''),
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
        
        error_log('Sanitized Submission Data: ' . print_r($submission_data, true));
        
        $required_fields = [
            'number_of_pets',
            'relocation_type',
            'departure_address_line',
            'departure_city',
            'departure_country',
            'travel_date',
            'same_flight',
            'arrival_address_line',
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
                $error = "Missing required field: $field";
                error_log($error);
                wp_send_json_error($error);
            }
        }
        
        $result = $wpdb->insert($table_name, $submission_data);
        if ($result === false) {
            $error = 'Main table insertion failed: ' . $wpdb->last_error;
            error_log($error);
            wp_send_json_error($error);
        }
        
        $submission_id = $wpdb->insert_id;
        if (!$submission_id) {
            $error = 'No submission ID returned after main table insertion';
            error_log($error);
            wp_send_json_error($error);
        }
        
        $pets = isset($_POST['pets']) && is_array($_POST['pets']) ? $_POST['pets'] : [];
        error_log('Pets Data: ' . print_r($pets, true));
        
        if (!isset($pets[0])) {
            $error = 'No pet data provided for the first pet';
            error_log($error);
            wp_send_json_error($error);
        }
        
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
                $error = "First pet missing required field: $field";
                error_log($error);
                wp_send_json_error($error);
            }
        }
        
        $pet_result = $wpdb->insert($pets_table_name, $pet_data);
        if ($pet_result === false) {
            $error = 'First pet insertion failed: ' . $wpdb->last_error;
            error_log($error);
            wp_send_json_error($error);
        }
        
        for ($i = 1; $i < count($pets); $i++) {
            if (!isset($pets[$i]['additional_info']) || empty($pets[$i]['additional_info'])) {
                continue;
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
                $error = "Additional pet $i insertion failed: " . $wpdb->last_error;
                error_log($error);
                wp_send_json_error($error);
            }
        }
        
        $admin_email = get_option('admin_email');
        $subject = 'New Pet Relocation Request';
        $message = "A new pet relocation request has been submitted.\n\n";
        $message .= "Number of Pets: " . $submission_data['number_of_pets'] . "\n";
        $message .= "Departure: " . $submission_data['departure_city'] . "\n";
        $message .= "Arrival: " . $submission_data['arrival_city'] . "\n";
        
        wp_mail($admin_email, $subject, $message);
        
        wp_send_json_success('Form submitted successfully');
    } catch (Exception $e) {
        $error = 'Unexpected error: ' . $e->getMessage();
        error_log($error);
        wp_send_json_error($error);
    }
    
    wp_die();
}

/**
 * Handles AJAX request for viewing submission details.
 */
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
        ob_start();
        ?>
        <div class="request-detail-container">
            <div class="request-detail-section" style="background: #F9FAFB; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <h3 style="color: #0C5460; font-size: 18px; margin-bottom: 15px;">Submission Details</h3>
                <div class="request-detail-group">
                    <?php foreach ($request as $key => $value) {
                        if ($key !== 'id' && !empty($value)) {
                            echo '<div class="request-detail-item">';
                            echo '<div class="request-detail-label" style="font-weight: 600; color: #333; margin-bottom: 5px;">' . esc_html(ucwords(str_replace('_', ' ', $key))) . '</div>';
                            echo '<div class="request-detail-value" style="color: #666;">' . esc_html($value) . '</div>';
                            echo '</div>';
                        }
                    } ?>
                </div>
            </div>
            
            <?php if ($pets): ?>
            <div class="request-detail-section" style="background: #F9FAFB; padding: 20px; border-radius: 8px;">
                <h3 style="color: #0C5460; font-size: 18px; margin-bottom: 15px;">Pets</h3>
                <?php foreach ($pets as $index => $pet): ?>
                    <div class="request-detail-group" style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #E5E7EB;">
                        <div style="font-weight: 600; color: #333; margin-bottom: 10px;">Pet <?php echo $index + 1; ?></div>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Type</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->pet_type); ?></div>
                        </div>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Breed</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->breed); ?></div>
                        </div>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Age</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->age); ?></div>
                        </div>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Weight</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->weight); ?></div>
                        </div>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Spaying Status</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->spaying_status); ?></div>
                        </div>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Vaccination Report</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->vaccination_report); ?></div>
                        </div>
                        <?php if (!empty($pet->health_condition)): ?>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Health Condition</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->health_condition); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pet->specific_medicine)): ?>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Specific Medicine</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->specific_medicine); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pet->behaviour_training)): ?>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Behaviour/Training</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->behaviour_training); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pet->additional_info)): ?>
                        <div class="request-detail-item">
                            <div class="request-detail-label">Additional Info</div>
                            <div class="request-detail-value"><?php echo esc_html($pet->additional_info); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
        $details = ob_get_clean();
        wp_send_json_success($details);
    } else {
        wp_send_json_error('Request not found');
    }
    
    wp_die();
}
?>