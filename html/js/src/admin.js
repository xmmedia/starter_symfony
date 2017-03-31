import 'babel-polyfill';

import Vue from 'vue';
import store from './admin/store';

import svgIcons from './common/svg_icons.vue';
import modal from './common/modal.vue';
import localTime from './common/local_time.vue';
import adminMenuSubnav from './admin/menu/subnav.vue';
import adminMenuSmall from './admin/menu/small.vue';
import adminDelete from './admin/admin_delete.vue';
import adminUserForm from './admin/user/form';
import listCheck from './admin/list_check.vue';

// SASS/CSS
import '../../css/sass/admin.scss';

// disable the warning about dev/prod
Vue.config.productionTip = false;

// global components
Vue.component('modal', modal);
Vue.component('admin-delete', adminDelete);
Vue.component('list-check', listCheck);
Vue.component('local-time', localTime);

new Vue({
    el: '#app',
    store,
    components: {
        'svg-icons': svgIcons,
        'menu-subnav': adminMenuSubnav,
        'menu-small': adminMenuSmall,
        'admin-user': adminUserForm,
    },
    methods: {
        setServerData (data) {
            this.$store.dispatch('updateServerData', data);
        }
    }
});