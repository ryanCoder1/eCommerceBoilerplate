<template>
  <div v-if="phoneNumber.length">
      <i class="fa fa-phone"></i>
      {{ phoneNumber[0].info }}
  </div>
</template>

<script>
  export default {
    data(){
      return{
        phoneNumber: [],

      }
    },
    mounted() {
      this.getPhoneNumber();
    },
    methods: {
      getPhoneNumber: function(){
          // for scope of this within axios
          let self = this;
            axios.post('/phonenumbershowclient'
              )
             .then((res) => {
               console.log(res.data.phonenumber);
               if(res.data.phonenumber != undefined && res.data.phonenumber.length ){
                   self.phoneNumber = res.data.phonenumber;
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Get Phone Number', self);
            })
        },
    }
  }
</script>

<style lang="css">

</style>
