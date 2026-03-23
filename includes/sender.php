<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_mc_mailer_send', 'mc_mailer_handle_send');

function mc_mailer_handle_send() {
    // Security Check
    // check_ajax_referer('mc_mailer_nonce', 'nonce'); // TODO: Add nonce in UI

    global $wpdb;
    $table_name = $wpdb->prefix . 'mc_mailer_logs';

    $to = sanitize_email($_POST['email']);
    $name = sanitize_text_field($_POST['name']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = wp_kses_post($_POST['message']); // Allow HTML
    $country = sanitize_text_field($_POST['country']);
    
    // Config
    $from_email = sanitize_email($_POST['from_email']);
    $bcc_email = sanitize_email($_POST['bcc_email']);
    $request_receipt = sanitize_text_field($_POST['request_receipt']) === 'true';

    if (!is_email($to)) {
        wp_send_json_error(['message' => 'Email inválido: ' . $to]);
    }

    // 1. Deduplication Check
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE email = %s", 
        $to
    ));

    if ($exists) {
        wp_send_json_error(['message' => 'Duplicado (Ya enviado): ' . $to, 'code' => 'duplicate']);
    }

    // 2. Prepare Headers
    $headers = [];
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    if ($from_email) {
        $headers[] = 'From: ' . $from_email; // May be overridden by SMTP plugin
        $headers[] = 'Reply-To: ' . $from_email;
    }
    if ($bcc_email && is_email($bcc_email)) {
        $headers[] = 'BCC: ' . $bcc_email;
    }
    if ($request_receipt) {
        $headers[] = 'Disposition-Notification-To: ' . ($from_email ?: get_option('admin_email'));
    }

    // 3. Add Tracking Pixel
    $hash = wp_generate_password(32, false);
    $tracking_url = get_rest_url(null, 'mc-mailer/v1/track') . '?h=' . $hash;
    $message .= '<img src="' . esc_url($tracking_url) . '" width="1" height="1" style="display:none;" />';

    // 4. Send
    $sent = wp_mail($to, $subject, $message, $headers);

    if ($sent) {
        // Log Success
        $wpdb->insert(
            $table_name,
            array(
                'email' => $to,
                'recipient_name' => $name,
                'country' => $country,
                'subject' => $subject,
                'tracking_hash' => $hash,
                'sent_at' => current_time('mysql')
            )
        );
        wp_send_json_success(['message' => 'Enviado correctamente a ' . $to]);
    } else {
        wp_send_json_error(['message' => 'Error wp_mail al enviar a ' . $to]);
    }
}
