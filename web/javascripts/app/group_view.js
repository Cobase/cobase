$(document).ready(function () {

    // Hide the submit button
    $('#postForm').submit( function(e) {
        $('#formSubmit').hide();
    });

    // Make the submission textarea autosize on text input
    $('#cobase_appbundle_posttype_content').autosize();

});

