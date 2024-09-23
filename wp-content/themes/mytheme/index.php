<? get_header(); ?>
<? /* if (function_exists('the_custom_logo')) {
    the_custom_logo();
} */ ?>

<? echo do_shortcode('[my_shortcode]'); ?>
<? get_template_part('template-parts/home-page'); ?>
<? get_sidebar(); ?>
<? get_footer(); ?>