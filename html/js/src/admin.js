import Vue from 'vue';
import VueResource from 'vue-resource';

import svg_icons from './common/svg_icons';
import admin_menu_subnav from './admin/admin_menu_subnav';
import admin_menu_small from './admin/admin_menu_small';
import admin_user from './admin/admin_user';

Vue.use(VueResource);
new Vue({
    el: 'body',
    components: {
        'svg-icons': svg_icons,
        'menu-subnav': admin_menu_subnav,
        'menu-small': admin_menu_small,
        'admin-user': admin_user,
    }
});