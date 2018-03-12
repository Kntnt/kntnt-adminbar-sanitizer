# Kntnt's adminbar sanitizer

WordPress plugin that sanitizes admin bar from junk and provides enhancements.

## Description

You can use this plugin to reomve or add any ites to the admin bar.

Out of the box, this plugns adds a toggle button, so you can switch between frontend and admin, and removes following buttons

* WordPress (menu with About, WordPress.org, Documentation, Support and Feedback)
* Site menu (menu with Visit site alternative Panel, Theme, Widgets and Menus)
* Customize

You can add and delete items from the list of items to be removed by implementing the `kntnt_admin_bar_sanitizer_remove_list`-filter:

```php
add_filter('kntnt_admin_bar_sanitizer_remove_list', function($remove_list) {
  /* Here you can add and delete items from the $remove_list array
   *
   *   $remove_list[] = 'my-account';
   *
   */
  return $remove_list;
});
```

You can add and delete items from the list of items to be added on the frontend by implementing the `kntnt_admin_bar_sanitizer_add_front_list`-filter:

```php
add_filter('kntnt_admin_bar_sanitizer_add_front_list', function($add_list) {
  /* Here you can add and delete items from the $add_list array.
   *
   *   $add_list['kntnt'] = [
   *     'title' => 'Kntnt',
   *     'link' => 'https://www.kntnt.com/',
   *     'icon' => '\f102',       // OPTIONAL
   *     'icon_top_margin' => 0,  // OPTIONAL
   *   ],
   *
  */
  return $add_list;
});
```

You can add and delete items from the list of items to be added on the backend by implementing the `kntnt_admin_bar_sanitizer_add_admin_list`-filter:

```php
add_filter('kntnt_admin_bar_sanitizer_add_admin_list', function($add_list) {
  /* Here you can add and delete items from the $add_list array.
   *
   *   $add_list['kntnt'] = [
   *     'title' => 'Kntnt',
   *     'link' => 'https://www.kntnt.com/',
   *     'icon' => '\f102',       // OPTIONAL
   *     'icon_top_margin' => 0,  // OPTIONAL
   *   ],
   *
  */
  return $add_list;
});
```
