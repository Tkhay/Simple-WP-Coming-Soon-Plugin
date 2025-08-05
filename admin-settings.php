<?php
if (!defined('ABSPATH')) {
    exit;
}

//Turn off the plugin roles after deactivating the plugin
register_deactivation_hook(__FILE__, 'ccs_remove_custom_capabilities');
function ccs_remove_custom_capabilities() {
    $roles = ['administrator', 'editor'];
    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            $role->remove_cap('access_coming_soon_settings');
        }
    }
}

add_action('admin_menu', 'ccs_add_admin_menu');
function ccs_add_admin_menu() {
    add_options_page(
        __('Coming Soon Settings', 'tieku-coming-soon'),
        __('Coming Soon', 'tieku-coming-soon'),
        'access_coming_soon_settings',
        'tieku-coming-soon',
        'ccs_options_page'
    );
}

add_action('admin_init', 'ccs_settings_init');
function ccs_settings_init() {
    register_setting('ccsSettings', 'ccs_enabled');
    register_setting('ccsSettings', 'ccs_heading');
    register_setting('ccsSettings', 'ccs_message');
    register_setting('ccsSettings', 'ccs_logo');
    register_setting('ccsSettings', 'ccs_bg_color');


    add_settings_section('ccs_section', __('Settings', 'wordpress'), null, 'ccsSettings');
    add_settings_field('ccs_enabled', __('Enable Coming Soon', 'wordpress'), 'ccs_enabled_render', 'ccsSettings', 'ccs_section');
    add_settings_field('ccs_heading', __('Heading', 'wordpress'), 'ccs_heading_render', 'ccsSettings', 'ccs_section');
    add_settings_field('ccs_message', __('Message', 'wordpress'), 'ccs_message_render', 'ccsSettings', 'ccs_section');
    add_settings_field('ccs_logo', 'Logo', 'ccs_logo_render', 'ccsSettings', 'ccs_section');
    add_settings_field('ccs_bg_color', 'Background Color', 'ccs_bg_color_render', 'ccsSettings', 'ccs_section');
}

function ccs_logo_render() {
    $logo = get_option('ccs_logo');
    ?>
    <input type="text" name="ccs_logo" id="ccs_logo" value="<?php echo esc_url($logo); ?>" style="width:60%;">
    <input type="button" id="ccs_logo_button" class="button" value="Upload Logo" />
    <?php
}

function ccs_bg_color_render() {
    $bg_color = get_option('ccs_bg_color', '#ffffff');
    ?>
    <input type="text" name="ccs_bg_color" value="<?php echo esc_attr($bg_color); ?>" class="ccs-color-field" />
    <?php
}


function ccs_enabled_render() {
    $enabled = get_option('ccs_enabled', '0');
    ?>
    <input type='checkbox' name='ccs_enabled' value='1' <?php checked(1, $enabled, true); ?>>
    <?php
}

function ccs_heading_render() {
    $heading = get_option('ccs_heading', 'Coming Soon');
    ?>
    <input type='text' name='ccs_heading' value='<?php echo esc_attr($heading); ?>'>
    <?php
}

function ccs_message_render() {
    $message = get_option('ccs_message', 'Our website is under construction.');
    ?>
    <textarea name='ccs_message'><?php echo esc_textarea($message); ?></textarea>
    <?php
}

function ccs_options_page() {
    ?>
    <form action='options.php' method='post'>
        <h2>Coming Soon Settings</h2>
        <?php
        settings_fields('ccsSettings');
        do_settings_sections('ccsSettings');
        submit_button();
        ?>
    </form>
    <?php
}

