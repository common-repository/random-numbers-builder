<?php
namespace yrn;

class Shortcode {
	public function __construct() {
		$this->init();
	}
	
	private function init() {
		add_shortcode('yrn_numbers', array($this, 'numbers'), 1);
	}
	
	public function numbers($args) {
		if(empty($args['id'])) {
			return '';
		}
		$id = (int)$args['id'];
		$obj = Numbers::find($id);
		if(empty($obj)) {
			return '';
		}
		
		return $obj->render();
	}
	
	public static function getInstance() {
		new self();
	}
}