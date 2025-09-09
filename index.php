<?php get_header(); ?>
<div class="content-page grow">

    <?php
    $breadcrumbs = bitka_get_breadcrumbs();
    if ($breadcrumbs) {
        echo '<nav class="breadcrumb">';
        foreach ($breadcrumbs as $i => $crumb) {
            $isLast = ($i === array_key_last($breadcrumbs));
            if ($isLast) {
                echo '<span>' . esc_html($crumb['title']) . '</span>';
            } else {
                echo '<a href="' . esc_url($crumb['url']) . '">' . esc_html($crumb['title']) . '</a> &raquo; ';
            }
        }
        echo '</nav>';
    }
    ?>

    <?php if (function_exists('is_cart') && is_cart()) : ?>
        <h1>Hallo Warenkorb</h1>
        <?php include get_template_directory() . '/templates/content.php'; ?>

    <?php elseif (function_exists('is_checkout') && is_checkout()) : ?>
        <h1>Checkout</h1>
        <?php include get_template_directory() . '/templates/content.php'; ?>

    <?php else : ?>
        <?php include get_template_directory() . '/templates/content.php'; ?>
    <?php endif; ?>

</div>
<?php get_footer(); ?>