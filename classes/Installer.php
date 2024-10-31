<?php
namespace yrn;

class Installer {

	public static function uninstall() {

		if (!get_option('YRN-delete-data')) {
			return false;
		}

		self::deleteScrolls();
		return true;
	}

	/**
	 * Delete all Scroll post types posts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 */
	private static function deleteScrolls()
	{
		$scrolls = get_posts(
			array(
				'post_type' => YRN_POST_TYPE,
				'post_status' => array(
					'publish',
					'pending',
					'draft',
					'auto-draft',
					'future',
					'private',
					'inherit',
					'trash'
				)
			)
		);

		foreach ($scrolls as $scroll) {
			if (empty($scroll)) {
				continue;
			}
			wp_delete_post($scroll->ID, true);
		}
	}

	public static function install() {
		self::createTables();

		if(is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;

			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}

			foreach($sites as $site) {

				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id.'_';
				}
				else {
					$blogId = $site['blog_id'].'_';
				}
				if($blogId != 1) {
					self::createTables($blogId);
				}
			}
		}
	}

	public static function createTables($blogId = '') {
	
	}
}