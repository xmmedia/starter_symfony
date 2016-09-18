import Vue from 'vue';
import VueResource from 'vue-resource';

import svg_icons from './common/svg_icons';

Vue.use(VueResource);
new Vue({
    el: 'body',
    components: {
        'svg-icons': svg_icons,
    }
});