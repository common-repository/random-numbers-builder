<?php
namespace yrn;

class Actions {
	public $customPostTypeObj;

	public function __construct() {
		$this->init();
	}

	public function init() {
		add_action('init', array($this, 'postTypeInit'));
		add_action('admin_menu', array($this, 'addSubMenu'));
		add_action('add_meta_boxes', array($this, 'addMetaboxes'), 10, 2);
		add_action('save_post_'.YRN_POST_TYPE, array($this, 'save'), 10, 3);
		Shortcode::getInstance();
		add_action('admin_head', array($this, 'adminHead'));
		add_action('admin_action_yrn_duplicate_post_as_draft', array($this, 'duplicatePostSave'));
		add_action('yrnRenderContent', array($this, 'renderContent'), 1, 2);
	}
	
	public function renderContent($content, $typeObj) {
		$styles = Numbers::renderStyles($typeObj);
		$content .= $styles;
		
		return $content;
	}
	
	public function duplicatePostSave() {
		
		global $wpdb;
		if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
			wp_die('No post to duplicate has been supplied!');
		}
		/*
		 * Nonce verification
		 */
		if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], YRN_POST_TYPE ) )
			return;
		
		/*
		 * get the original post id
		 */
		$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		/*
		 * and all the original post data then
		 */
		$post = get_post( $post_id );
		
		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;
		
		/*
		 * if post data exists, create the post duplicate
		 */
		if (isset( $post ) && $post != null) {
			
			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'publish',
				'post_title'     => $post->post_title.'(clone)',
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);
			
			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );
			
			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}
			
			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if (count($post_meta_infos)!=0) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					if( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query($sql_query);
			}
			
			
			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_redirect(admin_url('edit.php?post_type=' . YCD_COUNTDOWN_POST_TYPE));
			exit;
		} else {
			wp_die('Post creation failed, could not find original post: ' . $post_id);
		}
	}
	
	public function wpEnqueueScript() {
		$include = new IncludeToPage();
		$include->includeData();
	}
	
	public function adminHead() {
		echo "<script>jQuery(document).ready(function() {jQuery('a[href*=\"page=yrn-support-page\"]').css({'color': 'yellow'});jQuery('a[href*=\"page=yrn-support-page\"]').bind('click', function(e) {e.preventDefault(); window.open('https://wordpress.org/support/plugin/random-numbers-builder/')}) });</script>";
		echo "<script>jQuery(document).ready(function() {jQuery('a[href*=\"page=yrn-ideas-page\"]').css({'color': 'rgb(85, 239, 195)', 'font-size': '17px'});jQuery('a[href*=\"page=yrn-ideas-page\"]').bind('click', function(e) {e.preventDefault(); window.open('https://wordpress.org/support/plugin/random-numbers-builder/')}) });</script>";
	}
	
	public function hideMetaboxes($hidden, $screen) {
	
		if (('post' == $screen->base) && (YRN_POST_TYPE == $screen->id)) {
			$hidden[] ='hiddenMetaboxes';//for custom meta box, enter the id used in the add_meta_box() function.
		}
		
		return $hidden;
	}
	
	public function save($postId, $post, $update) {
		if(!$update) {
			return false;
		}
		$safePost = filter_input_array(INPUT_POST);
		$postData = Numbers::parseScrollDataFromData($safePost);
		$postData = apply_filters('YRNSavedData', $postData);
		if(empty($postData)) {
			return false;
		}
		$postData['yrn-post-id'] = $postId;
		
		if (!empty($postData['yrn-type'])) {
			$type = $postData['yrn-type'];
			$typePath = Numbers::getTypePathFormScrollType($type);
			$className = Numbers::getClassNameScrollType($type);
			
			require_once($typePath.$className.'.php');
			$className = __NAMESPACE__.'\\'.$className;
			
			$className::create($postData);
		}
		return true;
	}
	
	public function defaultMainMetaboxes() {
		$metaboxes = array();
		
		$metaboxes['hiddenMetaboxes'] = array('title' => 'hidden options', 'position' => 'normal', 'prioritet' => 'high');
		$metaboxes['generalMetaboxes'] = array('title' => 'General options', 'position' => 'normal', 'prioritet' => 'high');
		$metaboxes['yrnShortcode'] = array('title' => 'Info', 'position' => 'side', 'prioritet' => 'high');
		$metaboxes['yrnSupport'] = array('title' => 'Support', 'position' => 'side', 'prioritet' => 'high');
		$metaboxes['yrnSupport'] = array('title' => 'Support', 'position' => 'side', 'prioritet' => 'high');
		$metaboxes['yrnOptions'] = array('title' => 'Options', 'position' => 'normal', 'prioritet' => 'high');
		
		return $metaboxes;
	}
	
	public function getMetaBoxes() {
		$metaboxes = $this->defaultMainMetaboxes();
		
		return apply_filters('YRNGeneralMetaboxes', $metaboxes);
	}
	
	public function addMetaboxes() {
		$metaboxes = $this->getMetaBoxes();
		
		foreach ($metaboxes as $key => $metabox) {
			$obj = $this;
			if(isset($metabox['currentObj'])) {
				$obj = $metabox['currentObj'];
			}
			add_meta_box($key, __($metabox['title'], YRN_TEXT_DOMAIN), array($obj, $key), YRN_POST_TYPE, $metabox['position'], $metabox['prioritet']);
		}
	}
	
	public function yrnOptions() {
		$typeObj = $this->customPostTypeObj->getTypeObj();
		require_once YRN_VIEWS_PATH.'options.php';
	}
	
	public function hiddenMetaboxes() {
		$typeObj = $this->customPostTypeObj->getTypeObj();
		require_once YRN_VIEWS_PATH.'hiddenOptions.php';
	}
	
	public function generalMetaboxes() {
		$typeObj = $this->customPostTypeObj->getTypeObj();
		require_once YRN_VIEWS_PATH.'generalOptions.php';
	}
	
	public function yrnSupport() {
		require_once YRN_VIEWS_METABOXES_PATH.'support.php';
	}
	
	public function yrnShortcode() {
		$typeObj = $this->customPostTypeObj->getTypeObj();
		require_once YRN_VIEWS_METABOXES_PATH.'shortcode.php';
	}

	public function postTypeInit()
	{
		$this->customPostTypeObj = new RegisterPostType();
		add_filter('default_hidden_meta_boxes', array($this, 'hideMetaboxes'),10,2);
	}
	
	public function addSubMenu() {
		$this->customPostTypeObj->addSubMenu();
	}
}
