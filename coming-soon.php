<?php
/**
 * Plugin Name: Tieku Coming Soon
 * Description: Displays a Coming Soon page for visitors when enabled.
 * Version: 1.0.1
 * Author: Tieku Asare
 */

if ( !defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'admin-settings.php';

// Add custom capability on activation
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

// Display Coming Soon page logic
add_action('template_redirect', 'ccs_show_coming_soon');

function ccs_show_coming_soon() {
    if (is_admin()) {
        return;
    }
    if (is_user_logged_in() && (current_user_can('manage_options') || current_user_can('edit_others_posts'))) {
        return;
    }

    $enabled = get_option('ccs_enabled', '0');
    if ($enabled !== '1') {
        return;
    }

    $logo = get_option('ccs_logo');
    $bg_color = get_option('ccs_bg_color', '#ffffff');

    wp_enqueue_style('ccs-style', plugins_url('css/style.css', __FILE__));
    status_header(503);
    echo '<div class="ccs-wrapper" style="background-color:' . esc_attr($bg_color) . ';">';

    if ($logo) {
        echo '<img src="' . esc_url($logo) . '" alt="Coming Soon Logo" style="max-width:200px;">';
    }

    echo '<h1>' . esc_html(get_option('ccs_heading', 'Coming Soon')) . '</h1>';
    echo '<p>' . esc_html(get_option('ccs_message', 'Our website is under construction.')) . '</p>';
    echo '</div>';
    exit;
}
