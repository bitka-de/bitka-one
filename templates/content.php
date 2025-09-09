<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
<?php else : ?>
        <?php include get_template_directory() . '/templates/no-content.php'; ?>
<?php endif; ?>