$(document).ready(function () {
    $('body').on('submit', 'form[action][method].ajax', function (e) {
        e.preventDefault();

        var $form = $(this);

        if ($form.data('submitted')) {
            return;
        }

        ajaxSubmit($form.data('submitted', true));
    });

    $('body').on('click', '.ajax_hidden_form', function (e) {
        var form_id = $(this).data('form-id');
        $('#'+form_id).submit();
        return false;
    });
});
