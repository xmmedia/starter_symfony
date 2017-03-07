import Vue from 'vue';
import Vuex from 'vuex';

import * as types from './store/mutation_types';
import adminMenu from './store/admin_menu';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        serverData: {},
    },
    getters: {
    },
    actions: {
        updateServerData ({ commit, state }, serverData) {
            commit(types.UPDATE_SERVER_DATA, serverData);
        },
    },
    mutations: {
        [types.UPDATE_SERVER_DATA] (state, serverData) {
            state.serverData = serverData;
        },
    },

    modules: {
        adminMenu,
    }
});