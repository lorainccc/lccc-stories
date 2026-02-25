<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.lorainccc.edu
 * @since             1.0.0
 * @package           lccc-stories
 *
 * @wordpress-plugin
 * Plugin Name:       LCCC Stories
 * Plugin URI:        http://www.lorainccc.edu
 * Description:       Custom Post Type and Custom Fields for LCCC Stories Feature
 * Version:           1.0.0
 * Author:            LCCC Web Dev Team
 * Author URI:        http://www.lorainccc.edu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lorainccc
 * Domain Path:       /languages
 */

require_once( plugin_dir_path( __FILE__ ).'php/lccc-stories-cpt.php');
require_once( plugin_dir_path( __FILE__ ).'php/lccc-stories-metabox.php');
require_once( plugin_dir_path( __FILE__ ).'php/lccc-stories-import.php');

add_action('admin_enqueue_scripts', function($hook) {
    global $post;

    if (($hook == 'post.php' || $hook == 'post-new.php') && isset($post)) {
        wp_enqueue_media();
        wp_enqueue_script(
            'custom-meta-box',
            plugin_dir_url( __FILE__ ) . 'js/lc-stories-metabox-fields.js',
            ['jquery'],
            null,
            true
        );
    }
});

add_action( 'admin_enqueue_scripts', function ($hook) {
    global $post;

    if (($hook == 'post.php' || $hook == 'post-new.php') && isset($post)) {
        wp_enqueue_style('lc_google_material_icons', '//fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=cancel', 40);
        wp_enqueue_style('lc_stories_admin_styles', plugin_dir_url( __FILE__ ) . 'css/lc-stories-admin.css', 40);
    }
});

