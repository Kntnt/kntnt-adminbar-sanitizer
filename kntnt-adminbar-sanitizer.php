<?php

/**
 * Plugin main file.
 *
 * @wordpress-plugin
 * Plugin Name:       Kntnt's adminbar sanitizer
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Sanitizes adminbar from junk and provides enhancements.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */
 
namespace Kntnt\Admin_Bar_Sanitizer;

defined('WPINC') || die;

require_once __DIR__ . '/classes/class-admin-bar.php';

new Plugin();

final class Plugin {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'run' ] );
	}

	public function run() {
	
	  $remove_list = apply_filters('kntnt_admin_bar_sanitizer_remove_list', [
	    'wp-logo',
      'about',
      'wporg',
      'documentation',
      'support-forums',
      'feedback',
      'site-name',
      'customize',
	  ]);

    if(is_admin()) {

      $add_list = apply_filters('kntnt_admin_bar_sanitizer_add_admin_list', [
        'home' => [
          'title' => get_option('blogname'),
          'link' => get_home_url() . '/',
          'icon' => '\f102',
          'icon_top_margin' => 0,
        ],
      ]);

    }
    else {

      $add_list = apply_filters('kntnt_admin_bar_sanitizer_add_front_list', [
        'home' => [
          'title' => get_option('blogname'),
          'link' => get_home_url() . '/wp-admin',
          'icon' => '\f226',
          'icon_top_margin' => 0,
        ],
      ]);

    }

    $ab = new Admin_Bar();

    foreach($remove_list as $item) {
      $ab->remove($item);
    }
    
    foreach($add_list as $item => $args) {
      $this->default_values($args);
      $ab->add($item, $args['title'], $args['link'], $args['icon'], $args['icon_top_margin']);
    }

	}
	
	private function default_values(&$args) {
    if ( ! isset($args['icon'])) { $args['icon'] = ''; }
    if ( ! isset($args['icon_top_margin'])) { $args['icon_top_margin'] = 2; }
	}

}
