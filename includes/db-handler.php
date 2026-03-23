<?php
if (!defined('ABSPATH')) exit;

function mc_mailer_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mc_mailer_logs';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        recipient_name varchar(255),
        country varchar(10),
        subject varchar(255),
        sent_at datetime DEFAULT CURRENT_TIMESTAMP,
        is_opened boolean DEFAULT 0,
        opened_at datetime,
        tracking_hash varchar(64),
        PRIMARY KEY  (id),
        KEY email (email),
        KEY tracking_hash (tracking_hash)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
