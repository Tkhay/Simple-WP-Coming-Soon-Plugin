jQuery(document).ready(function($){
    // Color Picker
    $('.ccs-color-field').wpColorPicker();

    // Media Uploader
    $('#ccs_logo_button').on('click', function(e){
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Logo',
            multiple: false
        }).open()
        .on('select', function(){
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $('#ccs_logo').val(image_url);
        });
    });
});
