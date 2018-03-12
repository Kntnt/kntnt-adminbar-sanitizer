<?php

namespace Kntnt\Admin_Bar_Sanitizer;

defined('WPINC') || die;

require_once __DIR__ . '/class-admin-bar-container.php';
require_once __DIR__ . '/class-admin-bar-item.php';

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

    // N.B: The global object $wp_admin_bar is only available in
    // the 'admin_bar_menu' and 'wp_before_admin_bar_render' hooks.
    global $wp_admin_bar;

    // Add menu items.
    echo '<style>' . PHP_EOL;
    $this->_before_admin_bar_render_action($wp_admin_bar);
    echo '</style>' . PHP_EOL;

  }

  public function before_admin_bar_render_action() {

    // N.B: The global object $wp_admin_bar is only available in
    // the 'admin_bar_menu' and 'wp_before_admin_bar_render' hooks.
    global $wp_admin_bar;

    // Remove menu items.
    foreach ($this->trash as $id) {
      $wp_admin_bar->remove_node($id);
    }

  }

}
