<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package mytheme
 */

get_header();
?>
<section class="error-404 not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Упс! Такой страницы не существует.', 'mytheme'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'mytheme'); ?></p>
    </div>
</section>
<?php
get_footer();
