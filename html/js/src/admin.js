$(function() {
    svg_icons.load('/images/icons-admin.svg');

    // change the min-height on the main content so it's always covers the nav
    $(window).resize(function() {
        $('.js-content-wrap').css('min-height', $(window).height());
        $('body').removeClass('sidebar_nav-visible');
    });
    $(window).trigger('resize');

    // show/hide the menu
    // @todo if a submenu is open and another is opened, it will just close instead of opening the second one
    $('.js-sidebar_nav-submenu_trigger').on('click', function(e) {
        e.stopPropagation();

        $(this).toggleClass('sidebar_nav-submenu_arrow-open');
        $(this).parent().find('.js-sidebar_nav-submenu-wrap')
            .toggleClass('sidebar_nav-submenu-wrap-open')
        $('body').toggleClass('sidebar_nav-submenu-open');
    });

     $('.js-header_admin_small-menu').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('body').toggleClass('sidebar_nav-visible');
    });

    // click anywhere else on the doc and it will hide the nav
    $('html').on('click', function(e) {
        if (!$(e.target).is('.js-sidebar_nav-wrap') && $(e.target).parents('.js-sidebar_nav-wrap').length == 0) {
            $('.js-sidebar_nav-submenu_trigger').removeClass('sidebar_nav-submenu_arrow-open');
            $('.js-sidebar_nav-submenu-wrap').removeClass('sidebar_nav-submenu-wrap-open');
            $('body').removeClass('sidebar_nav-visible sidebar_nav-submenu-open');
        }
    });

    // on delete form submit & link click, display a confirmation
    $('.js-form-delete').on('submit', function(e) {
        admin.confirm_delete(this, e);
    });
    $('.js-link-delete').on('click', function(e) {
        admin.confirm_delete(this, e);
    });

    admin_user.setup_user_edit();
});

var admin = {
    confirm_delete : function(el, e)
    {
        var record_desc = $(el).data('record-desc');
        if (!confirm('Are you sure you want to delete this ' + record_desc + '? This cannot be undone.')) {
            e.preventDefault();
        }
    }
}

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