<?php
/*
Plugin Name: File Upload Plugin
Description: Allows you to upload a file to the WordPress media library.
Version: 1.0.0
Author: Your Name
Author URI: https://yourwebsite.com
*/

// Add a custom button to the media uploader
function file_upload_button() {
    echo '<form id="featured_upload" method="post" action="" enctype="multipart/form-data">
        <input type="file" name="my_image_upload" id="my_image_upload" multiple="false" />
        <input type="hidden" name="post_id" id="post_id" value="55" />
        '. wp_nonce_field('my_image_upload', 'my_image_upload_nonce') .'
        <input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
    </form>';
}
add_action('media_buttons', 'file_upload_button');

// Add a custom upload action
function file_upload_action() {
    global $post;

    // Check if a file was selected
    if (
        isset($_POST['my_image_upload_nonce'], $_POST['post_id'])
        && wp_verify_nonce($_POST['my_image_upload_nonce'], 'my_image_upload')) {
        // Get the file details
        $file = $_POST['my_image_upload'];

        // Check if the file is an image
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (in_array($file_extension, $allowed_types)) {
            // Create an array of file data
            $file_data = array(
                'name' => $file['name'],
                'type' => $file['type'],
                'tmp_name' => $file['tmp_name'],
                'error' => $file['error'],
                'size' => $file['size']
            );

            // Insert the file into the media library
            $media_id = media_handle_sideload($file_data, $post->ID);
            if (is_wp_error($media_id)) {
                // Handle the error
                $error_message = $media_id->get_error_message();
                // Display an error message or perform other actions
            } else {
                // File uploaded successfully
                // Display a success message or perform other actions
            }
        } else {
            // File type not allowed
            // Display an error message or perform other actions
        }
    }
}
add_action('wp_ajax_file_upload_action', 'file_upload_action');

// Enqueue the script for the custom media button
function file_upload_script() {
    wp_enqueue_script('file-upload-script', plugin_dir_url(__FILE__) . 'file-upload-script.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'file_upload_script');

function my_custom_menu_page()
{
    add_menu_page(
        'Моя страница с шорткодом',
        'Мой шорткод',
        'manage_options',
        'my-shortcode-page',
        'my_shortcode_content_function'
    );
}
add_action('admin_menu', 'my_custom_menu_page');

function my_shortcode_content_function()
{
    echo do_shortcode('[my_custom_shortcode]');
}

function my_custom_shortcode2()
{
    return 'Это содержимое моего шорткода';
}
add_shortcode('my_custom_shortcode', 'file_upload_button');