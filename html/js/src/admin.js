import 'babel-polyfill';

import Vue from 'vue';
import store from './admin/store';

import svg_icons from './common/svg_icons.vue';
import modal from './common/modal.vue';
import local_time from './common/local_time.vue';
import admin_menu_subnav from './admin/admin_menu_subnav.vue';
import admin_menu_small from './admin/admin_menu_small.vue';
import admin_delete from './admin/admin_delete.vue';
import admin_user from './admin/admin_user';
import list_check from './admin/list_check.vue';

// SASS/CSS
import '../../css/sass/admin.scss';

// disable the warning about dev/prod
Vue.config.productionTip = false;

// global components
Vue.component('modal', modal);
Vue.component('admin-delete', admin_delete);

new Vue({
    el: '#app',
    store,
    components: {
        'svg-icons': svg_icons,
        'local-time': local_time,
        'menu-subnav': admin_menu_subnav,
        'menu-small': admin_menu_small,
        'list-check': list_check,
        'admin-user': admin_user,
    },
    methods: {
        setServerData (data) {
            this.$store.dispatch('updateServerData', data);
        }
    }
});