<?
add_theme_support('custom-logo');

function themename_custom_logo_setup()
{
    $defaults = array(
        'height'               => 100,
        'width'                => 400,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array('site-title', 'site-description'),
        'unlink-homepage-logo' => true,
    );
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'themename_custom_logo_setup');

function it_blog_style_frontend()
{
    wp_enqueue_style('styles', get_stylesheet_directory_uri() . '/styles/style.css');
}

add_action('wp_enqueue_scripts', 'it_blog_style_frontend');
function it_blog_include_myscript()
{
    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.3.1.slim.min.js', '', '1.0', false);
    wp_enqueue_script('scripts', get_stylesheet_directory_uri() . '/js/main.min.js', '', '1.0', false);
}

add_action('wp_enqueue_scripts', 'it_blog_include_myscript');

function register_my_menus()
{
    register_nav_menus(
        array(
            'header-menu' => __('Header Menu'),
            'extra-menu' => __('Extra Menu')
        )
    );
}
add_action('init', 'register_my_menus');

add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);
function special_nav_class($classes, $item)
{
    if (in_array('current-menu-item', $classes)) {
        $classes[] = 'active ';
    }
    return $classes;
}

function themename_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Primary Sidebar', 'theme_name'),
        'id'            => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    register_sidebar(array(
        'name'          => __('Secondary Sidebar', 'theme_name'),
        'id'            => 'sidebar-2',
        'before_widget' => '<ul><li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li></ul>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}

add_action('init', 'themename_widgets_init');

function my_custom_shortcode()
{
    return 'Это мой кастомный шорткод';
}
add_shortcode('my_shortcode', 'my_custom_shortcode');



// Функция для обработки формы
add_action('init', 'my_contact_form');
function my_contact_form()
{
    if (isset($_POST['submit_message_nam'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field(
            $_POST['phone']
        );
        $message = sanitize_textarea_field(
            $_POST['message']
        );

        $post_data = array(
            'post_title'   => 'Напишите нам '. $name,
            'post_content' => $message,
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_phone'   => $phone,
            // Или другой тип записи
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            // Обработка ошибок
        } else {
            // Отправка уведомления
        }


        $to = 'pevec1@yandex.ru'; // Замените на ваш email
        $subject = 'Новое сообщение с сайта';
        $body = "Имя: $name\nEmail: $email\nТелефон: $phone\nСообщение:\n$message";

        wp_mail($to, $subject, $body);

        // Отправляем сообщение об успешной отправке
        echo '<p>Сообщение успешно отправлено!</p>';
        sleep(3); // Пауза в 3 секунды
        $page_id = 6; // Замените на нужный ID страницы
        $page_link = get_permalink($page_id);
        wp_redirect($page_link);
        exit;
    }
}

// Добавляем шорткод для вывода формы
add_shortcode('message_form', 'my_custom_message_fields');

add_action('message_form', 'my_custom_message_fields');
function my_custom_message_fields()
{
    ob_start();
?>
    <form method="post" action="">
        <div class="mb-6">
            <label for="name" class="form-label">Имя</label>
            <input type="text" class="form-control" name="name" id="name" aria-describedby="nameHelp" required>

        </div>
        <div class="mb-6">
            <label for="email" class="form-label">Email:</label>

            <input type="text" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-6">
            <label for="phone" class="form-label">Номер телефона:</label>
            <input type="tel" name="phone" class="form-control" id="phone" required>
        </div>
        <div class="mb-6">
            <label for="message" class="form-label">Сообщение:</label>
            <textarea class="form-control" name="message" id="message" rows="3"></textarea>
        </div>

        <input type="submit" class="btn btn-primary" name="submit_message_nam" value="Отправить">
    </form>
<?php
    return ob_get_clean();
}

?>