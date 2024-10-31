<?php
namespace yrn;
use \YrnOptionsConfig;

abstract class Numbers {

    private $id;
    private $type;
    private $title;
    private $savedData;
    private $sanitizedData;
    private $shortCodeArgs;
    private $shortCodeContent;
    private $currentConditionOptions;

    abstract protected function getViewContent();

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return (int)$this->id;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }
    
    public function setCurrentConditionOptions($currentConditionOptions) {
        $this->currentConditionOptions = $currentConditionOptions;
    }

    public function getCurrentConditionOptions() {
        return $this->currentConditionOptions;
    }
    
    public function getTypeTitle() {
    	$type = $this->getType();
	    global $YRN_TYPES;
	    $titles = $YRN_TYPES['titles'];
	    
	    $typeTitle = (isset($titles[$type])) ? $titles[$type] : __('Unknown Type', YRN_TEXT_DOMAIN);
	    
	    return $typeTitle;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setShortCodeContent($shortCodeContent) {
        $this->shortCodeContent = $shortCodeContent;
    }

    public function getShortCodeContent() {
        return $this->shortCodeContent;
    }

    public function setShortCodeArgs($shortCodeArgs) {
        $this->shortCodeArgs = $shortCodeArgs;
    }

    public function getShortCodeArgs() {
        return $this->shortCodeArgs;
    }

    public function setSavedData($savedData) {
        $this->savedData = $savedData;
    }

    public function getSavedData() {
        return $this->savedData;
    }

    public function insertIntoSanitizedData($sanitizedData) {
        if (!empty($sanitizedData)) {
            $this->sanitizedData[$sanitizedData['name']] = $sanitizedData['value'];
        }
    }

    public function getSanitizedData() {
        return $this->sanitizedData;
    }

    public static function create($data = array()) {
        $obj = new static();
        $id = $data['yrn-post-id'];
        $obj->setId($id);
  
        // set up apply filter
	    YrnOptionsConfig::optionsValues();
        foreach ($data as $name => $value) {
            $defaultData = $obj->getDefaultDataByName($name);
            if (empty($defaultData['type'])) {
                $defaultData['type'] = 'string';
            }
            $sanitizedValue = $obj->sanitizeValueByType($value, $defaultData['type']);
            $obj->insertIntoSanitizedData(array('name' => $name,'value' => $sanitizedValue));
        }

        $result = $obj->save();
    }

    public function save() {
        $options = $this->getSanitizedData();
        $postId = $this->getId();
		
        update_post_meta($postId, 'YRN_options', $options);
    }

    public function sanitizeValueByType($value, $type) {
        switch ($type) {
            case 'string':
            case 'number':
                $sanitizedValue = sanitize_text_field($value);
                break;
            case 'html':
                $sanitizedValue = $value;
                break;
            case 'array':
                $sanitizedValue = $this->recursiveSanitizeTextField($value);
                break;
            case 'email':
                $sanitizedValue = sanitize_email($value);
                break;
            case "checkbox":
                $sanitizedValue = sanitize_text_field($value);
                break;
            default:
                $sanitizedValue = sanitize_text_field($value);
                break;
        }

        return $sanitizedValue;
    }

    public function recursiveSanitizeTextField($array) {
        if (!is_array($array)) {
            return $array;
        }

        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursiveSanitizeTextField($value);
            }
            else {
                /*get simple field type and do sensitization*/
                $defaultData = $this->getDefaultDataByName($key);
                if (empty($defaultData['type'])) {
                    $defaultData['type'] = 'string';
                }
                $value = $this->sanitizeValueByType($value, $defaultData['type']);
            }
        }

        return $array;
    }

    public function getDefaultDataByName($optionName) {
        global $YRN_OPTIONS;

        foreach ($YRN_OPTIONS as $option) {
            if ($option['name'] == $optionName) {
                return $option;
            }
        }

        return array();
    }

    public function getDefaultValue($optionName) {

    	if (empty($optionName)) {
    		return '';
	    }

	    $defaultData = $this->getDefaultDataByName($optionName);

    	if (empty($defaultData['defaultValue'])) {
    		return '';
	    }

	    return $defaultData['defaultValue'];
    }

    public function isAllowOption($optionName) {
    	if(YRN_PKG_VERSION == YRN_FREE_VERSION) {
    		return true;
	    }
	    $defaultData = $this->getDefaultDataByName($optionName);

    	if(empty($defaultData['available'])) {
    		return true;
	    }

	    return YRN_PKG_VERSION >= $defaultData['available'];
    }

    public static function parseScrollDataFromData($data) {
        $cdData = array();

        foreach ($data as $key => $value) {
            if (strpos($key, 'yrn') === 0) {
                $cdData[$key] = $value;
            }
        }

        return $cdData;
    }

    public static function getClassNameScrollType($type) {
        $typeName = ucfirst(strtolower($type));
        $className = $typeName.'Numbers';

        return $className;
    }

    public static function getTypePathFormScrollType($type) {
        global $YRN_TYPES;
        $typePath = '';

        if (!empty($YRN_TYPES['typePath'][$type])) {
            $typePath = $YRN_TYPES['typePath'][$type];
        }

        return $typePath;
    }

    /**
     * Get option value from name
     * @since 1.0.0
     *
     * @param string $optionName
     * @param bool $forceDefaultValue
     * @return string
     */
    public function getOptionValue($optionName, $forceDefaultValue = false) {
        $savedData = YrnModel::getDataById($this->getId());
        $this->setSavedData($savedData);

        return $this->getOptionValueFromSavedData($optionName, $forceDefaultValue);
    }
    
    public function getConditionsOptionValue($optionName, $forceDefaultValue = false) {
        $savedData = $this->getCurrentConditionOptions();
       
        $this->setSavedData($savedData);

        return $this->getOptionValueFromSavedData($optionName, $forceDefaultValue);
    }

    public function getOptionValueFromSavedData($optionName, $forceDefaultValue = false) {
    	
        $defaultData = $this->getDefaultDataByName($optionName);
        $savedData = $this->getSavedData();
		
        $optionValue = null;

        if (empty($defaultData['type'])) {
            $defaultData['type'] = 'string';
        }

        if (!empty($savedData)) { //edit mode
            if (isset($savedData[$optionName])) { //option exists in the database
                $optionValue = $savedData[$optionName];
            }
            /* if it's a checkbox, it may not exist in the db
             * if we don't care about it's existence, return empty string
             * otherwise, go for it's default value
             */
            else if ($defaultData['type'] == 'checkbox' && !$forceDefaultValue) {
                $optionValue = '';
            }
        }

        if (($optionValue === null && !empty($defaultData['defaultValue'])) || ($defaultData['type'] == 'number' && !isset($optionValue))) {
            $optionValue = $defaultData['defaultValue'];
        }

        if ($defaultData['type'] == 'checkbox') {
            $optionValue = $this->boolToChecked($optionValue);
        }

	    if(isset($defaultData['ver']) && $defaultData['ver'] > YRN_PKG_VERSION) {
		    if (empty($defaultData['allow'])) {
			    return $defaultData['defaultValue'];
		    }
		    else if (!in_array($optionValue, $defaultData['allow'])) {
			    return $defaultData['defaultValue'];
		    }
	    }

        return $optionValue;
    }

    public static function getPostSavedData($postId) {
        $savedData = get_post_meta($postId, 'YRN_options');
		
        if (empty($savedData)) {
        	return $savedData;
        }
        $savedData = $savedData[0];

        return $savedData;
    }

    public static function find($id) {
		$options = YrnModel::getDataById($id);
		
		if(empty($options)) {
			return false;
		}
		$type = $options['yrn-type'];

	    $typePath = self::getTypePathFormScrollType($type);
	    $className = self::getClassNameScrollType($type);

	    if (!file_exists($typePath.$className.'.php')) {
		    return false;
	    }

	    require_once($typePath.$className.'.php');
	    $className = __NAMESPACE__.'\\'.$className;
	    $postTitle = get_the_title($id);

	    $typeObj = new $className();
	    $typeObj->setId($id);
	    $typeObj->setType($type);
	    $typeObj->setTitle($postTitle);
	    $typeObj->setSavedData($options);

	    return $typeObj;
    }

	public static function isActivePost($postId) {
		$enabled = !get_post_meta($postId, 'YRN_enable', true);
		$postStatus = get_post_status($postId);

		return ($enabled && $postStatus == 'publish');
	}

    public function boolToChecked($var) {
        return ($var ? 'checked' : '');
    }

    public static function getNumbersObj($agrs = array()) {
    	$postStatus = array('publish');
		$popups = array();

    	if (!empty($agrs['postStatus'])) {
		    $postStatus = $agrs['postStatus'];
	    }

	    $posts = get_posts(array(
		    'post_type' => YRN_POST_TYPE,
		    'post_status' => $postStatus,
		    'numberposts' => -1
		    // 'order'    => 'ASC'
	    ));

    	if(empty($posts)) {
    		return $popups;
	    }

	    foreach ($posts as $post) {
    		$popupObj = self::find($post->ID);

    		if(empty($popupObj)) {
    			continue;
		    }
		    $popups[] = $popupObj;
	    }

	    return $popups;
    }

    public static function shapeIdTitleData($numbers = array()) {
    	$idTitle = array();

    	if(empty($numbers)) {
    		$numbers = self::getNumbersObj();
	    }

	    foreach ($numbers as $popup) {
    		$title = $popup->getTitle();

    		if(empty($title)) {
			    $title = __('(no title)', YRN_TEXT_DOMAIN);
		    }

    		$idTitle[$popup->getId()] = $title .' - '. $popup->getTypeTitle();
	    }

	    return $idTitle;
    }

	/**
	 * Changing default options form changing options by name
	 *
	 * @since 1.0.0
	 *
	 * @param array $defaultOptions
	 * @param array $changingOptions
	 *
	 * @return array $defaultOptions
	 */
	public function changeDefaultOptionsByNames($defaultOptions, $changingOptions)
	{
		if (empty($defaultOptions) || empty($changingOptions)) {
			return $defaultOptions;
		}
		$changingOptionsNames = array_keys($changingOptions);
		
		foreach ($defaultOptions as $key => $defaultOption) {
			$defaultOptionName = $defaultOption['name'];
			if (in_array($defaultOptionName, $changingOptionsNames)) {
				$defaultOptions[$key] = $changingOptions[$defaultOptionName];
			}
		}
		
		return $defaultOptions;
	}
	
	/**
	 * Returns separate scroll types Free or Pro
	 *
	 * @since 2.5.6
	 *
	 * @return array $scrollTypesObj
	 */
	public static function getScrollTypes()
	{
		global $YRN_TYPES;
		$scrollTypesObj = array();
		$scrollTypes = $YRN_TYPES['typeName'];
		
		foreach ($scrollTypes as $popupType => $level) {
			
			if (empty($level)) {
				$level = SGPB_POPUP_PKG_FREE;
			}
			
			$scrollTypeObj = new ScrollType();
			$scrollTypeObj->setName($popupType);
			$scrollTypeObj->setAccessLevel($level);
			
			if (YRN_PKG_VERSION >= $level) {
				$scrollTypeObj->setAvailable(true);
			}
			$scrollTypesObj[] = $scrollTypeObj;
		}
		
		return $scrollTypesObj;
	}
	
	private function includeGeneralStyles() {
		ScriptsIncluder::registerStyle('bootstrap.css');
		ScriptsIncluder::enqueueStyle('bootstrap.css');
	}
	
	public function render() {
		$this->includeGeneralStyles();
		$content = $this->getViewContent();
		$content = apply_filters('yrnRenderContent', $content, $this);
		return $content;
	}

	private static function safeSize($size) {
		if (strpos($size, 'px')) {
			$size = (int)$size.'px';
		}
		if (strpos($size, '%')) {
			$size = (int)$size.'%';
		}
		else {
			$size = (int)$size.'px';
		}

		return $size;
	}
	
	public static function renderStyles($typeObj) {
		$id = $typeObj->getId();
		$numbersMarginTop = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-top'));
		$numbersMarginRight = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-right'));
		$numbersMarginBottom = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-bottom'));
		$numbersMarginLeft = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-left'));

		$labelMarginTop = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-top'));
		$labelMarginRight = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-right'));
		$labelMarginBottom = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-bottom'));
		$labelMarginLeft = self::safeSize($typeObj->getOPtionValue('yrn-numbers-margin-left'));

		$style = '<style>';
		if ($typeObj->getOPtionValue('yrn-numbers-font-size') != 'default') {
			$style .= '.yrn-options-wrapper-'.$id.' {font-size: '.$typeObj->getOPtionValue('yrn-numbers-font-size').'}';
		}
		$style .= '.yrn-options-wrapper-'.$id.' .yrn-number-wrapper {margin: '.$numbersMarginTop.' '.$numbersMarginRight.' '.$numbersMarginBottom.' '.$numbersMarginLeft.' }';
		$style .= '.yrn-options-wrapper-'.$id.' .yrn-label-wrapper {margin: '.$labelMarginTop.' '.$labelMarginRight.' '.$labelMarginBottom.' '.$labelMarginLeft.' }';
		if ($typeObj->getOPtionValue('yrn-label-font-size') != 'default') {
			$style .= '.yrn-options-wrapper-'.$id.' {font-size: '.$typeObj->getOPtionValue('yrn-label-font-size').'}';
		}
		$style .= '</style>';
		
		return $style;
	}
}