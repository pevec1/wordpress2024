<?php
// В файле functions.php вашей темы или плагина:
add_action('init', 'my_plugin_session_start');
function my_plugin_session_start()
{
    session_start();
}


function myplugin_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'reg_auto_data';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_login varchar(60) NOT NULL UNIQUE,
        user_email varchar(100) NOT NULL UNIQUE,
        user_pass varchar(255) NOT NULL,
        // Добавьте свои кастомные поля
        meta longtext,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(
    __FILE__,
    'myplugin_install'
);

// Функция для удаления таблицы при деактивации плагина
function myplugin_uninstall()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'reg_auto_data';
    $sql = "DROP TABLE IF EXISTS $table_name";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_uninstall_hook(__FILE__, 'myplugin_uninstall');

function my_login_form()
{
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $credentials = array(
            'user_login' => sanitize_text_field($_POST['username']),
            'user_password' => sanitize_text_field($_POST['password'])
        );

        global $wpdb;
        $table_name = $wpdb->prefix . 'reg_auto_data';
        $user_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE user_login = %s", $username));
        //echo "" . wp_check_password($password, $user_data->user_pass, $user_data->id) . "";
        if ($user_data) {
            // Проверяем пароль
            if (wp_check_password($password, $user_data->user_pass, $user_data->id)) {
                //echo "Logged in successfully ".$user_data->id;
                $_SESSION['user_data'] = $user_data;
            }
        }
    }
}

add_action('init', 'my_login_form');


function my_custom_login_form_function()
{
    ob_start();
    global $wpdb;
?>
    <form method="post" action="">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" id="username" required>
        <label for="password">Пароль:</label>

        <input type="password" name="password" id="password" required>
        <button type="submit">Войти</button>
    </form>
<?php
    return ob_get_clean();
}

add_action('init', 'my_custom_login_form_function');

function my_custom_logged_form_function()
{
    ob_start();
    global $wpdb;
?>  
    <form method="post" action="">
    <div>Вы авторизованы под именем <?php
                                    echo $_SESSION['user_data']->user_login; ?>
    </div>
    <p style="text-align: center;"><input type="submit" name="logout" class="btn btn-primary" value="Выйти" /></p>
    </form>
<?php
    return ob_get_clean();
}

add_action('init', 'my_custom_logged_form_function');

function my_custom_logout_form_function() {
    if (isset($_POST['logout'])) {
    if (isset($_SESSION['user_data'])) {
        $_SESSION['user_data'] = null;
    }
}
}

add_action('init', 'my_custom_logout_form_function');
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
}

function my_custom_registration_fields()
{
    ob_start();
    global $wpdb;
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
        <p><input type="submit" name="register" class="btn btn-primary" value="Зарегистрироваться" />
        </p>
    </form>
<?php
    return ob_get_clean();
}
add_action('init', 'my_custom_registration_fields');

function my_custom_login_fields()
{
    ob_start();
?>
    <form method="post" action="">
        <p>
            <label for="user_login">Логин:</label>
            <input type="text" name="user_login" id="user_login" class="input" value="<?php if (isset($_POST['user_login'])) echo esc_attr($_POST['user_login']); ?>" />
        </p>
        <p>
            <label for="password">Пароль:</label>
            <input type="password" name="password" id="user_pass" />
        </p>
        <p>
            <input type="checkbox" name="rememberme" id="rememberme" value="forever" />
            <label for="rememberme">Запомнить меня</label>
        </p>
        <p><input type="submit" class="btn btn-primary" name="login" value="Войти" />

            <?php
            $page_id = 10; // ID страницы, на которую нужно перейти
            $page_link = get_permalink($page_id);
            ?>

            <a href="<?php echo esc_url($page_link); ?>" class="btn btn-secondary">Зарегистрироваться</a>
        </p>
        </p>
    </form>
    <?php
    return ob_get_clean();
}
//add_action('login_form', 'my_custom_login_fields');

function my_custom_registration_process($user_id)
{
    if (isset($_POST['register'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'reg_auto_data';

        // Проверяем и очищаем данные
        $username = sanitize_text_field($_POST['user_login']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        // Проверка на существование пользователя
        $user = $wpdb->get_row("SELECT * FROM $table_name WHERE email = '$email' OR login = '$username'"); // $user = get_user_by('email', $email);
        if ($user) {
            // Пользователь с таким email уже существует
            wp_die(__('Пользователь с таким email или логином уже существует.', 'my-plugin'));
        }

        // $user = get_user_by('login', $username);
        // if ($user) {
        //     // Пользователь с таким email уже существует
        //     wp_die(__('Пользователь с таким логином уже существует.', 'my-plugin'));
        // }

        // Создание пользователя
        // $user_id = wp_create_user($username, $password, $email);

        // if (is_wp_error($user_id)) {
        //     // Обработка ошибок при создании пользователя
        //     wp_die($user_id->get_error_message());
        // }

        $data = array(
            'id' => $user_id,
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => wp_hash_password(
                $password
            ),
            'name' => '',
            'avatar' => ''
        );

        $wpdb->insert($table_name, $data);
        $userdata = array(
            'user_login' => $username,
            'user_pass' => $password,
            'user_email' => $email,
        );
        wp_insert_user(wp_slash($userdata));
        // Отправка email-уведомления
        wp_new_user_notification($user_id);

        $page_id = 20; // ID страницы, на которую нужно перейти
        $page_link = get_permalink($page_id);
        wp_redirect($page_link);
        exit;
    }
}
add_action('init', 'my_custom_registration_process');

function custom_login_link()
{
    ob_start();
    ?>
    <form method="post" action="">
    <div>Вы авторизованы под именем <?php
                                    echo $_SESSION['user_data']->user_login; ?>
    </div>
    <p style="text-align: center;"><input type="submit" name="logout" class="btn btn-secondary" value="Выйти" /></p>
    </form>
    <?php
    return ob_get_clean();
}

add_action('init', 'custom_login_link');
function custom_logout_link()
{
    if ($_SESSION['user_data']) {
        echo '<a href="' . wp_logout_url(get_permalink()) . '">Выйти</a>';
    } else {
        echo 'Вы не авторизованы';
    }
}


// Добавляем хук для вывода формы
add_shortcode('my_custom_registration2', 'my_custom_registration_form');

add_shortcode('register_form', 'my_custom_registration_fields');

// Создаем шорткод для вывода формы
add_shortcode('login_form', 'my_custom_login_form_function');
add_shortcode('logged_form', 'my_custom_logged_form_function');

//add_shortcode('login_form', 'my_custom_login_fields');

add_shortcode('my_custom_login', 'my_custom_login');

add_shortcode('my_custom_lost_password', 'my_custom_lost_password');

add_shortcode('custom_logout', 'custom_logout_link');
add_shortcode('custom_login', 'custom_login_link');



?>