<?php
/*
 * Pet Relocation Form - Admin Page
 * License: GPL-2.0+
 * Author: Md Shorov Abedin
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html__('Pet Relocation Requests', 'pet-relocation'); ?></h1>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php echo esc_html__('ID', 'pet-relocation'); ?></th>
                <th><?php echo esc_html__('Number of Pets', 'pet-relocation'); ?></th>
                <th><?php echo esc_html__('Submission Date', 'pet-relocation'); ?></th>
                <th><?php echo esc_html__('Actions', 'pet-relocation'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'pet_relocation';
            $requests = $wpdb->get_results("SELECT id, number_of_pets, submission_date FROM $table_name ORDER BY submission_date DESC");
            
            foreach ($requests as $request) {
                ?>
                <tr>
                    <td><?php echo esc_html($request->id); ?></td>
                    <td><?php echo esc_html($request->number_of_pets); ?></td>
                    <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($request->submission_date))); ?></td>
                    <td>
                        <button class="button view-details" data-id="<?php echo esc_attr($request->id); ?>">
                            <?php echo esc_html__('View Details', 'pet-relocation'); ?>
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal for Details -->
<div id="request-details-modal" style="display: none;">
    <div class="modal-content">
        <span class="modal-close">×</span>
        <div id="request-details-content"></div>
    </div>
</div>

<style>
    #request-details-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: #fff;
        padding: 20px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        border-radius: 8px;
        position: relative;
    }
    .modal-close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: #333;
    }
    .request-detail-container {
        font-size: 14px;
        line-height: 1.6;
    }
    .request-detail-section {
        margin-bottom: 20px;
    }
    .request-detail-group {
        display: grid;
        gap: 10px;
    }
    .request-detail-item {
        display: flex;
        flex-direction: column;
    }
    .request-detail-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .request-detail-value {
        color: #666;
    }
</style>

<script>
jQuery(document).ready(function($) {
    $('.view-details').on('click', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_request_details',
                id: id,
                nonce: '<?php echo wp_create_nonce('get_request_details'); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('#request-details-content').html(response.data);
                    $('#request-details-modal').show();
                } else {
                    alert('Error loading details: ' + response.data);
                }
            },
            error: function() {
                alert('Error loading details. Please try again.');
            }
        });
    });
    
    $('.modal-close').on('click', function() {
        $('#request-details-modal').hide();
        $('#request-details-content').html('');
    });
    
    $(document).on('click', function(event) {
        if ($(event.target).is('#request-details-modal')) {
            $('#request-details-modal').hide();
            $('#request-details-content').html('');
        }
    });
});
</script>