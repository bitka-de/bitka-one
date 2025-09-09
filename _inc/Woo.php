<?php

namespace Bitka;

// use WooShipping; // Removed or update this line if WooShipping is in a different namespace
use Bitka\WooShipping; // Update this line if WooShipping is in the Bitka namespace

class Woo
{
    public function __construct()
    {

    }


    /**
     * Shortcode handler for [bitka_categories]
     *
     * @param array $atts
     * @return string
     */
    public static function bitka_categories_shortcode(array $atts = []): string
    {
        $atts = shortcode_atts([
            'hide_empty' => 'true', // 'true' or 'false'
            'exclude'    => 'Unkategorisiert,uncategorized', // Kommagetrennte Liste von Kategorie-Namen
            'title'      => 'Shop Kategorien', // Überschrift
        ], $atts, 'bitka_categories');

        $hideEmpty = $atts['hide_empty'] === 'true';
        $excludeNames = array_filter(array_map('trim', explode(',', $atts['exclude'])));

        $args = [
            'taxonomy'   => 'product_cat',
            'hide_empty' => $hideEmpty,
        ];

        $categories = get_terms($args);
        if (empty($categories) || is_wp_error($categories)) {
            return '<p>' . __('Keine Kategorien gefunden.', 'bitka-one') . '</p>';
        }

        if (!empty($excludeNames)) {
            $categories = array_filter($categories, fn($cat) => !in_array($cat->name, $excludeNames, true));
        }

        // View auslagern
        ob_start();
        $title = $atts['title'];
        // $categories ist bereits gesetzt
        $template = locate_template('templates/woo/bitka-categories-shortcode.php');
        if ($template) {
            extract(['categories' => $categories, 'title' => $title]);
            include $template;
        } else {
            echo '<p>Template nicht gefunden.</p>';
        }
        return ob_get_clean();
    }


}