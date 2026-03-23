<?php
/**
 * Plugin Name: Mars Dynamic Mailer
 * Description: Sistema de envío masivo con soporte para CSV flexible, tracking de aperturas y deduplicación.
 * Version: 1.0.0
 * Author: AI-Vault Agent
 * Text Domain: mc-dynamic-mailer
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MC_MAILER_PATH', plugin_dir_path(__FILE__));
define('MC_MAILER_URL', plugin_dir_url(__FILE__));

// Includes
require_once MC_MAILER_PATH . 'includes/db-handler.php';
require_once MC_MAILER_PATH . 'includes/tracker.php';
require_once MC_MAILER_PATH . 'includes/sender.php';
require_once MC_MAILER_PATH . 'includes/history.php';

// Admin Menu
add_action('admin_menu', 'mc_mailer_menu');
function mc_mailer_menu() {
    add_menu_page(
        'Mars Mailer',
        'Mars Mailer',
        'manage_options',
        'mc-dynamic-mailer',
        'mc_mailer_render_page',
        'dashicons-email',
        25
    );
}

// Render Admin Page
function mc_mailer_render_page() {
    require_once MC_MAILER_PATH . 'admin/ui-page.php';
}

// Activation Hook: Create DB Table
register_activation_hook(__FILE__, 'mc_mailer_activate');
function mc_mailer_activate() {
    mc_mailer_create_table();
}
