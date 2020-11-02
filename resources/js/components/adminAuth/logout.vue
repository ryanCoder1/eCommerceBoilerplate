<template>
    <div id="button" class="text-center text-secondary">
      <h1>Logging out</h1>
      <div class="mt-5">
        <span class="circle-one loader-dots"></span>
        <span class="circle-two loader-dots"></span>
        <span class="circle-three loader-dots"></span>
      </div>
    </div>
</template>



<script>
import { adminLogout } from '../../helper/auth';

export default {

    data(){
      return{
          title: 'logout',

      }

    },
    mounted(){
      this.logout();
    },
    methods: {

    logout: function(){

            this.$store.dispatch('admin/logout');
            this.$store.dispatch('admin/alertLogout');
            this.$router.push('/admin/login');

       }

     }
  }





</script>
<style>
/* CSS for Field Jobs Tracker Navigation Bar Logged in */
#button {
margin: 200px 0 0 0;
  color: black !important;
}

</style>
