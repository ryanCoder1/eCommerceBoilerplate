<template>
        <div  class="dashboard-page-containers">
           <h3>Personal Info for Phone Number</h3>
            <h4 class="text-secondary" >Personal Info shows up on website.</h4>
              <!-- component is a checkbox with save button -->
              <phone-number
              v-bind:title="phoneNumber"
              v-bind:route-name="phoneNumberLC">
              </phone-number>
              <!-- component to adjust Phone Number -->
              <phone-number-input>
              </phone-number-input>


          </div>
</template>

<script>
import PhoneNumber from './checkcomponent.vue';
import PhoneNumberInput from './phonenumbercomponent.vue';
import { errorHandle } from '../../helper/errors';

  export default {
    components: {
      'phone-number': PhoneNumber,
      'phone-number-input': PhoneNumberInput,

    },
    data(){
      return{
        phoneNumber: 'Phone Number',
        phoneNumberLC: 'phonenumber',

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
