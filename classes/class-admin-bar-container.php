<?php

namespace Kntnt\Admin_Bar_Sanitizer;

defined('WPINC') || die;

abstract class Admin_Bar_Container {

  protected $menu;

  protected $icon;

  private $items = [];

  protected function __construct($parent = '', $id = '', $title = '', $href = '', $icon = '', $icon_top_margin = 2) {

    if ($icon) {
      $title = $this->icon_html($title);
      $this->icon = $this->icon_css($id, $icon, $icon_top_margin);
    };

    $this->menu = array(
      'id' => $id,
      'title' => $title,
      'parent' => $parent,
      'href' => $href,
    );

  }

  protected function _add($parent, $id, $title, $href, $icon, $icon_top_margin) {
    $item = new Admin_Bar_Item($parent, $id, $title, $href, $icon, $icon_top_margin);
    $this->items[$id] = $item;
    return $item;
  }

  protected function _before_admin_bar_render_action($wp_admin_bar) {
    foreach($this->items as $item) {
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
