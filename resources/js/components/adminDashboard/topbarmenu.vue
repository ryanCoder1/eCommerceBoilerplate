<template>
        <div class="top-bar-menu-container">
          <span class="top-bar-hamburger" v-if="hamburger" v-on:click="showMenuContainer()">
              <i class="fa fa-bars"></i>
          </span>
          <div class="top-bar-user">
              <div>
                <router-link :to="{ path: '/admin/adminInvite' }">
                  <a>
                    + Invite
                  </a>
               </router-link>
              </div>
              <div class="top-bar-user-info">
                <i class='fa fa-user'></i>
                {{ currentAdmin.first_name }} {{ currentAdmin.last_name }}
              </div>
              <!-- <div>
                <i class="fa fa-caret-down i-top-bar-info"></i>
                <add-user
                  v-bind:current-admin="currentAdmin"
                ></add-user>
              </div> -->
          </div>
        </div>
</template>



<script>


export default {

    props: {
      'currentAdmin': Object,
    },
    data(){
      return{
        hamburger: true,
      }

  },
  methods: {
    showMenuContainer: function(){
      this.$root.$emit('showMenuContainer', true);
    }
  }


}


</script>
<style>


</style>
