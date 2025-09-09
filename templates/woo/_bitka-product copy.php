<?php

/**
 * Bitka Custom WooCommerce Product Template
 *
 * @var WC_Product $product
 */


// if (! $product || ! is_a($product, 'WC_Product')) {
//     $product_id = get_the_ID();
//     $product = wc_get_product($product_id);
// }
if (! $product || ! is_a($product, 'WC_Product')) return;

global $product;

$product_id = $product->get_id();
$product_headline = $product->get_name();
$product_tax = WooTax::get_tax_label($product_id);


?>



<article id="product-<?= $product_id; ?>" <?php post_class('bitka-product'); ?>>

    <section class="bitka-product-main">
        <div class="bitka-product-main_gallery">
            <?php
            if (has_post_thumbnail($product_id)) {
                echo get_the_post_thumbnail($product_id, 'large');
            }
            ?>
        </div>

        <div class="bitka-product-shortdesc">
            <h1 class="bitka-product_title"><?php echo esc_html($product_headline); ?></h1>
            <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>






            <div class="bitka-product-price">

                <?php if (class_exists('Woo')): ?>
                    <span class="bitka-mwst-status">
                        <?php echo esc_html($product_tax); ?>
                    </span>


    

                <?php echo esc_html(WooShipping::get_shipping_time($product->get_id())); ?>


                
                <?php echo $product->get_price_html(); ?>
            </div>
        </div>

    </section>

</article>








<article style="display: none;">




    <header class="bitka-product-header">
        <h1 class="bitka-product-title"><?php the_title(); ?></h1>
    </header>

    <div class="bitka-product-main">
        <div class="bitka-product-gallery">
            <?php
            if (has_post_thumbnail($product_id)) {
                echo get_the_post_thumbnail($product_id, 'large');
            }
            ?>
        </div>

        <div class="bitka-product-shortdesc">
            hallo

            <div class="bitka-product-price">
                <?php echo $product->get_price_html(); ?>
            </div>
        </div>

    </div>

    <div class="bitka-product-summary">

        <div class="bitka-product-shortdesc">
            <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
        </div>
        <div class="bitka-product-meta">
            <span class="bitka-mwst-status">
                <?php if (class_exists('Woo')) echo esc_html(Woo::get_mwst_status($product->get_id())); ?> MwSt.
            </span>
            <span class="bitka-taxclass">
                <?php if (class_exists('Woo')) echo esc_html(Woo::get_taxclass($product->get_id())); ?>
            </span>
        </div>
        <div class="bitka-product-addtocart">
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>
    </div>

    <div class="bitka-product-content">
        <?php the_content(); ?>
    </div>
</article>