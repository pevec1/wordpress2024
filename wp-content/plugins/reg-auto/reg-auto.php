<?php
/*
Plugin Name: Моя кастомная авторизация
Description: Плагин для регистрации, авторизации и восстановления пароля
Version: 1.0
Author: Андрей Харенков
*/

// Функция для авторизации пользователя
function my_custom_login()
{
    // Форма авторизации
    if (isset($_POST['login'])) {
        $login = sanitize_text_field($_POST['username']);
        $password = $_POST['password'];

        $user = wp_signon(array(
            'username' => $login,
            'password' => $password,
            'remember' => isset($_POST['rememberme'])
        ));

        if (is_wp_error($user)) {
            // Обработка ошибок
            echo $user->get_error_message();
        } else {
            // Успешная авторизация
            wp_redirect(home_url());
            exit;
        }
    }

    // Форма отображения
    // ...
}

// Функция для восстановления пароля
function my_custom_lost_password()
{
    // Форма восстановления пароля
    if (isset($_POST['lost_password'])) {
        $user_login = sanitize_email($_POST['user_login']);

        $user = get_user_by('email', $user_login);
        if (!$user) {
            // Пользователь не найден
        } else {
            function wp_send_password($user_id)
            {
                $user = get_userdata($user_id);
                $new_password = wp_generate_password();
                wp_set_password($new_password, $user_id);
                wp_password_change_notification($user);
            }
            wp_send_password($user->ID);
        }
    }

    // Форма отображения
    // ...
}
add_action('register_form', 'my_custom_registration_fields');
function my_custom_registration_fields()
{
    ob_start();
?>
    <form method="post" action="">
        <p>
            <label for="user_login">Логин:</label>
            <input type="text" name="user_login" id="user_login" class="input" value="<?php if (isset($_POST['user_login'])) echo esc_attr($_POST['user_login']); ?>" />
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" class="input" value="<?php if (isset($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
        </p>
        <p>
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="user_pass" />
        </p>
        <p><input type="submit" name="register" value="Зарегистрироваться" /></p>
    </form>
<?php
    return ob_get_clean();
}

    add_action('init', 'my_custom_registration_process');
    function my_custom_registration_process()
    {
        if (isset($_POST['register'])) {
            // Проверяем и очищаем данные
            $username = sanitize_text_field($_POST['user_login']);
            $password = $_POST['password'];
            $email = $_POST['email'];

            // Создаем нового пользователя
            $user_id = wp_create_user($username, $password, $email);

            if (is_wp_error($user_id)) {
                // Обработка ошибок
                echo $user_id->get_error_message();
            } else {
                // Успешная регистрация
                echo 'Пользователь зарегистрирован успешно!';
            }
        }
    }
    ?>