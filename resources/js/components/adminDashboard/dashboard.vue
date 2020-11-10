<template>
        <div id="dashboardContainer">
          <side-bar-menu
            v-bind:side-bar-height="sideBarHeight"
            v-bind:sub-menu="subMenu">
          </side-bar-menu>
          <top-bar-menu
            v-bind:current-admin="currentAdmin"
          ></top-bar-menu>
          <router-view></router-view>
        </div>
</template>



<script>

import SideBarMenu from './sidebarmenu.vue';
import TopBarMenu from './topbarmenu.vue';
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'side-bar-menu': SideBarMenu,
      'top-bar-menu': TopBarMenu,
    },

    data(){
      return{
        sideBarHeight: 500,
        subMenu: null,
        currentAdmin: {},
      }

  },
  beforeRouteLeave(to, from, next) {
    // when currentAdmin leaves dashboard to client view sign them out
    if(this.$store.state.admin.currentAdmin != null && to.path != '/admin/adminInvite'){
      this.$store.dispatch('admin/logoutQuiet');
      next();
    }else{
      next();
    }

  },
  created(){
    this.$root.$on('sideBarHeight', (value) =>{
       this.sideBarHeight = value;
    }),
    this.$root.$on('subMenu', (value) =>{
       this.subMenu = value;

    }),
    this.$root.$on('showMenuContainer', (value) =>{
       this.showMenuContainer();
    }),
    this.$root.$on('closeMenuContainer', (value) =>{
       this.closeMenuContainer();
    })
  },
 mounted(){
     this.testIsLoggedInDashboard();
     this.currentAdminSet();
 },
  computed: {

  },
    methods: {
      testIsLoggedInDashboard: function(){
        // boolean if false then show Nav bar
          if(!this.$store.state.admin.isLoggedInDashboard){
              console.log(this.$store.state.admin.currentAdmin);
            this.$router.push('/admin/logoutQuiet');

            console.log('here in dash');
          }
      },
      currentAdminSet: function(){
        // admin of personal account
        // when loading Dashboard, check of currentAdmin is null/push to logout.
        if(this.$store.state.admin.currentAdmin){
           this.currentAdmin = this.$store.state.admin.currentAdmin;
        }else{
            this.$router.push('/admin/logoutQuiet');
        }
      },
      showMenuContainer: function(){
        // on small screen, move into view the side bar menu
        // by adding an active class the transitions into view
        let elmnt = document.getElementsByClassName('side-bar-menu-container');
          elmnt[0].classList.add('active');
      },
      closeMenuContainer: function(){
        // on small screen, move into view the side bar menu
        // by adding an active class the transitions into view
        let elmnt = document.getElementsByClassName('side-bar-menu-container');
          elmnt[0].classList.remove('active');
      }
    }
  }





</script>
<style>
#dashboardContainer {

}

</style>
