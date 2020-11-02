<template>
        <div  class="dashboard-page-containers">
           <h3>Personal Info for Email Address</h3>
            <h4 class="text-secondary" >Personal Info shows up on website.</h4>

              <!-- component is a checkbox with save button -->
              <email-address
              v-bind:title="emailAddress"
              v-bind:route-name="emailAddressLC">
              </email-address>
              <!-- component that adjusts email address -->
              <email-address-input>
              </email-address-input>

          </div>
</template>

<script>

import EmailAddress from './checkcomponent.vue';
import EmailAddressInput from './emailaddresscomponent.vue';
import { errorHandle } from '../../helper/errors';

  export default {
    components: {
      'email-address': EmailAddress,
      'email-address-input': EmailAddressInput,
    },
    data(){
      return{
        emailAddress: 'Email Address',
        emailAddressLC: 'emailaddress',

      }
    },
    created(){
      this.$root.$on('sideBarHeight', (value) =>{
         this.sideBarHeight(value);
      })
    },
    mounted() {
      this.pageOpen();
    },
    methods: {
      pageOpen: function(){
        this.$root.$emit('subMenu', 'Personal Info');
      },
      sideBarHeight: function(elem){
        this.$root.$emit('sideBarHeight', elem);
      },

    }
  }
</script>

<style lang="css">
</style>
