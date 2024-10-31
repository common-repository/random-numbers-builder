<?php
namespace YRN;

class Css {

    public function __construct() {
        $this->init();
    }

    public function init() {

        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
    }

    public function enqueueStyles($hook) {

	    $allowedPages = array(
	    );
	    
        if(in_array($hook, $allowedPages) || get_post_type(@$_GET['post']) == YRN_POST_TYPE) {
        	
	        ScriptsIncluder::registerStyle('admin.css');
	        ScriptsIncluder::registerStyle('bootstrap.css');
	        ScriptsIncluder::registerStyle('select2.css');
	
	        ScriptsIncluder::enqueueStyle('bootstrap.css');
	        ScriptsIncluder::enqueueStyle('admin.css');
	        ScriptsIncluder::enqueueStyle('select2.css');
        }
    }
}

new Css();