jQuery(document).ready(function($) {
    // Attach a click event to the custom media button
    $('#file-upload-button').click(function(e) {
        e.preventDefault();

        // Create a hidden form to handle the file upload
        var form = $('<form></form>');
        form.attr('id', 'file-upload-form');
        form.attr('method', 'POST');
        form.attr('action', ajaxurl);

        // Add a hidden input field for the nonce
        var nonce_field = $('<input></input>');
        nonce_field.attr('type', 'hidden');
        nonce_field.attr('name', '_wpnonce');
        nonce_field.val(wp_create_nonce('file_upload_action'));
        form.append(nonce_field);

        // Add a hidden input field for the action
        var action_field = $('<input></input>');
        action_field.attr('type', 'hidden');
        action_field.attr('name', 'action');
        action_field.val('file_upload_action');
        form.append(action_field);

        // Add a hidden input field for the file
        var file_field = $('<input></input>');
        file_field.attr('type', 'hidden');
        file_field.attr('name', 'file_upload');
        file_field.attr('accept', 'image/*');
        form.append(file_field);

        // Append the form to the page
        $('body').append(form);

        // Trigger the file upload
        file_field.trigger('click');
    });
});