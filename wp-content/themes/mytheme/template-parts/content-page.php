<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package mytheme
 */

?>
<?php
	if ( is_page('туры') ) {
		get_template_part( 'tury', 'page' );
	}
	elseif ( is_page('42') ) {
		get_template_part( 'template-parts/nam', 'page' );
	}
?>
