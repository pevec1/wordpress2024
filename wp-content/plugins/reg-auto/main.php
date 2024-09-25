<?php
/*
Plugin Name: Моя кастомная авторизация
Description: Плагин для регистрации, авторизации и восстановления пароля
Version: 1.0
Author: Андрей Харенков
*/

// Функция для создания таблицы при активации плагина

// В файле functions.php вашей темы или плагина:
// main.php
define('MY_PLUGIN_DIR', plugin_dir_path(__FILE__));
require_once MY_PLUGIN_DIR . 'includes/functions.php';
?>