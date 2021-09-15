require('./bootstrap');

import Vue from 'vue';
import helpers from './plugins/helpers';
import swal from 'vue-sweetalert2';
import Toasted from 'vue-toasted';
import Vuex from 'vuex';

Vue.use(Toasted);

Vue.use(swal);
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        url: process.env.MIX_APP_URL,
        token: '',
    },
    mutations: {
        setToken (state, token) {
            // mutate state
            state.token = token
        }
    }
});

window.EventBus = new Vue();

// *************************************************************************
// Admin Panel Menu Components
// *************************************************************************

// Home
Vue.component('register-component', require('./components/Auth/RegisterComponent').default);
Vue.component('login-component', require('./components/Auth/LoginComponent').default);
Vue.component('home-component', require('./components/HomeComponent').default);

// *************************************************************************
// General Components
// *************************************************************************

Vue.prototype.$helpers = helpers;
window.Vue = Vue

const app = new Vue({
    el: '#app',
    swal,
    store
});
