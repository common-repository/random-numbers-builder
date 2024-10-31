<?php
namespace yrn;
use \WP_Query;

class IncludeToPage {
	private $posts = array();
	
	public function pushToPosts($post) {
		$this->posts[] = $post;
	}
	
	public function getPosts() {
		return $this->posts;
	}
	
	public function includeData() {
		var_dump(33);die;
		$this->getSavedData();
		$this->includeToPage();
	}
	
	private function getSavedData() {
		$posts = new WP_Query(
			array(
				'post_type'      => YRN_POST_TYPE,
				'posts_per_page' => - 1
			)
		);
		// We check all the popups one by one to realize whether they might be loaded or not.
		while($posts->have_posts()) {
			$posts->next_post();
			$currentPost = $posts->post;
			$id = $currentPost->ID;
			$scroll = Numbers::find($id);
			$this->pushToPosts($scroll);
		}
	}
	
	public function includeToPage() {
		$scrolls = $this->getPosts();
		
		if(empty($scrolls)) {
			return false;
		}
		
		foreach($scrolls as $scroll) {
			$this->addToFooter($scroll);
		}
		$this->includeScripts();
		
		
		
	}
	
	public function addToFooter($scroll) {
		$id = $scroll->getId();
		$options = $scroll->getSavedData();
		$options = json_encode($options, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
		$content = $scroll->getViewContent();
		$styles = $scroll->getCurrentStyles();
		
		add_action('wp_footer', function() use ($id, $options, $content, $styles) {
			$footerPopupContent = '<div style="position:relative; bottom: -999999999999999999999px;">
							<div class="YRN-content" id="YRN-content-wrapper-'.$id.'" data-styles="'.esc_attr($styles).'" data-id="'.esc_attr($id).'" data-options="'.esc_attr($options).'">
								<div class="YRN-content-'.esc_attr($id).' YRN-content-html">'.$content.'</div>
							</div>
						  </div>';
			
			echo $footerPopupContent;
		});
	}
	
	public function includeScripts() {
		ScriptsIncluder::registerScript('YrnCountUp.js');
		ScriptsIncluder::enqueueScript('YrnCountUp.js');
	}
}