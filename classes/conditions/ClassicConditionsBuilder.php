<?php
namespace yrn\conditions;
require_once dirname(__FILE__).'/ConditionsBuilder.php';

class ClassicConditionsBuilder extends ConditionsBuilder {
	private $typeObj;
	
	public function setTypeObj($typeObj) {
		$this->typeObj = $typeObj;
	}
	
	public function getTypeObj() {
		return $this->typeObj;
	}
	
	public function prepareRender() {
		$typeObj = $this->getTypeObj();
		$savedData = $typeObj->getOptionValue('yrn-conditions');
		
		$this->setSavedOptions($savedData);
	}
	
	public function render() {
		$this->prepareRender();
		$content = $this->getContent();
		
		return $content;
	}
}