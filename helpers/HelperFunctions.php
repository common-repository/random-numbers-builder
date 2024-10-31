<?php
namespace yrn;
use \SxGeo;

class HelperFunctions {

	public static function createAttrs($attrs) {
		$attrString = '';
		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		return $attrString;
	}

	public static function createSelectBox($data, $selectedValue, $attrs) {
		$selected = '';
		$attrString = self::createAttrs($attrs);

		$selectBox = '<select '.$attrString.'>';

		foreach($data as $value => $label) {

			/*When is multiselect*/
			if(is_array($selectedValue)) {
				$isSelected = in_array($value, $selectedValue);
				if($isSelected) {
					$selected = 'selected';
				}
			}
			else if($selectedValue == $value) {
				$selected = 'selected';
			}
			else if(is_array($value) && in_array($selectedValue, $value)) {
				$selected = 'selected';
			}

			$selectBox .= '<option value="'.esc_attr($value).'" '.$selected.'>'.$label.'</option>';
			$selected = '';
		}

		$selectBox .= '</select>';

		return $selectBox;
	}
}