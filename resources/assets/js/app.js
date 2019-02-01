require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import {routes} from './routes.js';
import storeData from './store.js';
import MainApp from './MainApp.vue';
import Vuelidate from 'vuelidate';

Vue.use(Vuelidate);
Vue.use(VueRouter);
Vue.use(Vuex);

const router = new VueRouter({
    routes,
    mode: 'history'
});

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h('MainApp'),
    mounted(){
    	if(this.$router.currentRoute.meta.requiresAuth){
    		if(!this.$store.getters.isLoggedIn){
				this.$router.push({path: '/login'});
			}
    	}
		if (this.$router.currentRoute.path == '/login') {
			if (this.$store.getters.isLoggedIn) {
				this.$router.push({path: '/dashboard'});
			}
		}
    },
    components: {
        MainApp
    }
});
