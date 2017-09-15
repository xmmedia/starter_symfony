import Vue from 'vue';
import Vuex from 'vuex';

import adminMenu from './menu/store';

Vue.use(Vuex);

export default new Vuex.Store({
    namespaced: true,
    state: {
        serverData: {},
    },
    getters: {},
    actions: {
        updateServerData ({ commit }, serverData) {
            commit('setServerData', serverData);
        },
    },
    mutations: {
        setServerData (state, serverData) {
            state.serverData = serverData;
        },
    },

    modules: {
        adminMenu,
    }
});