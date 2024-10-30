<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              elementinvader.com
 * @since             1.0.0
 * @package           Color_Changer_Elementor
 *
 * @wordpress-plugin
 * Plugin Name:       Color Change For Elementor
 * Description:       Plugin will help you Replace / Change Colors defined inside Elementor Editor on all pages automatically  in one simple step
 * Version:           1.0.0
 * Author:            ElementInvader
 * Author URI:        elementinvader.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       color-changer-elementor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COLOR_CHANGER_ELEMENTOR_VERSION', '1.0.0' );
define( 'COLOR_CHANGER_ELEMENTOR_PATH', plugin_dir_path( __FILE__ ) );
define( 'COLOR_CHANGER_ELEMENTORT_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-color-changer-elementor-activator.php
 */
function activate_color_changer_elementor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-color-changer-elementor-activator.php';
	Color_Changer_Elementor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-color-changer-elementor-deactivator.php
 */
function deactivate_color_changer_elementor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-color-changer-elementor-deactivator.php';
	Color_Changer_Elementor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_color_changer_elementor' );
register_deactivation_hook( __FILE__, 'deactivate_color_changer_elementor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-color-changer-elementor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_color_changer_elementor() {

	$plugin = new Color_Changer_Elementor();
	$plugin->run();

}
run_color_changer_elementor();
