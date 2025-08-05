<?php
/**
 * Plugin Name: Tieku Coming Soon
 * Description: Displays a Coming Soon page for visitors when enabled.
 * Version: 1.0
 * Author: Tieku Asare
 */

if ( !defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'admin-settings.php';


register_activation_hook(__FILE__, 'ccs_add_custom_capabilities');

function ccs_add_custom_capabilities() {
    $roles = ['administrator', 'editor'];

    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            $role->add_cap('access_coming_soon_settings');
        }
    }
}

// Create the settings page
add_action('template_redirect', 'ccs_show_coming_soon');
function ccs_show_coming_soon() {
    if (is_user_logged_in() && current_user_can('manage_options')) {
        return;
    }

    $enabled = get_option('ccs_enabled', '0');

    if ($enabled === '1') {
        wp_enqueue_style('ccs-style', plugins_url('css/style.css', __FILE__));
        status_header(503);
        echo '<div class="ccs-wrapper">';
        echo '<h1>' . esc_html(get_option('ccs_heading', 'Coming Soon')) . '</h1>';
        echo '<p>' . esc_html(get_option('ccs_message', 'Our website is under construction.')) . '</p>';
        echo '</div>';
        exit;
    }
}


