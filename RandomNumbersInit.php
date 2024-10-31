<?php
namespace yrn;

class RandomNumbersInit {

    private static $instance = null;
    private $actions;
    private $filters;

    private function __construct() {
        $this->init();
    }

    private function __clone() {
    }

    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() {
	    register_activation_hook(YRN_PREFIX, array($this, 'activate'));
	    register_deactivation_hook(YRN_PREFIX, array($this, 'deactivate'));
	    add_action('admin_init', array($this, 'pluginRedirect'));
        $this->includeData();
        $this->actions();
        $this->filters();
    }

    private function includeData() {
        require_once YRN_HELPERS_PATH.'HelperFunctions.php';
        require_once YRN_HELPERS_PATH.'ScriptsIncluder.php';
        require_once YRN_HELPERS_PATH.'MultipleChoiceButton.php';
        require_once YRN_HELPERS_PATH.'AdminHelper.php';
        require_once YRN_CONDITIONS_PATH.'ClassicConditionsBuilder.php';
        require_once YRN_CLASSES_PATH.'ScrollType.php';
	    require_once YRN_NUMBERS_PATH.'YrnModel.php';
        require_once YRN_NUMBERS_PATH.'Numbers.php';
        require_once YRN_CSS_PATH.'Css.php';
        require_once YRN_JS_PATH.'Js.php';
        require_once YRN_CLASSES_PATH.'RegisterPostType.php';
        require_once YRN_CLASSES_PATH.'IncludeToPage.php';
        require_once YRN_CLASSES_PATH.'Shortcode.php';
        require_once YRN_CLASSES_PATH.'Actions.php';
        require_once YRN_CLASSES_PATH.'Ajax.php';
		require_once YRN_CLASSES_PATH.'Filters.php';
		require_once YRN_CLASSES_PATH.'Installer.php';
    }

    public function actions() {
        $this->actions = new Actions();
    }

    public function filters() {
        $this->filters = new Filters();
    }

    public function activate() {
	    Installer::install();
    }
	
	public function pluginRedirect() {
		if (!get_option('yrn_redirect')) {
			update_option('yrn_redirect', 1);
			exit(wp_redirect(admin_url('edit.php?post_type='.YRN_POST_TYPE)));
		}
	}
	
	public function deactivate() {
		delete_option('yrn_redirect');
	}
}

RandomNumbersInit::getInstance();