    <footer>
        <div class="footer__content">
            <div class="footer__logo">Маршруты будущего</div>
            <div class="footer__text">© 2024. Все права защищены.</div>
            <form class="footer__form">
                <input type="text" placeholder="Email">
                <button>Подписаться на нас</button>
            </form>
            <div class="footer__social"><?php echo do_shortcode('[DISPLAY_ULTIMATE_SOCIAL_ICONS]'); ?></div>
        </div>
    </footer>
    <script src="<?php echo bloginfo('template_url'); ?>/js/main.js0"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- важно: включает контент подвала в WordPress -->
    <?php wp_footer(); ?>

    </body>

    </html>