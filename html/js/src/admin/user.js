var admin_user = {
    setup_user_edit: function() {
        $('.js-admin_user-set_password').on('change', function() {
            if ($(this).find('[type=checkbox]').is(':checked')) {
                $('.js-admin_user-password').removeClass('hidden')
                    .find('input').focus();
            } else {
                $('.js-admin_user-password').addClass('hidden');
            }
        });

        // on load
        if ($('.js-admin_user-set_password :checked').length) {
            $('.js-admin_user-password').removeClass('hidden');
        }
    }
};