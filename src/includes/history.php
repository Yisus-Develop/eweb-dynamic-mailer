<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_mc_mailer_get_history', 'mc_mailer_handle_get_history');

function mc_mailer_handle_get_history() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mc_mailer_logs';

    // Get last 100 logs (Lightweight query)
    $results = $wpdb->get_results("
        SELECT id, email, recipient_name, country, sent_at, is_opened 
        FROM $table_name 
        ORDER BY sent_at DESC 
        LIMIT 100
    ");

    $stats = $wpdb->get_row("
        SELECT 
            COUNT(*) as total, 
            SUM(CASE WHEN is_opened = 1 THEN 1 ELSE 0 END) as opened 
        FROM $table_name
    ");

    wp_send_json_success([
        'logs' => $results,
        'stats' => $stats
    ]);
}

add_action('wp_ajax_mc_mailer_delete_log', 'mc_mailer_handle_delete_log');
function mc_mailer_handle_delete_log() {
    global $wpdb;
    $id = intval($_POST['id']);
    if (!$id) wp_send_json_error('Invalid ID');

    $table_name = $wpdb->prefix . 'mc_mailer_logs';
    $wpdb->delete($table_name, ['id' => $id]);
    
    wp_send_json_success();
}
