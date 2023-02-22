<?php
/*
Plugin Name: Quick-Caching
Plugin URI: https://github.com/Grey-sz/Quick-Caching
Description: A caching plugin for WordPress websites
Version: 1.0
Author: Grey SZ
Author URI: https://github.com/Grey-sz
License: GPL2
*/

// Start the plugin

add_action('plugins_loaded', 'wp_caching_plugin_init');
 
function wp_caching_plugin_init() {
    //Check if caching is supported
    if (function_exists('wp_cache_get') && function_exists('wp_cache_set')) {
        // Enable caching
        add_action('template_redirect', 'wp_caching_plugin_start_caching', 1);
    }
    //Create a settings page
    add_action('admin_menu', 'wp_caching_plugin_create_menu');
}

//Create a settings page
function wp_caching_plugin_create_menu() {
    add_menu_page('WP Caching Plugin', 'WP Caching Plugin', 'administrator', __FILE__, 'wp_caching_plugin_settings_page', plugins_url('/images/icon.png', __FILE__));
    add_action('admin_init', 'wp_caching_plugin_register_settings');
}

//Register settings
function wp_caching_plugin_register_settings() {
    register_setting('wp-caching-plugin-settings-group', 'wp_caching_plugin_cache_key');
    register_setting('wp-caching-plugin-settings-group', 'wp_caching_plugin_cache_time');
    register_setting('wp-caching-plugin-settings-group', 'wp_caching_plugin_enabled');
}

//Create options page
function wp_caching_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>WP Caching Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('wp-caching-plugin-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Cache Key</th>
                    <td><input type="text" name="wp_caching_plugin_cache_key" value="<?php echo get_option('wp_caching_plugin_cache_key'); ?>" /></td>
                </tr>
                 
                <tr valign="top">
                    <th scope="row">Cache Time (in seconds)</th>
                    <td><input type="text" name="wp_caching_plugin_cache_time" value="<?php echo get_option('wp_caching_plugin_cache_time'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable Plugin</th>
                    <td><input type="checkbox" name="wp_caching_plugin_enabled" value="1" <?php checked(1, get_option('wp_caching_plugin_enabled'), true); ?> /></td>
                </tr>
            </table>
             
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Start Caching
 
function wp_caching_plugin_start_caching() {
    // Check if the plugin is enabled
    if (get_option('wp_caching_plugin_enabled') != 1) {
        // Plugin is not enabled, so exit
        return;
    }
 
    // Get the cache key
    $cache_key = get_option('wp_caching_plugin_cache_key');
 
    // Get the cache time
    $cache_time = get_option('wp_caching_plugin_cache_time');
 
    // Get the cache
    $cache = wp_cache_get($cache_key);
 
    // Check if the cache exists
    if (false !== $cache) {
        // The cache exists, so output it and exit
        echo $cache;
        exit;
    }
 
    // No cache exists, start output buffering
    ob_start();
 
    // This is where your content would go
    echo 'This is the content of your page.';
 
    // Store the output buffer in the cache
    $cache = ob_get_flush();
    wp_cache_set($cache_key, $cache, $cache_time);
}

// End the plugin

?>
