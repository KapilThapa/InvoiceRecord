<template>
    <div class="main">
       <Header />
       <div class="content container-fluid">
			<div class="row" v-if="isLoggedIn">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
						<li :class="{'active': subIsActive('/dashboard')}" exact>
							<router-link :to="{name: 'dashboard'}">
								Dashboard
                    </router-link>
						</li>
						<li :class="{'active': subIsActive('/recording')}" exact>
							<router-link :to="{name: 'recording'}">
								Record Keeping
                    </router-link>
						</li>
						<li :class="{'active': subIsActive('/records')}" exact>
							<router-link :to="{name: 'records'}">
								Filter Records
                    </router-link>
						</li>
					</ul>
					<div class="tab-content p-15 bg-white" id="nav-tabContent">
						<router-view></router-view>
					</div>
				</div>
			</div>
			<div v-else>
				<router-view></router-view>
			</div>
       </div>
    </div>
</template>

<script>
import Header from './components/partials/Header.vue';
export default {
	name: 'main-App',
	components: { Header },
	methods:{
		subIsActive(input) {
			const paths = Array.isArray(input) ? input : [input]
			return paths.some(path => {
				return this.$route.path.indexOf(path) === 0 // current path starts with this path string
			});
		}
	},
	computed:{
		isLoggedIn(){
			return this.$store.getters.isLoggedIn
		}
	}
}
</script>
