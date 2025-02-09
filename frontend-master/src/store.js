import { createStore } from 'vuex';
import axios from 'axios';

export default createStore({
    state: {
        isLoggedIn: false,
        user: null
    },
    mutations: {
        setLogin(state, user) {
            state.isLoggedIn = true;
            state.user = user;
        },
        logout(state) {
            state.isLoggedIn = false;
            state.user = null;
        }
    },
    actions: {
        async login({ commit }, { email, password }) {
            try {
                const response = await axios.post('http://localhost:8000/api/login', { email, password });
                if (response.data.status) {
                    commit('setLogin', response.data.user);
                    return true;
                }
            } catch (error) {
                console.error('Login error:', error);
            }
            return false;
        },
        logout({ commit }) {
            commit('logout');
        }
    }
});
