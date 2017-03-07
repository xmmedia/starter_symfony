import * as types from './mutation_types';

const state = {
    mobileMenuOpen: false,
};

const getters = {
};

const actions = {
    mobileMenuOpen ({ commit, state }, open) {
        commit(types.ADMIN_MOBILE_MENU_OPEN, open);
    },
};

const mutations = {
    [types.ADMIN_MOBILE_MENU_OPEN] (state, open) {
        state.mobileMenuOpen = open;
    },
};

export default {
    state,
    getters,
    actions,
    mutations
}