<?php
namespace yrn;

class Js {

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
	}
	
	private function gutenbergParams() {
		$settings = array(
			'allCountdowns' => Numbers::shapeIdTitleData(),
			'title'   => __('Random numbers', YRN_TEXT_DOMAIN),
			'description'   => __('This block will help you to add countdown’s shortcode inside the page content', YRN_TEXT_DOMAIN),
			'logo_classname' => 'yrn-gutenberg-logo',
			'numbers_select' => __('Select Random number', YRN_TEXT_DOMAIN)
		);
		
		return $settings;
	}

	public function enqueueStyles($hook) {
		$allowedPages = array(
		);
		$blockSettings = $this->gutenbergParams();
		ScriptsIncluder::registerScript('WpYrnBlockMin.js');
		ScriptsIncluder::localizeScript('WpYrnBlockMin.js', 'YRN_GUTENBERG_PARAMS', $blockSettings);
		ScriptsIncluder::enqueueScript('WpYrnBlockMin.js');
		
		ScriptsIncluder::registerScript('Admin.js');
		ScriptsIncluder::registerScript('select2.js');
		
		if(in_array($hook, $allowedPages) || get_post_type(@$_GET['post']) == YRN_POST_TYPE) {
			$localizedData = array(
				'nonce' => wp_create_nonce('ydn_ajax_nonce'),
				'copied' => __('Copied', YRN_TEXT_DOMAIN),
				'copyToClipboard' => __('Copy to clipboard', YRN_TEXT_DOMAIN),
			);
			
			ScriptsIncluder::enqueueScript('select2.js');
			ScriptsIncluder::localizeScript('Admin.js', 'yrn_admin_localized', $localizedData);
			ScriptsIncluder::enqueueScript('Admin.js');
		}
	}
}

new Js();