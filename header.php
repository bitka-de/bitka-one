<?php
if (! defined('ABSPATH')) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php include get_template_directory() . '/templates/header.php'; ?>