<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mytheme
 */

// if (! is_active_sidebar('sidebar-1')) {
//     return;
// }
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('sidebar-1'); ?>
    <div class="sidebar">
        <div class="sidebar__content">Экскурсии</div>
        <div class="map">Карта
            <a href="https://yandex.ru/maps/?um=constructor%3Ab8581983e969960a8ac76ae799c4bffe33dae274a49a61d4575deaed673a2f75&amp;source=constructorStatic" target="_blank"><img src="https://api-maps.yandex.ru/services/constructor/1.0/static/?um=constructor%3Ab8581983e969960a8ac76ae799c4bffe33dae274a49a61d4575deaed673a2f75&amp;width=500&amp;height=400&amp;lang=ru_RU" alt="" style="border: 0;" /></a>
        </div>
        <div class="last__news">Новости
            <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Ab8581983e969960a8ac76ae799c4bffe33dae274a49a61d4575deaed673a2f75&amp;width=500&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
        </div>
        <div class="calendar">Календарь</div>
    </div>

</aside><!-- #secondary -->