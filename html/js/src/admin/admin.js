var admin = {
    setup_deletes : function()
    {
        // on delete form submit & link click, display a confirmation
        $('.js-form-delete').on('submit', function(e) {
            e.preventDefault();
            admin.confirm_delete(this);
        });

        $('.js-link-delete').on('click', function(e) {
            e.preventDefault();
            admin.confirm_delete(this);
        });
    },

    confirm_delete : function(el)
    {
        var $el = $(el),
            md = md.get_md(),
            record_desc = $el.data('record-desc'),
            form_action = $el.attr('action') ? $el.attr('action') : $el.attr('href'),
            msg = 'Are you sure you want to delete this '+record_desc+'? This cannot be undone.',
            delete_form = '<form action="'+form_action+'" method="POST"><input type="hidden" name="_method" value="DELETE"><button>Delete</button></form>';

        md.find('.js-md-content_wrap').html('<p class="md-content-center">'+msg+'</p>');
        md.find('.js-md-button_wrap').html(delete_form);

        md.open();

        md.find('.js-md-button_wrap').find('button').focus();
    },

    setup_menu : function()
    {
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
    }
};