<?php

namespace Bitka;

class Theme
{
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'assets']);
        add_action('init', [$this, 'shortcodes']);
        add_action('after_setup_theme', [$this, 'custom_logo']);
        add_filter('upload_mimes', [$this, 'allow_svg_uploads']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        // Ajax-Handler für Galerie-Bild
        add_action('wp_ajax_bitka_get_gallery_image', [$this, 'ajax_get_gallery_image']);
        add_action('wp_ajax_nopriv_bitka_get_gallery_image', [$this, 'ajax_get_gallery_image']);
    }

    /**
     * Allow SVG file uploads in WordPress media library
     *
     * @param array $mimes
     * @return array
     */
    public function allow_svg_uploads(array $mimes): array
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function setup()
    {
        add_theme_support('woocommerce');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', ['search-form', 'gallery', 'caption']);
        add_theme_support('title-tag');
    }

    function assets()
    {
        wp_enqueue_style('bitka-theme-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
        wp_enqueue_style('bitka-theme-main-css', get_template_directory_uri() . '/assets/css/main.css', [], wp_get_theme()->get('Version'));
        wp_enqueue_script('bitka-theme-js', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], wp_get_theme()->get('Version'), true);
    }

    function shortcodes()
    {
        # Shortcode für Produktkategorien
        add_shortcode('bitka_woo_categories', ['Woo', 'bitka_categories_shortcode']);
    }


    function custom_logo()
    {
        $defaults = array(
            'height'               => 100,
            'width'                => 400,
            'flex-height'          => true,
            'flex-width'           => true,
            'header-text'          => array('site-title', 'site-description'),
            'unlink-homepage-logo' => true,
        );
        add_theme_support('custom-logo', $defaults);
    }

    /**
     * Lädt Admin-spezifische Styles
     */
    public function admin_assets()
    {
        wp_enqueue_style('bitka-theme-admin-css', get_template_directory_uri() . '/assets/css/admin.css', [], wp_get_theme()->get('Version'));
    }

    /**
     * Ajax-Handler: Gibt die URL eines Galerie-Bildes zurück
     */
    public function ajax_get_gallery_image()
    {
        $image_id = isset($_GET['image_id']) ? intval($_GET['image_id']) : 0;
        if (!$image_id) {
            wp_send_json_error('No image_id');
        }
        $url = wp_get_attachment_url($image_id);
        if (!$url) {
            wp_send_json_error('Image not found');
        }
        wp_send_json_success(['url' => esc_url_raw($url)]);
    }


}
