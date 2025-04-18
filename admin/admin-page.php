<?php
/*
 * Pet Relocation Form - Admin Page
 * License: GPL-2.0+
 * Author: Md Shorov Abedin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'pet_relocation';
$requests = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submission_date DESC");
?>

<div class="wrap">
    <h1>Pet Relocation Requests</h1>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th class="column-id">ID</th>
                <th class="column-number_of_pets">Number of Pets</th>
                <th class="column-departure">Departure</th>
                <th class="column-arrival">Arrival</th>
                <th class="column-travel_date">Travel Date</th>
                <th class="column-submission_date">Submission Date</th>
                <th class="column-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($requests): ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo esc_html($request->id); ?></td>
                        <td><?php echo esc_html($request->number_of_pets); ?></td>
                        <td><?php echo esc_html($request->departure_city); ?></td>
                        <td><?php echo esc_html($request->arrival_city); ?></td>
                        <td><?php echo esc_html($request->travel_date); ?></td>
                        <td><?php echo esc_html($request->submission_date); ?></td>
                        <td>
                            <button class="button view-details" data-id="<?php echo esc_attr($request->id); ?>">View Details</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for request details -->
<div id="request-details-modal" class="modal">
    <div class="modal-content">
        <span class="close">×</span>
        <h2 style="color: #0C5460; margin-bottom: 20px;">Request Details</h2>
        <div id="request-details"></div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.view-details').click(function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_request_details',
                id: id,
                nonce: '<?php echo wp_create_nonce("get_request_details"); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#request-details').html(response.data);
                    $('#request-details-modal').fadeIn(300);
                }
            }
        });
    });
    
    $('.close').click(function() {
        $('#request-details-modal').fadeOut(300);
    });
    
    $(window).click(function(e) {
        if ($(e.target).hasClass('modal')) {
            $('#request-details-modal').fadeOut(300);
        }
    });
});
</script>