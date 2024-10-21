<?php
get_header();
?>
<main>
	<?php wp_body_open(); ?>
	<h1>Маршруты будущего</h1>
	<div class="content_nam">
		<div class="">
			<div class="">
				<?php ?>
				<? if ($_SESSION['user_data']) {
					echo do_shortcode('[my_file_upload]');
					echo do_shortcode('[register_form]'); 
					echo do_shortcode('[logged_form]');
				} else {
					echo do_shortcode('[my_file_upload]');
					echo do_shortcode('[register_form]'); 
					?>
					<a href="<?php echo get_permalink(20); ?>" class="btn btn-secondary">Войти</a>
					<?
				}
				?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();