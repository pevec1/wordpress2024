
    <?php
    if (have_posts()):
        while (have_posts()) :
            the_post();
            if (is_page(2)||is_home()) :
                get_template_part('index');
            // elseif (is_page(8)) :
            //     get_template_part('template-parts/tury', 'page');
            elseif (is_page(6)) :
                get_template_part('template-parts/nam', 'page');
            // elseif (is_page(12)) :
            //     get_template_part('template-parts/zapis', 'page');
            elseif (is_page(10)) :
                get_template_part('template-parts/kabinet', 'page');
            elseif (is_page(20)) :
                get_template_part('template-parts/vhod', 'page');
            elseif (is_page(23)) :
                get_template_part('template-parts/lost', 'page');
                elseif (is_page('reset-password')) :
                    get_template_part('template-parts/reset', 'page');
                    elseif (is_404()) :
                        get_template_part('404');
                        endif;


            // If comments are open or we have at least one comment, load up the comment template.
            // if (comments_open() || get_comments_number()) :
            //     comments_template();
            // endif;

        endwhile; // End of the loop.
    endif;
    ?>

</main><!-- #main -->
