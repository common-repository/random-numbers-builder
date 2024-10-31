<?php

class YrnOptionsConfig {

    public static function init() {
        global $YRN_TYPES;

        $YRN_TYPES['typeName'] = apply_filters('YRNTypes', array(
	        'text' => 1
        ));

        $YRN_TYPES['typePath'] = apply_filters('YRNTypePaths', array(
	        'text' => YRN_NUMBERS_PATH
        ));
        
        $YRN_TYPES['titles'] = apply_filters('YRNTitles', array(
	        'text' => __('Text type', YRN_TEXT_DOMAIN)
        ));
    }

    public static function optionsValues() {
        global $YRN_OPTIONS;
        $options = array();
        $dfaultOPtionsTemplease = array(
	        'yrn-number' => 10,
	        'yrn-label' => 'Label [currentIndex]'
        );
        $customOption1 = $dfaultOPtionsTemplease;
	    $customOption1['yrn-label'] = 'Label 1';
	    $customOption2 = $dfaultOPtionsTemplease;
	    $customOption2['yrn-label'] = 'Label 2';
	    $customOption3 = $dfaultOPtionsTemplease;
	    $customOption3['yrn-label'] = 'Label 3';
	    
        $defaultOptions = array(
        	'',
	        $customOption1,
	        $customOption2,
	        $customOption3
        );
	    $options[] = array('name' => 'yrn-conditions', 'type' => 'array', 'defaultValue' => $defaultOptions);
        $options[] = array('name' => 'YRN-show-after', 'type' => 'text', 'defaultValue' => '100');
	    $options[] = array('name' => 'YRN-button-text', 'type' => 'text', 'defaultValue' => __('Scroll to Top', YRN_TEXT_DOMAIN));
	    
	    $options[] = array('name' => 'yrn-numbers', 'type' => 'text', 'defaultValue' => __('10', YRN_TEXT_DOMAIN));
	    $options[] = array('name' => 'yrn-label', 'type' => 'text', 'defaultValue' => __('10', YRN_TEXT_DOMAIN));
	    $options[] = array('name' => 'yrn-classic-default', 'type' => 'text', 'defaultValue' => $dfaultOPtionsTemplease);
	    $options[] = array('name' => 'yrn-numbers-font-size', 'type' => 'text', 'defaultValue' => 'default');
	    $options[] = array('name' => 'yrn-label-font-size', 'type' => 'text', 'defaultValue' => 'default');

	    $options[] = array('name' => 'yrn-numbers-margin-top', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-numbers-margin-right', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-numbers-margin-bottom', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-numbers-margin-left', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-label-margin-top', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-label-margin-right', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-label-margin-bottom', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-label-margin-left', 'type' => 'text', 'defaultValue' => '0px');
	    $options[] = array('name' => 'yrn-enable-animation', 'type' => 'checkbox', 'defaultValue' => '');
	    $options[] = array('name' => 'yrn-animation-speed', 'type' => 'text', 'defaultValue' => '2');

        $YRN_OPTIONS = apply_filters('YRNDefaultOptions', $options);
    }

	public static function getDefaultTimezone() {
		$timezone = get_option('timezone_string');
		if (!$timezone) {
			$timezone = 'America/New_York';
		}

		return $timezone;
	}
}

YrnOptionsConfig::init();