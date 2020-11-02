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
import { logout } from '../../helper/auth';

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
      logout(this.$store.state.userInfo.currentUser.token, this.$store.state.userInfo.currentUser.id)
        .then((res) =>{
            this.$store.dispatch('userInfo/logout');
            this.$store.dispatch('userInfo/alertLogout');
            this.$router.push('/login');
          })
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
