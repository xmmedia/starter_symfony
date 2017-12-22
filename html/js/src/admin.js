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
        this.$nextTick(() => {
            this.setMenuHeight();
            window.addEventListener('resize', this.setMenuHeight);
        });
    },
    methods: {
        setServerData (data) {
            this.$store.dispatch('updateServerData', data);
        },
        setMenuHeight () {
            let windowHeight = this.getWindowHeight();
            // 60 = height of header, 90 = height of bottom wrap, 20 = a little extra
            let percentage = (windowHeight - 60 - 90 - 20) / windowHeight * 100;

            document.querySelectorAll('.js-sidebar_nav-nav')[0].style.maxHeight = percentage+'%';
        },
        getWindowHeight() {
            let w = window,
                d = document,
                e = d.documentElement,
                g = d.body;

            return w.innerHeight || e.clientHeight || g.clientHeight;
        }
    }
});