<?php

/**
 * Bitka Custom WooCommerce Product Template
 *
 * @var WC_Product $product
 */

global $product;

if (!$product) return;

if (!($product instanceof WC_Product)) {
    $product = wc_get_product(get_the_ID());
}

$product_id = $product->get_id();
$product_headline = $product->get_name();
$product_tax = WooTax::get_tax_label($product_id);
$product_shipping = WooShipping::get_shipping_time($product_id);
$product_shipping_url = WooShipping::get_shipping_page_url();
$product_tabs = WooTabs::get_tabs_for_product($product_id);
$gallery_image_ids = $product->get_gallery_image_ids();

?>

<article id="product-<?= $product_id; ?>" <?php post_class('bitka-product'); ?>>
    <section class="bitka-product-main">

        <div class="bitka-product-main_images">
            <div class="bitka-product-main_gallery">
            <?php
            // Use a wrapper for the main image to allow JS swapping
            $main_image_id = has_post_thumbnail($product_id) ? get_post_thumbnail_id($product_id) : ( !empty($gallery_image_ids) ? $gallery_image_ids[0] : null );
            if ($main_image_id) {
                echo wp_get_attachment_image($main_image_id, 'large', false, [
                'id' => 'bitka-main-gallery-image',
                'class' => 'bitka-main-gallery-image',
                'data-image-id' => $main_image_id
                ]);
            }
            ?>
            </div>

            <?php if (!empty($gallery_image_ids)) : ?>
            <div class="bitka-product-gallery">
                <?php foreach ($gallery_image_ids as $image_id): ?>
                <div class="bitka-product-gallery-item">
                    <?= wp_get_attachment_image($image_id, 'thumbnail', false, [
                    'class' => 'bitka-gallery-thumb',
                    'data-image-id' => $image_id
                    ]); ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const mainImg = document.getElementById('bitka-main-gallery-image');
            const thumbs = document.querySelectorAll('.bitka-gallery-thumb');
            thumbs.forEach(thumb => {
                thumb.style.cursor = 'pointer';
                thumb.addEventListener('click', function() {
                const newImgId = this.getAttribute('data-image-id');
                if (!newImgId || !mainImg) return;
                // AJAX to get new image HTML
                fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=bitka_get_gallery_image&image_id=' + newImgId)
                    .then(res => res.text())
                    .then(html => {
                    const temp = document.createElement('div');
                    temp.innerHTML = html;
                    const newImg = temp.querySelector('img');
                    if (newImg) {
                        newImg.id = 'bitka-main-gallery-image';
                        newImg.classList.add('bitka-main-gallery-image');
                        newImg.setAttribute('data-image-id', newImgId);
                        mainImg.replaceWith(newImg);
                    }
                    });
                });
            });
            });
        </script>


        <div class="bitka-product-shortdesc">
            <h1 class="bitka-product_title"><?= esc_html($product_headline); ?></h1>

            <div class="bitka-product_meta">
                <span class="bitka-product_meta_item">
                    <span>
                        <?= esc_html($product_tax); ?>
                    </span>
                    <button popovertarget="shipping-popover" popovertargetaction="show" type="button" style="margin-left:auto; font-size:0.8em; text-decoration:underline; cursor:pointer; background:none; border:none; padding:0;">(zzgl. Versand)</button>
                </span>

                <span class="bitka-product_meta_item">
                    <svg viewBox="0 0 256 256">
                        <path d="m223.68 66.15-88-48.15a15.88 15.88 0 0 0-15.36 0l-88 48.17a16 16 0 0 0-8.32 14v95.64a16 16 0 0 0 8.32 14l88 48.17a15.88 15.88 0 0 0 15.36 0l88-48.17a16 16 0 0 0 8.32-14V80.18a16 16 0 0 0-8.32-14.03ZM128 32l80.34 44-29.77 16.3-80.35-44Zm0 88L47.66 76l33.9-18.56 80.34 44ZM40 90l80 43.78v85.79l-80-43.75Zm176 85.78-80 43.79v-85.75l32-17.51V152a8 8 0 0 0 16 0v-44.45L216 90v85.77Z" />
                    </svg>
                    <span>
                        Lieferzeit: ~ <?= esc_html($product_shipping); ?>
                    </span>
                </span>
            </div>

            <div popover="auto" id="shipping-popover" class="bitka-popover" style="position:absolute; background:#fff; border:1px solid #ccc; padding:1em; z-index:1000; max-width:90vw; width:30rem; box-shadow:0 2px 8px rgba(0,0,0,0.15); margin-inline:auto; margin-block:auto;">
                <?= $product_shipping_url; ?>
                <button popovertarget="shipping-popover" popovertargetaction="hide" type="button" style="margin-top:0.5em;">Schließen</button>
            </div>

        </div>
    </section>

    <?php if (!empty($product_tabs)): ?>
        <section class="bitka-product-tabs">
            <div class="bitka-tabs-nav">
                <?php foreach ($product_tabs as $i => $tab): ?>
                    <?php $tab_label = !empty($tab->display_name) ? $tab->display_name : $tab->post_title; ?>
                    <button type="button" class="bitka-tab-nav-btn" data-tab="tab-<?= $tab->ID ?>" <?= $i === 0 ? ' aria-selected="true"' : '' ?>><?= esc_html($tab_label) ?></button>
                <?php endforeach; ?>
            </div>
            <div class="bitka-tabs-content">
                <?php foreach ($product_tabs as $i => $tab): ?>
                    <div class="bitka-tab-content" id="tab-<?= $tab->ID ?>" style="display:<?= $i === 0 ? 'block' : 'none' ?>;">
                        <?= apply_filters('the_content', $tab->post_content) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <script>
            // Simple JS for tab switching
            document.addEventListener('DOMContentLoaded', function() {
                const navBtns = document.querySelectorAll('.bitka-tab-nav-btn');
                const tabContents = document.querySelectorAll('.bitka-tab-content');
                navBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        navBtns.forEach(b => b.setAttribute('aria-selected', 'false'));
                        tabContents.forEach(tc => tc.style.display = 'none');
                        btn.setAttribute('aria-selected', 'true');
                        const tabId = btn.getAttribute('data-tab');
                        document.getElementById(tabId).style.display = 'block';
                    });
                });
            });
        </script>
    <?php endif; ?>
</article>