$(function () {
    $(document).ready(function () {
        $('#submitbutton').click(function (e) {
            e.preventDefault();
            var button = $(this);
            button.prop('disabled', true);
            button.removeClass('btn-success');
            button.html("Please wait...");
            $.post(button.data('target'), $("#inviteform").serialize())
                .done(function () {
                    console.log('success!');
                    button.addClass('btn-success');
                    button.html('WOOT. Check your email!');
                })
                .fail(function () {
                    console.log('fail!');
                    button.addClass('btn-danger');
                    button.html('Error occurred');
                })
                .always(function () {
                    button.prop('disabled', false);
                });
            return false;
        });
    });
});
