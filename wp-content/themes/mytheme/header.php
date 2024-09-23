<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
</head>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="theme-color" content="#c9e0e04d">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/styles/main.css">
    <?php
    wp_head();
    ?>

</head>

<body>

    <?php wp_body_open(); ?>
    <main class="wrap">
        <header>
            <img src="<?php echo bloginfo('template_url'); ?>/img/hero.png" alt="Маршруты будущего">
            <nav>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">Главная</a></li>
                    <li><a href="<?php echo get_permalink(8); ?>">Туры</a></li>
                    <li><a href="<?php echo get_permalink(6); ?>">Напишите нам</a></li>
                    <li><a href="<?php echo get_permalink(12); ?>">Записаться</a></li>
                </ul>
            </nav>
            <div>
                <a href="<?php echo get_permalink(10); ?>">Личный кабинет</a>
            </div>
            <!-- <?php //wp_nav_menu(array('theme_location' => 'header-menu'));
                    ?> -->
        </header>