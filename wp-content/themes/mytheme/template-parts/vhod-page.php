<?php
get_header();
?>
<main>
	<?php wp_body_open(); ?>
	<h1>Маршруты будущего</h1>
	<div class="content_nam">
		<div class="">
			<div class="">
				<? if ($_SESSION['user_data']) {
					//do_shortcode('[custom_logout]');
					echo do_shortcode('[logged_form]');
				} else {
					echo do_shortcode('[login_form]');
					$page_id = 23; // ID страницы, на которую нужно перейти
					$page_link = get_permalink($page_id);
					?>
					<a href="<?php echo esc_url($page_link); ?>" class="btn btn-secondary">Забыли пароль?</a>
					<?
				}
				?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();