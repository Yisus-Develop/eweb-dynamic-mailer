<?php
/**
 * Plugin Name: EWEB Dynamic Mailer
 * Description: Advanced Mass Mailing System with CSV support, open tracking, deduplication, and database-driven history. Part of the EWEB Plugin Suite.
 * Version: 1.0.0
 * Author: Yisus Develop
 * Author URI: https://github.com/Yisus-Develop
 * Plugin URI: https://enlaweb.co/
 * Text Domain: eweb-dynamic-mailer
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 8.1
 * Tested up to: 6.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * EWEB Dynamic Mailer - Developed by Yisus Develop
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Requirements Check
 */
function eweb_dynamic_mailer_check_requirements() {
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        add_action('admin_notices', function() {
            echo '<div class="error"><p>EWEB Dynamic Mailer requires PHP 7.4 or higher.</p></div>';
        });
        return false;
    }
    return true;
}

if (!eweb_dynamic_mailer_check_requirements()) {
    return;
}

define('MC_MAILER_PATH', plugin_dir_path(__FILE__));
define('MC_MAILER_URL', plugin_dir_url(__FILE__));

// Initialize GitHub Updater
if (is_admin()) {
    $updater_file = MC_MAILER_PATH . 'includes/class-eweb-github-updater.php';
    if (file_exists($updater_file)) {
        require_once $updater_file;
        new EWEB_GitHub_Updater(__FILE__, 'Yisus-Develop', 'eweb-dynamic-mailer');
    }
}

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
