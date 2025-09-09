<?php
namespace Bitka\Inc;

class WooShipping
{
    public function __construct()
    {
        // Admin menu for shipping times
        add_action('admin_menu', [$this, 'register_shipping_times_menu']);
        add_action('admin_init', [$this, 'register_shipping_times_settings']);
        // Product select field
        add_action('woocommerce_product_options_general_product_data', [$this, 'product_shipping_time_select']);
        add_action('woocommerce_process_product_meta', [$this, 'save_product_shipping_time_select']);
        // WooCommerce settings
        add_filter('woocommerce_get_settings_pages', [$this, 'add_shipping_page_to_pages_settings']);
        add_filter('woocommerce_get_settings_advanced', [$this, 'insert_shipping_page_after_agb'], 10, 2);
    }

    /**
     * Register custom admin menu for shipping times
     */
    public function register_shipping_times_menu()
    {
        add_submenu_page(
            'edit.php?post_type=product',
            __('Versandzeiten', 'bitka-one'),
            __('Versandzeiten', 'bitka-one'),
            'manage_options',
            'bitka_shipping_times',
            [$this, 'shipping_times_page']
        );
    }

    /**
     * Register settings for shipping times
     */
    public function register_shipping_times_settings()
    {
        register_setting('bitka_shipping_times_group', 'bitka_shipping_times', [
            'type' => 'array',
            'default' => [],
        ]);
    }

    /**
     * Render the shipping times admin page
     */
    public function shipping_times_page()
    {
        if (!current_user_can('manage_options')) return;
        if (isset($_POST['bitka_shipping_times_nonce']) && wp_verify_nonce($_POST['bitka_shipping_times_nonce'], 'bitka_shipping_times_save')) {
            $times = array_map('sanitize_text_field', array_filter(explode("\n", $_POST['bitka_shipping_times'] ?? '')));
            update_option('bitka_shipping_times', $times);
            echo '<div class="updated"><p>' . __('Gespeichert!', 'bitka-one') . '</p></div>';
        }
        $shipping_times = get_option('bitka_shipping_times', []);
        ?>
        <div class="wrap">
            <h1><?php _e('Versandzeiten verwalten', 'bitka-one'); ?></h1>
            <form method="post">
                <?php wp_nonce_field('bitka_shipping_times_save', 'bitka_shipping_times_nonce'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Versandzeiten (eine pro Zeile)', 'bitka-one'); ?></th>
                        <td>
                            <textarea name="bitka_shipping_times" rows="8" cols="40"><?php echo esc_textarea(implode("\n", $shipping_times)); ?></textarea>
                            <p class="description"><?php _e('Beispiel: 1-3 Werktage, 2 Wochen, Sofort lieferbar ...', 'bitka-one'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Add select field for shipping time to product data panel
     */
    public function product_shipping_time_select()
    {
        global $post;
        $selected = get_post_meta($post->ID, '_bitka_shipping_time', true);
        $options = get_option('bitka_shipping_times', []);
        if (!is_array($options)) $options = [];
        echo '<div class="options_group">';
        woocommerce_wp_select([
            'id' => 'bitka_shipping_time',
            'label' => __('Versandzeit', 'bitka-one'),
            'options' => array_merge(['' => __('Bitte wählen', 'bitka-one')], array_combine($options, $options)),
            'value' => $selected,
            'desc_tip' => true,
            'description' => __('Wähle eine Versandzeit für dieses Produkt.', 'bitka-one'),
        ]);
        echo '</div>';
    }

    /**
     * Save shipping time select from product data panel
     */
    public function save_product_shipping_time_select($post_id)
    {
        $val = isset($_POST['bitka_shipping_time']) ? sanitize_text_field($_POST['bitka_shipping_time']) : '';
        update_post_meta($post_id, '_bitka_shipping_time', $val);
    }

    /**
     * Helper: Gibt Versandzeit für ein Produkt zurück
     */
    public static function get_shipping_time($product_id): string
    {
        $val = get_post_meta($product_id, '_bitka_shipping_time', true);
        return $val ?: '';
    }

    /**
     * Fügt das Feld zur Auswahl der Versandzeiten-Seite in die WooCommerce-Seiteneinrichtung ein
     */
    public function add_shipping_page_to_pages_settings($settings)
    {
        foreach ($settings as $settings_page) {
            if (isset($settings_page->id) && $settings_page->id === 'general') {
                add_filter('woocommerce_settings_pages', function($fields) {
                    $pages = get_pages(['post_type' => 'page']);
                    $options = ['' => __('Bitte wählen', 'bitka-one')];
                    foreach ($pages as $page) {
                        $options[$page->ID] = $page->post_title;
                    }
                    $fields[] = [
                        'title'    => __('Versandzeiten-Seite', 'bitka-one'),
                        'desc_tip' => true,
                        'id'       => 'bitka_shipping_page_id',
                        'type'     => 'select',
                        'options'  => $options,
                        'default'  => '',
                        'desc'     => __('Wähle die Seite, die als Versandzeiten-Seite genutzt werden soll.', 'bitka-one'),
                    ];
                    return $fields;
                });
            }
        }
        return $settings;
    }

    /**
     * Fügt das Feld zur Auswahl der Versandzeiten-Seite in den WooCommerce-Tab "Erweitert" (Seiteneinrichtung) ein
     */
    public function insert_shipping_page_after_agb($settings, $current_section)
    {
        if ($current_section === '') {
            $new_settings = [];
            foreach ($settings as $setting) {
                $new_settings[] = $setting;
                if (isset($setting['id']) && $setting['id'] === 'woocommerce_terms_page_id') {
                    $pages = get_pages(['post_type' => 'page']);
                    $options = ['' => __('Bitte wählen', 'bitka-one')];
                    foreach ($pages as $page) {
                        $options[$page->ID] = $page->post_title;
                    }
                    $new_settings[] = [
                        'title'    => __('Versand-Seite', 'bitka-one'),
                        'desc_tip' => true,
                        'id'       => 'bitka_shipping_page_id',
                        'type'     => 'select',
                        'options'  => $options,
                        'default'  => '',
                        'desc'     => __('Wähle die Seite, die als Versandzeiten-Seite genutzt werden soll.', 'bitka-one'),
                    ];
                }
            }
            return $new_settings;
        }
        return $settings;
    }

    /**
     * Gibt die URL der in den WooCommerce-Einstellungen ausgewählten Versand-Seite zurück
     *
     * @return string|null
     */
    public static function get_shipping_page_url(): ?string
    {
        $page_id = get_option('bitka_shipping_page_id');
        if ($page_id && get_post_status($page_id) === 'publish') {
            $content = get_post_field('post_content', $page_id);
            if ($content) {
                return '<div class="bitka-shipping-dialog">' . apply_filters('the_content', $content) . '</div>';
            }
        }
        return null;
    }
}