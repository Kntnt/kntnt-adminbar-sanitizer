<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Kntnt Adminbar Sanitizer
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Sanitizes adminbar from junk and provides enhancements.
 * Version:           2.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Kntnt\Adminbar_Sanitizer;

defined('ABSPATH') && new Plugin;

class Plugin {

    public function __construct() {
        add_action('init', [$this, 'run']);
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
        ]);

        if (is_admin()) {

            $add_list = apply_filters('kntnt_admin_bar_sanitizer_add_admin_list', [
                'home' => [
                    'title' => get_option('blogname'),
                    'link' => get_home_url() . '/',
                    'icon' => '\f102',
                    'icon_top_margin' => 0,
                ],
            ]);

        } else {

            $add_list = apply_filters('kntnt_admin_bar_sanitizer_add_front_list', [
                'home' => [
                    'title' => get_option('blogname'),
                    'link' => get_home_url() . '/wp-admin/',
                    'icon' => '\f226',
                    'icon_top_margin' => 0,
                ],
            ]);

        }

        $ab = new Admin_Bar();

        foreach ($remove_list as $item) {
            $ab->remove($item);
        }

        foreach ($add_list as $item => $args) {

            if (!isset($args['icon'])) {
                $args['icon'] = '';
            }
            if (!isset($args['icon_top_margin'])) {
                $args['icon_top_margin'] = 2;
            }

            $ab->add($item, $args['title'], $args['link'], $args['icon'], $args['icon_top_margin']);

        }

    }

}

abstract class Admin_Bar_Container {

    protected $menu;

    protected $icon;

    private $items = [];

    protected function __construct($parent = '', $id = '', $title = '', $href = '', $icon = '', $icon_top_margin = 2) {

        if ($icon) {
            $title = $this->icon_html($title);
            $this->icon = $this->icon_css($id, $icon, $icon_top_margin);
        };

        $this->menu = [
            'id' => $id,
            'title' => $title,
            'parent' => $parent,
            'href' => $href,
        ];

    }

    protected function _add($parent, $id, $title, $href, $icon, $icon_top_margin) {
        $item = new Admin_Bar_Item($parent, $id, $title, $href, $icon, $icon_top_margin);
        $this->items[$id] = $item;
        return $item;
    }

    protected function _before_admin_bar_render_action($wp_admin_bar) {
        foreach ($this->items as $item) {
            $wp_admin_bar->add_node($item->menu);
            if ($item->icon) {
                echo $item->icon . PHP_EOL;
            }
            $item->_before_admin_bar_render_action($wp_admin_bar);
        }
    }

    protected function icon_html($title) {
        return '<span class="ab-icon"></span>' . $title;
    }

    protected function icon_css($id, $icon, $icon_top_margin) {
        return <<<CSS
      #wp-admin-bar-$id {
        display: inline-block
      }
      #wp-admin-bar-$id .ab-icon::before {
        content: '$icon';
        margin-top: {$icon_top_margin}px
      }
CSS;
    }

}

final class Admin_Bar extends Admin_Bar_Container {

    private $trash = [];

    public function __construct() {
        add_action('add_admin_bar_menus', [$this, 'add_admin_bar_menus_action']);
        add_action('wp_before_admin_bar_render', [$this, 'before_admin_bar_render_action']);
    }

    public function add($id, $title, $href, $icon = '', $icon_top_margin = 2) {
        return $this->_add('', $id, $title, $href, $icon, $icon_top_margin);
    }

    public function remove($id) {
        $this->trash[] = $id;
    }

    public function add_admin_bar_menus_action() {
        $position = 10;
        add_action('admin_bar_menu', [$this, 'admin_bar_menu_action'], $position);
    }

    public function admin_bar_menu_action() {

        // N.B: The global object $wp_admin_bar is only available in
        // the 'admin_bar_menu' and 'wp_before_admin_bar_render' hooks.
        global $wp_admin_bar;

        // Add menu items.
        echo '<style>' . PHP_EOL;
        $this->_before_admin_bar_render_action($wp_admin_bar);
        echo '</style>' . PHP_EOL;

    }

    public function before_admin_bar_render_action() {

        // N.B: The global object $wp_admin_bar is only available in
        // the 'admin_bar_menu' and 'wp_before_admin_bar_render' hooks.
        global $wp_admin_bar;

        // Remove menu items.
        foreach ($this->trash as $id) {
            $wp_admin_bar->remove_node($id);
        }

    }

}

class Admin_Bar_Item extends Admin_Bar_Container {

    public function add($id, $title, $href, $icon = '', $icon_top_margin = 2) {
        return $this->_add($this->menu['id'], $id, $title, $href, $icon, $icon_top_margin);
    }

}
