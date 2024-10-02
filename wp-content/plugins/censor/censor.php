<?php
/*
Plugin Name: Фильтр нецензурной лексики Censor
Description: Заменяет нецензурные слова на звездочки
Version: 1.0
Author: Андрей Харенков
Author URI: Ваш сайт
*/

function censor_message()
{
    $args = array(
        'post_type'      => 'post', // Выбираем только посты
        'posts_per_page' => -1, // Выводим все посты
        'orderby'        => 'date', // Сортируем по дате
        'order'          => 'ASC'  // По убыванию
    );

    $query = new WP_Query($args);

    if (
        $query->have_posts()
    ) {
        while ($query->have_posts()) {

            $query->the_post();
            // Здесь вы можете работать с текущим постом
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $post_phone = get_post_meta($post_id,'post_phone', true);
            $post_author_email = get_the_author_meta('email');
            $post_content = get_the_content();
            //$all_content = $message;
            $bad_words = array('козёл', 'козел', 'дурак');
            $replacement = '***'; // Создаем строку из звездочек
            //$return = str_replace($bad_words, '<span style="color: red;">'.$replacement.'</span>', mb_strtolower($all_content));

            $post_content =  str_replace($bad_words, '<span style="color: red;">' . $replacement . '</span>', mb_strtolower($post_content));


            // Output the modified content
            //echo $post_content;

             echo '<p> Пост ' . $post_id . '    Заголовок: ' . $post_title . '   Содержимое: ' . $post_content . '   Телефон: ' . $post_phone . '   Автор: ' . $post_author_email . '</p>';
        }
        wp_reset_postdata();
    }
    // return $return;

    // Customize the content

}

add_action('get_the_content', 'censor_message');
add_shortcode('censor_message', 'censor_message');
