<?php

class YrnNumbers {
    public static function addDefine($name, $value) {
        if(!defined($name)) {
            define($name, $value);
        }
    }

    public static function init() {
        self::addDefine('YRN_PREFIX', YRN_FILE_NAME);
        self::addDefine('YRN_ADMIN_URL', admin_url());
        self::addDefine('YRN_BUILDER_URL', plugins_url().'/'.YRN_FOLDER_NAME.'/');
        self::addDefine('YRN_ADMIN_URL', admin_url());
        self::addDefine('YRN_URL', plugins_url().'/'.YRN_FOLDER_NAME.'/');
        self::addDefine('YRN_ASSETS_URL', YRN_URL.'assets/');
        self::addDefine('YRN_CSS_URL', YRN_ASSETS_URL.'css/');
        self::addDefine('YRN_JS_URL', YRN_ASSETS_URL.'js/');
        self::addDefine('YRN_IMG_URL', YRN_ASSETS_URL.'img/');
        self::addDefine('YRN_LIB_URL', YRN_URL.'lib/');
        self::addDefine('YRN_PATH', WP_PLUGIN_DIR.'/'.YRN_FOLDER_NAME.'/');
        self::addDefine('YRN_CLASSES_PATH', YRN_PATH.'classes/');
        self::addDefine('YRN_CONDITIONS_PATH', YRN_CLASSES_PATH.'conditions/');
        self::addDefine('YRN_DATA_TABLE_PATH', YRN_CLASSES_PATH.'dataTable/');
        self::addDefine('YRN_LIB_PATH', YRN_PATH.'lib/');
        self::addDefine('YRN_HELPERS_PATH', YRN_PATH.'helpers/');
        self::addDefine('YRN_CONFIG_PATH', YRN_PATH.'config/');
        self::addDefine('YRN_ASSETS_PATH', YRN_PATH.'/assets/');
        self::addDefine('YRN_VIEWS_PATH', YRN_ASSETS_PATH.'views/');
        self::addDefine('YRN_VIEWS_METABOXES_PATH', YRN_VIEWS_PATH.'metaboxes/');
        self::addDefine('YRN_TYPES_VIEWS_PATH', YRN_VIEWS_PATH.'types/');
        self::addDefine('YRN_PREVIEW_VIEWS_PATH', YRN_VIEWS_PATH.'preview/');
        self::addDefine('YRN_CSS_PATH', YRN_ASSETS_PATH.'css/');
        self::addDefine('YRN_JS_PATH', YRN_ASSETS_PATH.'js/');
        self::addDefine('YRN_NUMBERS_PATH', YRN_CLASSES_PATH.'types/');
        self::addDefine('YRN_HELPERS_PATH', YRN_PATH.'helpers/');
        self::addDefine('YRN_POST_TYPE', 'yrnnumbers');
        self::addDefine('YRN_DEFAULT_TYPE', 'text');
	    self::addDefine('YRN_POSTS_TABLE_NAME', 'posts');
        self::addDefine('YRN_TEXT_DOMAIN', 'yrnnumbers');
        self::addDefine('YRN_PRO_URL', 'https://edmonsoft.com/random-numbers');
        self::addDefine('YRN_NUMBERS_SUPPORT_URL', 'https://wordpress.org/support/plugin/random-numbers-builder/');
	    self::addDefine('YRN_FILTER_REPEAT_INTERVAL', 50);
	    self::addDefine('YRN_CRON_REPEAT_INTERVAL', 1);
	    self::addDefine('YRN_TABLE_LIMIT', 15);
        self::addDefine('YRN_VERSION_TEXT', '1.1.6');
        self::addDefine('YRN_LAST_UPDATE_DATE', 'Sep 29');
        self::addDefine('YRN_NEXT_UPDATE_DATE', 'Oct 29');
        self::addDefine('YRN_VERSION', 1.16);
        self::addDefine('YRN_VERSION_PRO', 1.0);
        self::addDefine('YRN_FREE_VERSION', 1);
        self::addDefine('YRN_SILVER_VERSION', 2);
        self::addDefine('YRN_GOLD_VERSION', 3);
        self::addDefine('YRN_PLATINUM_VERSION', 4);
        require_once(dirname(__FILE__).'/config-pkg.php');
    }

    public static function getVersionString() {
	    $version = 'YRN_VERSION='.YRN_VERSION;
	    if(YRN_PKG_VERSION > YRN_FREE_VERSION) {
		    $version = 'YRN_VERSION_PRO=' . YRN_VERSION_PRO.";";
	    }

	    return $version;
    }

    public static function headerScript() {
		$version = self::getVersionString();

		ob_start();
		?>
			<script type="text/javascript">
				<?= $version; ?>
			</script>
	    <?php
	    $content = ob_get_contents();
	    ob_get_clean();

	    return $content;
    }
}

YrnNumbers::init();