import 'babel-polyfill';

import Vue from 'vue';
import store from './admin/store';

import svgIcons from './common/svg_icons';
import modal from './common/modal';
import localTime from './common/local_time';
import adminMenuSubnav from './admin/menu/subnav';
import adminMenuSmall from './admin/menu/small';
import adminDelete from './admin/admin_delete';
import adminUserForm from './admin/user/form';
import listCheck from './admin/list_check';

// SASS/CSS
import '../../css/sass/admin.scss';

// disable the warning about dev/prod
Vue.config.productionTip = false;

// global components
Vue.component('modal', modal);
Vue.component('admin-delete', adminDelete);
Vue.component('list-check', listCheck);
Vue.component('local-time', localTime);

window.App = new Vue({
    el: '#app',
    store,

    components: {
        'svg-icons': svgIcons,
        'menu-subnav': adminMenuSubnav,
        'menu-small': adminMenuSmall,
        'admin-user': adminUserForm,
    },

    mounted () {
        if (this.$el.dataset && this.$el.dataset.serverData) {
            this.$store.commit('updateServerData', JSON.parse(this.$el.dataset.serverData));
        }
    },
});