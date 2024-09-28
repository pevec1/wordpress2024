<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mytheme
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    if (have_posts()):
        while (have_posts()) :
            the_post();
            if (is_page(8)) :
                get_template_part('template-parts/tury', 'page');
            elseif (is_page(6)) : ?>
<?php the_title(); ?>
    <?php the_content();
                get_template_part('template-parts/nam', 'page');
            elseif (is_page(12)) :
                get_template_part('template-parts/zapis', 'page');
            elseif (is_page(10)) :
                get_template_part('template-parts/kabinet', 'page');
            elseif (is_page(20)) :
                get_template_part('template-parts/vhod', 'page');
            elseif (is_page(23)) :
                get_template_part('template-parts/lost', 'page');
            endif;


            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
    endif;
    ?>

</main><!-- #main -->

<?php

get_footer();
