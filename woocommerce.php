<?php get_header(); ?>

<main id="main" class="woo-content">
    <?php
    if (function_exists('is_cart') && is_cart()) {
        wc_get_template('cart.php');
    } elseif (function_exists('is_product') && is_product()) {
        $template = locate_template('templates/woo/bitka-product.php');
        if ($template) {
            include $template;
        } else {
            woocommerce_content();
        }
    } else {
        woocommerce_content();
    }
    ?>
</main>

<?php get_footer();
