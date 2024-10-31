<?php
/**
 * Plugin Name: Random numbers builder
 * Description: Random numbers customizable plugin.
 * Version: 1.1.6
 * Author: Adam Skaat
 * Author URI:
 * License: GPLv2
 */

/*If this file is called directly, abort.*/
if(!defined('WPINC')) {
    wp_die();
}

if(!defined('YRN_FILE_NAME')) {
    define('YRN_FILE_NAME', plugin_basename(__FILE__));
}

if(!defined('YRN_FOLDER_NAME')) {
    define('YRN_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'config/boot.php');
require_once(plugin_dir_path(__FILE__).'RandomNumbersInit.php');