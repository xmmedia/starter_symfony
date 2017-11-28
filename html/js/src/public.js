import 'babel-polyfill';

import Vue from 'vue';

import svgIcons from './common/svg_icons.vue';

// SASS/CSS
import '../../css/sass/public.scss';

// disable the warning about dev/prod
Vue.config.productionTip = false;

new Vue({
    el: '#app',
    components: {
        'svg-icons': svgIcons,
    }
});
