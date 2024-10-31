<?php
namespace yrn;

class AdminHelper {

    public static function buildCreateCountdownUrl($type) {
        $isAvailable = $type->isAvailable();
        $name = $type->getName();

        $url = YRN_ADMIN_URL.'post-new.php?post_type='.YRN_POST_TYPE.'&YRN_type='.$name;

        if (!$isAvailable) {
            $url = YRN_PRO_URL;
        }

        return $url;
    }

    public static function buildCreateScrollAttrs($type) {
    	$attrStr = '';
	    $isAvailable = $type->isAvailable();

	    if (!$isAvailable) {
	    	$args = array(
				'target' => '_blank'
		    );
		    $attrStr = self::createAttrs($args);
	    }

	    return $attrStr;
    }

    public static function getScrollThumbClass($type) {
        $isAvailable = $type->isAvailable();
        $name = $type->getName();

        $typeClassName = $name.'-scroll';

        if (!$isAvailable) {
            $typeClassName .= '-pro YRN-pro-version';
        }

        return $typeClassName;
    }
    
    public static function defaultData() {

        $data = array();
	
	    $data['showAfter'] = array(
	        'px' => __('px', YRN_TEXT_DOMAIN),
	        '%' => __('%', YRN_TEXT_DOMAIN)
	    );
	    
	    $data['fontSize'] = array(
	        'default' => __('Default', YRN_TEXT_DOMAIN)
	    );
	    
	    for ($i = 0; $i <= 100; $i++) {
		    $data['fontSize'][$i.'px'] = $i.'px';
	    }
	    
	    return apply_filters('YRNDefaults', $data);
    }
    

    public static function selectBox($data, $selectedValue, $attrs) {

        $attrString = '';
        $selected = '';

        if(!empty($attrs) && isset($attrs)) {

            foreach ($attrs as $attrName => $attrValue) {
                $attrString .= ''.$attrName.'="'.$attrValue.'" ';
            }
        }

        $selectBox = '<select '.$attrString.'>';

        foreach ($data as $value => $label) {

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

	/**
	 * Create html attrs
	 *
	 * @since 1.0.9
	 *
	 * @param array $attrs
	 *
	 * @return string $attrStr
	 */
	public static function createAttrs($attrs)
	{
		$attrStr = '';

		if (empty($attrs)) {
			return $attrStr;
		}

		foreach ($attrs as $attrKey => $attrValue) {
			$attrStr .= $attrKey.'="'.$attrValue.'" ';
		}

		return $attrStr;
	}
	
	public static function createStyleAttrs($attrs)
	{
		$attrStr = '';
		
		if (empty($attrs)) {
			return $attrStr;
		}
		
		foreach ($attrs as $attrKey => $attrValue) {
			$attrStr .= $attrKey.": $attrValue; ";
		}
		
		return $attrStr;
	}

	/**
	 * Create Radio buttons
	 *
	 * @since 1.0.9
	 *
	 * @param array $data
	 * @param string $savedValue
	 * @param array $attrs
	 *
	 * @return string
	 */
	public static function createRadioButtons($data, $savedValue, $attrs = array()) {

		$attrString = '';
		$selected = '';

		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$radioButtons = '';

		foreach($data as $value) {

			$checked = '';
			if($value == $savedValue) {
				$checked = 'checked';
			}

			$radioButtons .= "<input type=\"radio\" value=\"$value\" $attrString  $checked>";
		}

		return $radioButtons;
	}

	public static function getCurrentPostType() {
		global $post, $typenow, $current_screen;
		
		//we have a post so we can just get the post type from that
		if ($post && $post->post_type) {
			return $post->post_type;
		}
		//check the global $typenow - set in admin.php
		elseif($typenow) {
			return $typenow;
		}
		//check the global $current_screen object - set in sceen.php
		elseif($current_screen && $current_screen->post_type) {
			return $current_screen->post_type;
		}
		//lastly check the post_type querystring
		elseif(isset($_REQUEST['post_type'])) {
			return sanitize_key($_REQUEST['post_type']);
		}
		
		//we do not know the post type!
		return null;
	}
}