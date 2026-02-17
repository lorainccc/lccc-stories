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
require_once( plugin_dir_path( __FILE__ ).'php/lccc-stories-import.php');