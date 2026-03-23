<?php
if (!defined('ABSPATH')) exit;

// Register Tracking Endpoint
add_action('rest_api_init', function () {
    register_rest_route('mc-mailer/v1', '/track', array(
        'methods' => 'GET',
        'callback' => 'mc_mailer_track_open',
        'permission_callback' => '__return_true'
    ));
});

function mc_mailer_track_open($request) {
    global $wpdb;
    $hash = $request->get_param('h');

    if ($hash) {
        $table_name = $wpdb->prefix . 'mc_mailer_logs';
        $wpdb->update(
            $table_name,
            array(
                'is_opened' => 1,
                'opened_at' => current_time('mysql')
            ),
            array('tracking_hash' => $hash),
            array('%d', '%s'),
            array('%s')
        );
    }

    // Serve 1x1 Transparent GIF
    header('Content-Type: image/gif');
    echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
    exit;
}
