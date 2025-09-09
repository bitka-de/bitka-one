<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bitka\Core\Theme;

new Theme();


/**
 * WooCommerce eigene Anpassungen (Beispiel)
 */
function bitka_theme_woocommerce_setup()
{
    // Entferne WooCommerce Styles, wenn du nur eigene Tailwind/CSS willst
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
}
add_action('after_setup_theme', 'bitka_theme_woocommerce_setup');


// Header Menü registrieren
function bitka_register_menus()
{
    register_nav_menus([
        'header-menu' => __('Header Menü', 'bitka-one'),
    ]);
}
add_action('after_setup_theme', 'bitka_register_menus');


#
function get_menu($location)
{
    $menu_items = [];
    $locations = get_nav_menu_locations();
    if (isset($locations[$location])) {
        $menu = wp_get_nav_menu_items($locations[$location]);
        if ($menu) {
            foreach ($menu as $item) {
                $is_active = (get_permalink($item->object_id) === home_url(add_query_arg([], $_SERVER['REQUEST_URI'])));
                $menu_items[] = [
                    'name' => $item->title,
                    'link' => $item->url,
                    'active' => $is_active,
                ];
            }
        }
    }
    return $menu_items;
}

add_action('init', function () {
    register_block_type(get_stylesheet_directory() . '/blocks/hero');
});



function bitka_get_breadcrumbs()
{
    global $post;
    $breadcrumbs = [];

    // Startseite
    $breadcrumbs[] = [
        'title' => __('Home', 'bitka-one'),
        'url'   => home_url('/'),
    ];

    if (is_home() || is_front_page()) {
        // Nur Startseite
        return $breadcrumbs;
    }

    if (is_category() || is_single()) {
        $cat = get_the_category();
        if (!empty($cat)) {
            $category = $cat[0];
            $ancestors = get_ancestors($category->term_id, 'category');
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_category($ancestor_id);
                $breadcrumbs[] = [
                    'title' => $ancestor->name,
                    'url'   => get_category_link($ancestor_id),
                ];
            }
            $breadcrumbs[] = [
                'title' => $category->name,
                'url'   => get_category_link($category->term_id),
            ];
        }
        if (is_single()) {
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url'   => get_permalink(),
            ];
        }
    } elseif (is_page()) {
        $ancestors = get_post_ancestors($post);
        $ancestors = array_reverse($ancestors);
        foreach ($ancestors as $ancestor_id) {
            $ancestor = get_post($ancestor_id);
            $breadcrumbs[] = [
                'title' => get_the_title($ancestor_id),
                'url'   => get_permalink($ancestor_id),
            ];
        }
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => get_permalink(),
        ];
    } elseif (is_archive()) {
        $breadcrumbs[] = [
            'title' => get_the_archive_title(),
            'url'   => '',
        ];
    } elseif (is_search()) {
        $breadcrumbs[] = [
            'title' => sprintf(__('Search results for "%s"', 'bitka-one'), get_search_query()),
            'url'   => '',
        ];
    } elseif (is_404()) {
        $breadcrumbs[] = [
            'title' => __('404 Not Found', 'bitka-one'),
            'url'   => '',
        ];
    }

    return $breadcrumbs;
}





function admin_tpl_part(string $part = '', array $data = []): string
{
    $path = get_template_directory() . '/templates/admin/parts/' . $part;
    if (!is_file($path)) {
        return '<div>Template part not found</div>';
    }

    if (!empty($data)) {
        extract($data, EXTR_SKIP);
    }

    ob_start();
    include $path;
    return ob_get_clean();
}


function _echo($content, $type = 'html')
{
    if (!function_exists('esc_html')) {
        require_once(ABSPATH . 'wp-includes/formatting.php');
    }
    if (!empty($content) && $type === 'html') {
        return esc_html($content);
    }

    if (!empty($content) && $type === 'url') {
        return esc_url($content);
    }

    return '<mark>no Info</mark>';
}
