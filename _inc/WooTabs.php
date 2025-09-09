<?php

namespace Bitka;
/**
 * WooTabs – Admin-Seite für WooCommerce Tabs mit Gutenberg-Unterstützung
 */
class WooTabs
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_tabs_menu']);
        add_action('init', [$this, 'register_tabs_post_type']);
        add_action('add_meta_boxes', [$this, 'add_display_name_metabox']);
        add_action('save_post_woo_tab', [$this, 'save_display_name_metabox']);
        // Eigener Produktdaten-Tab für WooTabs
        add_filter('woocommerce_product_data_tabs', [$this, 'add_custom_tabs_tab']);
        add_action('woocommerce_product_data_panels', [$this, 'render_custom_tabs_panel']);
        add_action('woocommerce_process_product_meta', [$this, 'save_custom_tabs_panel']);
    }

    /**
     * Registriert den Custom Post Type für Woo Tabs
     */
    public function register_tabs_post_type()
    {
        $labels = [
            'name' => __('WooTabs', 'bitka-one'),
            'singular_name' => __('WooTab', 'bitka-one'),
            'add_new' => __('Neu hinzufügen', 'bitka-one'),
            'add_new_item' => __('Neuen Tab anlegen', 'bitka-one'),
            'edit_item' => __('bearbeiten', 'bitka-one'),
            'new_item' => __('Neuer Tab', 'bitka-one'),
            'view_item' => __('Tab ansehen', 'bitka-one'),
            'search_items' => __('Tabs durchsuchen', 'bitka-one'),
            'not_found' => __('Keine Tabs gefunden', 'bitka-one'),
            'not_found_in_trash' => __('Keine Tabs im Papierkorb', 'bitka-one'),
        ];
        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'supports' => ['title', 'editor'], // Gutenberg-Editor
            'capability_type' => 'page',
        ];
        register_post_type('woo_tab', $args);
    }

    /**
     * Fügt die Admin-Menüseite für Woo Tabs hinzu
     */
    public function register_tabs_menu()
    {
        add_submenu_page(
            'edit.php?post_type=product',
            __('WooTabs', 'bitka-one'),
            __('WooTabs', 'bitka-one'),
            'manage_options',
            'edit.php?post_type=woo_tab'
        );
    }

    /**
     * Multi-Select für Woo Tabs im Produkt-Backend
     */
    public function product_tabs_multiselect()
    {
        global $post;
        $selected = get_post_meta($post->ID, '_bitka_woo_tabs', true);
        $selected = is_array($selected) ? $selected : (array) $selected;
        $tabs = get_posts([
            'post_type' => 'woo_tab',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);
        echo '<div class="options_group">';
        echo '<p class="form-field"><label for="bitka_woo_tabs">' . esc_html__('WooTabs', 'bitka-one') . '</label>';
        echo '<select id="bitka_woo_tabs" name="bitka_woo_tabs[]" multiple style="min-width:200px;">';
        foreach ($tabs as $tab) {
            $is_selected = in_array($tab->ID, $selected) ? 'selected' : '';
            echo '<option value="' . esc_attr($tab->ID) . '" ' . $is_selected . '>' . esc_html($tab->post_title) . '</option>';
        }
        echo '</select>';
        echo '<span class="description">' . esc_html__('Wähle zusätzliche Tabs für dieses Produkt.', 'bitka-one') . '</span></p>';
        echo '</div>';
    }

    /**
     * Speichert die Tab-Auswahl im Produkt
     */
    public function save_product_tabs_multiselect($post_id)
    {
        if (isset($_POST['bitka_woo_tabs'])) {
            $tabs = array_map('intval', (array) $_POST['bitka_woo_tabs']);
            update_post_meta($post_id, '_bitka_woo_tabs', $tabs);
        } else {
            delete_post_meta($post_id, '_bitka_woo_tabs');
        }
    }

    /**
     * Metabox für Tab-Anzeigenamen
     */
    public function add_display_name_metabox()
    {
        add_meta_box(
            'woo_tab_display_name',
            __('Tab-Anzeigename', 'bitka-one'),
            [$this, 'render_display_name_metabox'],
            'woo_tab',
            'side',
            'default'
        );
    }

    public function render_display_name_metabox($post)
    {
        $value = get_post_meta($post->ID, '_bitka_tab_display_name', true);
        echo '<label for="bitka_tab_display_name">' . esc_html__('Anzeigename für den Tab im Frontend', 'bitka-one') . '</label>';
        echo '<input type="text" id="bitka_tab_display_name" name="bitka_tab_display_name" value="' . esc_attr($value) . '" style="width:100%;" />';
    }

    public function save_display_name_metabox($post_id)
    {
        if (isset($_POST['bitka_tab_display_name'])) {
            update_post_meta($post_id, '_bitka_tab_display_name', sanitize_text_field($_POST['bitka_tab_display_name']));
        }
    }

    /**
     * Produktdaten-Tab hinzufügen
     */
    public function add_custom_tabs_tab($tabs)
    {
        $tabs['bitka_woo_tabs'] = [
            'label'    => __('WooTabs', 'bitka-one'),
            'target'   => 'bitka_woo_tabs_panel',
            'class'    => [],
            'priority' => 80,
        ];
        return $tabs;
    }

    /**
     * Panel für die Tab-Auswahl und Sortierung
     */
    public function render_custom_tabs_panel()
    {
        global $post;
        $selected = get_post_meta($post->ID, '_bitka_woo_tabs', true);
        $selected = is_array($selected) ? $selected : (array) $selected;
        $tabs = get_posts([
            'post_type' => 'woo_tab',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);
        echo '<div id="bitka_woo_tabs_panel" class="panel woocommerce_options_panel">';
        echo '<h2>' . esc_html__('Zusätzliche Produkt-Tabs', 'bitka-one') . '</h2>';
        // Button zur WooTabs-Editor-Übersicht
        $wootabs_admin_url = admin_url('edit.php?post_type=woo_tab');
        echo '<a href="' . esc_url($wootabs_admin_url) . '" target="_blank" class="button button-secondary" style="margin-bottom:1em;">WooTabs Verwalten</a>';
        echo '<p>' . esc_html__('Ziehe die gewünschten Tabs in die gewünschte Reihenfolge. Nur ausgewählte Tabs werden angezeigt.', 'bitka-one') . '</p>';
        echo '<ul id="bitka-woo-tabs-sortable" style="list-style:none; margin:0; padding:0;">';
        // Zuerst die ausgewählten, sortiert
        foreach ($selected as $tab_id) {
            $tab = get_post($tab_id);
            if ($tab && $tab->post_status === 'publish') {
                $display_name = get_post_meta($tab->ID, '_bitka_tab_display_name', true);
                $label = $display_name ?: $tab->post_title;
                // Link zum Produkt, falls zugeordnet
                $product_link = get_edit_post_link($post->ID);
                $frontend_link = get_permalink($post->ID) . '#tab-' . $tab->ID;
                echo '<li class="bitka-woo-tab-item" data-id="' . esc_attr($tab->ID) . '">';
                echo '<input type="checkbox" name="bitka_woo_tabs[]" value="' . esc_attr($tab->ID) . '" checked style="margin-right:8px;" />';
                echo esc_html($label);
                echo ' <a href="' . esc_url(get_edit_post_link($tab->ID)) . '" target="_blank" style="font-size:0.9em; margin-left:1em;">Edit &#8599;</a>';
                echo '</li>';
            }
        }
        // Dann die nicht ausgewählten
        foreach ($tabs as $tab) {
            if (!in_array($tab->ID, $selected)) {
                $display_name = get_post_meta($tab->ID, '_bitka_tab_display_name', true);
                $label = $display_name ?: $tab->post_title;
                echo '<li class="bitka-woo-tab-item" data-id="' . esc_attr($tab->ID) . '">';
                echo '<input type="checkbox" name="bitka_woo_tabs[]" value="' . esc_attr($tab->ID) . '" style="margin-right:8px;" />';
                echo esc_html($label);
                echo ' <a href="' . esc_url(get_edit_post_link($tab->ID)) . '" target="_blank" style="font-size:0.9em; margin-left:1em;">Edit &#8599;</a>';
                echo '</li>';
            }
        }
        echo '</ul>';
        // jQuery UI Sortable
        echo '<script>jQuery(function($){ $("#bitka-woo-tabs-sortable").sortable({items:">li"}); });</script>';
        echo '</div>';
    }

    /**
     * Speichert die Tab-Auswahl und Sortierung
     */
    public function save_custom_tabs_panel($post_id)
    {
        if (isset($_POST['bitka_woo_tabs'])) {
            $tabs = array_map('intval', (array) $_POST['bitka_woo_tabs']);
            update_post_meta($post_id, '_bitka_woo_tabs', $tabs);
        } else {
            delete_post_meta($post_id, '_bitka_woo_tabs');
        }
    }

    /**
     * Gibt die ausgewählten Woo Tabs für ein Produkt zurück (als Array von WP_Post mit display_name)
     */
    public static function get_tabs_for_product($product_id)
    {
        $tab_ids = get_post_meta($product_id, '_bitka_woo_tabs', true);
        $tab_ids = is_array($tab_ids) ? $tab_ids : (array) $tab_ids;
        if (empty($tab_ids) || !$tab_ids[0]) return [];
        $tabs = get_posts([
            'post_type' => 'woo_tab',
            'post__in' => $tab_ids,
            'orderby' => 'post__in',
            'numberposts' => -1,
        ]);
        // Hole den Anzeigenamen als Property
        foreach ($tabs as &$tab) {
            $tab->display_name = get_post_meta($tab->ID, '_bitka_tab_display_name', true);
        }
        return $tabs;
    }
}
