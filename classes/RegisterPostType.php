<?php
namespace yrn;
use \YrnOptionsConfig;

class RegisterPostType {

    private $typeObj;
    private $type;
    private $id;
	
    public function __construct() {
        $this->init();
	    
	    return true;
    }

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

    public function setTypeObj($typeObj) {
        $this->typeObj = $typeObj;
    }

    public function getTypeObj() {
        return $this->typeObj;
    }

    public function init() {
        $postType = YRN_POST_TYPE;
	    add_filter('YRNPostTypeSupport', array($this, 'postTypeSupports'), 10, 1);
        $args = $this->getPostTypeArgs();

        register_post_type($postType, $args);

        if(@$_GET['post_type'] || get_post_type(@$_GET['post']) == YRN_POST_TYPE) {
            $this->createCdObjFromCdType();
        }
	    YrnOptionsConfig::optionsValues();
    }

	public function postTypeSupports($supports) {

		$id = $this->getId();
		$type = $this->getTypeName();
		$typePath = Numbers::getTypePathFormScrollType($type);
		$className = Numbers::getClassNameScrollType($type);

		if (!file_exists($typePath.$className.'.php')) {
			return $supports;
		}
		
		require_once $typePath.$className.'.php';
		$className = __NAMESPACE__.'\\'.$className;
		if (!class_exists($className)) {
			return $supports;
		}

		if (method_exists($className, 'getTypeSupports')) {
			$supports = $className::getTypeSupports();
		}

		return $supports;
	}

    private function createCdObjFromCdType() {
        $id = 0;

        if (!empty($_GET['post'])) {
            $id = (int)$_GET['post'];
        }

        $type = $this->getTypeName();
        $this->setType($type);
        $this->setId($id);

        $this->createCdObj();
    }

    public function createCdObj()
    {
        $id = $this->getId();
        $type = $this->getType();
        $typePath = Numbers::getTypePathFormScrollType($type);
        $className = Numbers::getClassNameScrollType($type);

        if (!file_exists($typePath.$className.'.php')) {
            wp_die(__($className.' class does not exist', YRN_TEXT_DOMAIN));
        }
        require_once($typePath.$className.'.php');
        $className = __NAMESPACE__.'\\'.$className;
	    if (!class_exists($className)) {
		    wp_die(__($className.' class does not exist', YRN_TEXT_DOMAIN));
	    }
	    $id = $this->getId();
	    
        $typeObj = new $className();
        $typeObj->setId($id);
        $this->setTypeObj($typeObj);
    }

    private function getTypeName() {
        $type = YRN_DEFAULT_TYPE;

        /*
         * First, we try to find the Scroll type with the post id then,
         * if the post id doesn't exist, we try to find it with $_GET['YRN_type']
         */
        if (!empty($_GET['post'])) {
            $id = (int)$_GET['post'];
            $cdOptionsData = Numbers::getPostSavedData($id);
            if (!empty($cdOptionsData['yrn-type'])) {
                $type = $cdOptionsData['yrn-type'];
            }
        }
        else if (!empty($_GET['yrn_type'])) {
            $type = sanitize_text_field($_GET['yrn_type']);
        }

        return $type;
    }

    public function getPostTypeArgs()
    {
        $labels = $this->getPostTypeLabels();

        $args = array(
            'labels'             => $labels,
            'description'        => __('Description.', 'your-plugin-textdomain'),
            //Exclude_from_search
            'public'             => true,
	        'has_archive'        => true,
            //Where to show the post type in the admin menu
            'show_ui'            => true,
            'query_var'          => false,
            // post preview button
            'publicly_queryable' => false,
	        'map_meta_cap'       => true,
            'capability_type'    => 'post',
            'menu_position'      => 10,
            'supports'           => apply_filters('YRNPostTypeSupport', array('title')),
            'menu_icon'          => 'dashicons-slides'
        );

        return $args;
    }

    public function getPostTypeLabels()
    {
        $labels = array(
            'name'               => _x('Random Numbers Builder', 'post type general name', YRN_TEXT_DOMAIN),
            'singular_name'      => _x('Random Numbers Builder', 'post type singular name', YRN_TEXT_DOMAIN),
            'menu_name'          => _x('Random Numbers Builder', 'admin menu', YRN_TEXT_DOMAIN),
            'name_admin_bar'     => _x('RandomNumbersBuilder', 'add new on admin bar', YRN_TEXT_DOMAIN),
            'add_new'            => _x('Add New', 'Random Numbers', YRN_TEXT_DOMAIN),
            'add_new_item'       => __('Add New Random Numbers', YRN_TEXT_DOMAIN),
            'new_item'           => __('New Random Numbers', YRN_TEXT_DOMAIN),
            'edit_item'          => __('Edit Random Numbers', YRN_TEXT_DOMAIN),
            'view_item'          => __('View Item', YRN_TEXT_DOMAIN),
            'all_items'          => __('All Items', YRN_TEXT_DOMAIN),
            'search_items'       => __('Search Scroll to Tops', YRN_TEXT_DOMAIN),
            'parent_item_colon'  => __('Parent Scroll to Top:', YRN_TEXT_DOMAIN),
            'not_found'          => __('No item found.', YRN_TEXT_DOMAIN),
            'not_found_in_trash' => __('No item found in Trash.', YRN_TEXT_DOMAIN)
        );

        return $labels;
    }

    public function addSubMenu() {
	    add_submenu_page(
		    'edit.php?post_type='.YRN_POST_TYPE,
		    __('Support', YRN_TEXT_DOMAIN),
		    __('Support', YRN_TEXT_DOMAIN),
		    'read',
		    'yrn-support-page',
		    array($this, 'supportPage')
	    );
	
	    add_submenu_page(
		    'edit.php?post_type='.YRN_POST_TYPE,
		    __('More Ideas?', YRN_TEXT_DOMAIN),
		    __('More Ideas?', YRN_TEXT_DOMAIN),
		    'read',
		    'yrn-ideas-page',
		    array($this, 'supportPage')
	    );
	
	    return ;
		 add_submenu_page(
            'edit.php?post_type='.YRN_POST_TYPE,
            __('Numbers Types', YRN_TEXT_DOMAIN), // page title
            __('Numbers Types', YRN_TEXT_DOMAIN), // menu title
            'ycd_manage_options', 
            YRN_POST_TYPE,
            array($this, 'postTypePage')
        );
    }
	
	public function postTypePage() {
		require_once YRN_VIEWS_PATH.'typesPage.php';
	}
	
	public function supportPage() {
    	
	}
}