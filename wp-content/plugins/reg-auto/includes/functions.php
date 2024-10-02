<?php
// В файле functions.php вашей темы или плагина:
add_action('init', 'my_plugin_session_start');
function my_plugin_session_start()
{
    session_start();
}

require_once ABSPATH . 'wp-admin/includes/media.php';
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
        reset_token varchar(255),
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

function my_custom_logout_form_function()
{
    if (isset($_POST['logout'])) {
        if (isset($_SESSION['user_data'])) {
            $_SESSION['user_data'] = null;
        }
    }
}

add_action('init', 'my_custom_logout_form_function');
// Функция для восстановления пароля
function custom_password_reset()
{
    if (isset($_POST['submit_email'])) {
        // Проверка и санитация данных формы
        $email = sanitize_email($_POST['email']);
        // Запрос к базе данных для поиска пользователя по email
        global $wpdb;
        $table_name = $wpdb->prefix . 'reg_auto_data';
        $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE user_email = %s", $email));
        if ($user) {
            // Генерация токена
            $reset_token = wp_generate_password(20, false);
            // Обновление записи в базе данных
            $wpdb->update(
                $table_name,
                array('reset_token' => $reset_token),
                array('id' => $user->id)
            );
            // Отправка письма со ссылкой для сброса пароля
            $to = $email;
            $subject = 'Сброс пароля';
            $message = "Ссылка для сброса пароля: " . site_url() . "/reset-password?token=" . $reset_token;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            //mail($to, $subject, $message);
            mail($to, $subject, $message);
            // Сообщение об успешной отправке письма
            echo "Проверьте свою почту для сброса пароля.";
        } else {
            echo "Пользователь с таким email не найден.";
        }
    }
}
add_action("init", "custom_password_reset");

function custom_user_password_reset_form()
{
    // Форма для ввода email
    ob_start();
?>
    <form method="post">
        <input type="email" name="email" placeholder="Введите ваш email">
        <button type="submit" name="submit_email">Отправить</button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_password_reset', 'custom_user_password_reset_form');

function custom_reset_password($token)
{
    global $wpdb;

    // 1. Проверка валидности токена
    $token_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}reg_auto_data WHERE token = %s", $token));

    if (!$token_data || time() > $token_data->expires) {
        return new WP_Error('invalid_token', __('Токен недействителен или истек', 'your-textdomain'));
    }

    // 2. Получение ID пользователя
    $user_id = $token_data->user_id;

    // 3. Проверка существования пользователя в кастомной таблице
    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}reg_auto_data WHERE id = %d", $user_id));

    if (!$user) {
        return new WP_Error('user_not_found', __('Пользователь не найден', 'your-textdomain'));
    }

    // 4. Форма для ввода нового пароля
    if (is_wp_error($user)) {
        // Вывод сообщения об ошибке
    } else {
    ?>
        <form method="post" action="">
            <input type="hidden" name="token" value="<?php echo esc_attr($token); ?>">
            <label for="password">Новый пароль:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" name="user_password" value="Сменить пароль">
        </form>
    <?php
    }

    // 5. Обработка отправки формы
    if (isset($_POST['password'])&&isset($_POST['user_password'])) {
        $new_password = sanitize_text_field($_POST['password']);

        // Хэширование нового пароля
        $hashed_password = wp_hash_password($new_password);

        // Обновление пароля в кастомной таблице
        $wpdb->update(
            $wpdb->prefix . 'reg_auto_data',
            array('user_pass' => $hashed_password),
            array('id' => $user_id)
        );

        // Удаление токена
        $wpdb->delete($wpdb->prefix . 'reg_auto_data', array('token' => $token));

        // Перенаправление на страницу успешной смены пароля
        wp_redirect(home_url('/'));
        exit;
    }
}

add_shortcode('custom_reset_password', 'custom_reset_password');
// Функция для создания формы
function my_upload_form()
{
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        Выберите изображение для загрузки:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Загрузить изображение" name="submit_image">
    </form>

    <script>
        jQuery(document).ready(function() {
            // Создаем элемент img
            var img = jQuery('<img>');

            // Устанавливаем путь к изображению
            img.attr('src', '/wp-content/plugins/reg-auto/includes/uploads/1.jpg?' + Math.random());
            img.width(100);
            img.height(100);
            // Добавляем изображение в элемент с id="image-container"
            jQuery('#image-container').append(img);
        });
    </script>
<?php
}
add_shortcode('my_upload_form', 'my_upload_form');

// Функция для обработки загрузки
function my_upload_action()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_image'])) {
        // Получаем информацию о загруженном файле
        $target_dir = MY_PLUGIN_DIR . "uploads/"; // Директория для сохранения файлов
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        // Проверяем размер файла
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Слишком большой файл.";
            $uploadOk = 0;
        }
        // Проверяем формат файла
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Разрешены только JPG, JPEG, PNG и GIF файлы.";
            $uploadOk = 0;
        }
        // Проверяем, был ли файл загружен без ошибок
        if ($uploadOk == 0) {
            echo "Извините, ваш файл не был загружен.";
            // Если всё в порядке, загружаем файл
        } else {
            unlink(__DIR__ . '/uploads/1.jpg');
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], __DIR__ . '/uploads/1.jpg')) {
                //echo "Файл " . basename($_FILES["fileToUpload"]["name"]) . " загружен.";
                // Выводим изображение
                //echo '<img src="' . $target_file . '" alt="Загруженное изображение">';
            } else {
                echo "Извините, произошла ошибка при загрузке вашего файла.";
            }
        }
    }
}
add_action('init', 'my_upload_action');

function my_custom_registration_fields()
{
    ob_start();
    global $wpdb;
?>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="image-container" id="image-container"></div>

                <p><?php echo do_shortcode('[my_upload_form]'); ?></p>
            </div>
            <div class="col-md-6">
                <form method="post" action="">
                    <div class="wrap">
                        <div class="mb-6">
                        </div>
                        <div class="mb-6">
                            <p>
                                <label for="user_login">Логин:</label>
                                <input type="text" name="user_login" id="user_login" class="input" value="<?php if (isset($_POST['user_login'])) echo esc_attr($_POST['user_login']); ?>" />
                            </p>
                            <p>
                                <label for="user_name">Имя:</label>
                                <input type="text" name="user_name" id="user_name" class="input" value="<?php if (isset($_POST['user_name'])) echo esc_attr($_POST['user_name']); ?>" />
                            </p>
                            <p>
                                <label for="email">Email:</label>
                                <input type="text" name="email" id="email" class="input" value="<?php if (isset($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
                            </p>
                            <p>
                                <label for="password">Пароль:</label>
                                <input type="password" name="password" id="user_pass" />
                            </p>
                            <p>
                                <label for="password_repeat">Повторите пароль:</label>
                                <input type="password" name="password_repeat" id="user_pass_repeat" />
                            </p>
                            <p><input type="submit" name="register" class="btn btn-primary" value="Зарегистрироваться" />
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        </>
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
        $name = sanitize_text_field($_POST['user_name']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        if ($password != $_POST['password_repeat']) {
            $errors = new WP_Error();
            $errors->add('password_mismatch', 'Пароли не совпадают.');
            return $errors;
        } else {

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
                'name' => $name,
                'avatar' => ''
            );

            $wpdb->insert($table_name, $data);
            $userdata = array(
                'user_login' => $username,
                'user_pass' => $password,
                'user_email' => $email,
                'name' => $name,
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