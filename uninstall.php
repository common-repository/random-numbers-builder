<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit();
}
use YRN\Installer;
if (!defined('YRN_FILE_NAME')) {
	define('YRN_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('YRN_FOLDER_NAME')) {
	define('YRN_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'config/boot.php');
require_once(YRN_CLASSES_PATH.'Installer.php');
Installer::uninstall();
