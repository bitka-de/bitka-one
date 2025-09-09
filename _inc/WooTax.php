<?php
namespace Bitka;

class WooTax
{
    public function __construct() {}


    /**
     * Gibt einen Text wie "inkl. xx% MwSt." oder "zzgl. xx% MwSt." für ein Produkt zurück.
     *
     * @param int|WC_Product $product Produkt-ID oder Produktobjekt
     * @return string|null
     */
    public static function get_tax_label($product)
    {
        $rate = self::get_product_tax_rate($product);
        if ($rate === null) {
            return null;
        }
        $mode = self::get_tax_display_mode();
        return sprintf('%s %d%% MwSt.', $mode, round($rate));
    }

    /**
     * Gibt den höchsten Steuersatz (in Prozent) für ein Produkt zurück (inkl. zusätzlicher Steuersätze)
     *
     * @param int|WC_Product $product Produkt-ID oder Produktobjekt
     * @return float|null Steuersatz in Prozent oder null, wenn nicht gefunden
     */
    public static function get_product_tax_rate($product)
    {
        if (is_numeric($product)) {
            $product = wc_get_product($product);
        }
        if (!$product || !is_a($product, 'WC_Product')) {
            return null;
        }
        $tax_class = $product->get_tax_class();
        $tax_class = $tax_class === 'standard' ? '' : $tax_class;
        $tax_rates = WC_Tax::get_rates($tax_class);
        if (!empty($tax_rates)) {
            $rates = array_column($tax_rates, 'rate');
            $rates = array_map('floatval', $rates);
            return max($rates);
        }
        return null;
    }

    /**
     * Gibt zurück, ob die Produktpreise im Backend inkl. oder exkl. MwSt. eingegeben werden
     *
     * @return string "inkl." oder "exkl."
     */
    public static function get_tax_display_mode(): string
    {
        // WooCommerce-Option: 'yes' (inkl.) oder 'no' (exkl.)
        $prices_include_tax = get_option('woocommerce_prices_include_tax', 'yes');
        return $prices_include_tax === 'yes' ? 'inkl.' : 'zzgl.';
    }
}
