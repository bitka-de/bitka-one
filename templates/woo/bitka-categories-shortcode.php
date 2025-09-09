<?php
/**
 * Template for Bitka Product Categories Shortcode
 *
 * @var array $categories
 * @var string $title
 */
?>
<section class="bitka-categories-section">
    <h2><?= esc_html($title) ?></h2>
    <div class="bitka-categories">
        <?php foreach ($categories as $category): ?>
            <?php
                $thumbnailId = get_term_meta($category->term_id, 'thumbnail_id', true);
                $imageUrl = $thumbnailId ? wp_get_attachment_url($thumbnailId) : wc_placeholder_img_src();
                $categoryLink = get_term_link($category);
            ?>
            <a class="bitka-category" href="<?= esc_url($categoryLink) ?>">
                <img src="<?= esc_url($imageUrl) ?>" alt="<?= esc_attr($category->name) ?>" />
                <strong><?= esc_html($category->name) ?></strong>
            </a>
        <?php endforeach; ?>
    </div>
</section>
