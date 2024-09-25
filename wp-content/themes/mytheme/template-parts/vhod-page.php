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
				}
				?>
			</div>
		</div>
	</div>
</main>