<?php

namespace Kntnt\Admin_Bar_Sanitizer;

defined('WPINC') || die;

require_once __DIR__ . '/class-admin-bar-container.php';

class Admin_Bar_Item extends Admin_Bar_Container {

  public function add($id, $title, $href, $icon = '', $icon_top_margin = 2) {
    return $this->_add($this->menu['id'], $id, $title, $href, $icon, $icon_top_margin);
  }

}
