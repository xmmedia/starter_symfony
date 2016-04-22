$(function() {
    svg_icons.load('/images/icons-admin.svg');

    admin.setup_menu();
    admin.setup_deletes();

    admin_user.setup_user_edit();
});