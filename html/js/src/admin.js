import Vue from 'vue';
import VueResource from 'vue-resource';

import svg_icons from './common/svg_icons';
import admin_menu_subnav from './admin/admin_menu_subnav';
import admin_menu_small from './admin/admin_menu_small';
import admin_user from './admin/admin_user';
import list_check from './admin/list_check';

Vue.use(VueResource);

new Vue({
    el: '#app',
    components: {
        'svg-icons': svg_icons,
        'menu-subnav': admin_menu_subnav,
        'menu-small': admin_menu_small,
        'list-check': list_check,
        'admin-user': admin_user,
    }
});