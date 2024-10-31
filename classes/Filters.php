<?php
namespace yrn;

class Filters {

    public function __construct() {
        $this->init();
    }

    public function init() {
	    add_filter('post_updated_messages' , array($this, 'updatedMessages'), 10, 1);
	    add_action('manage_'.YRN_POST_TYPE.'_posts_custom_column' , array($this, 'tableColumnValues'), 10, 2);
	    add_filter('manage_'.YRN_POST_TYPE.'_posts_columns' , array($this, 'tableColumns'));
	    add_filter( 'post_row_actions', array($this, 'duplicatePost'), 10, 2 );
    }
	
	public function duplicatePost($actions, $post) {
		if (current_user_can('edit_posts') && $post->post_type == YRN_POST_TYPE) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=yrn_duplicate_post_as_draft&post=' . $post->ID, YRN_POST_TYPE, 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">'.__('Clone', YRN_TEXT_DOMAIN).'</a>';
		}
		return $actions;
	}
	
	public function tableColumns($columns) {
		unset($columns['date']);
		
		$additionalItems = array();
		$additionalItems['shortcode'] = __('Shortcode', YRN_TEXT_DOMAIN);
		
		return $columns + $additionalItems;
	}
	
	public function tableColumnValues($column, $postId) {
		
		if ($column == 'shortcode') {
			echo '<div class="yrn-tooltip"><span class="yrn-tooltiptext" id="yrn-tooltip-'.$postId.'">'. __('Copy to clipboard', YRN_TEXT_DOMAIN).'</span><input type="text" class="download-shortcode" id="yrn-shortcode-input-'.$postId.'" data-id="'.$postId.'" value="[yrn_numbers id='.$postId.']" readonly="" onfocus="this.select()"></div>';
			
		}
	}
    
    public function updatedMessages($messages) {
    	$currentPostType = AdminHelper::getCurrentPostType();
        if ($currentPostType != YRN_POST_TYPE) {
        	return $messages;
        }
	    $messages[YRN_POST_TYPE][1] = 'Scroll to Top updated.';
	    $messages[YRN_POST_TYPE][6] = 'Scroll to Top published.';
	    $messages[YRN_POST_TYPE][7] = 'Scroll to Top saved.';
     
	    return $messages;
	}

    public function addNewPostUrl($url, $path) {
        if ($path == 'post-new.php?post_type='.YRN_POST_TYPE) {
            $url = str_replace('post-new.php?post_type='.YRN_POST_TYPE, 'edit.php?post_type='.YRN_POST_TYPE.'&page='.YRN_POST_TYPE, $url);
        }

        return $url;
    }
}