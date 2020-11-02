<template>
  <div v-if="emailAddress.length">
      <i class="fa fa-envelope"></i>
      {{ emailAddress[0].info }}
  </div>
</template>

<script>
  export default {
    data(){
      return{
        emailAddress: [],
      }
    },
    mounted() {
      this.getEmailAddress();
    },
    methods: {
      getEmailAddress: function(){
          // for scope of this within axios
          let self = this;
            axios.post('/emailaddressshowclient'
              )
             .then((res) => {
               console.log(res.data.emailaddress);
               if(res.data.emailaddress != undefined && res.data.emailaddress.length ){
                   self.emailAddress = res.data.emailaddress;
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Get Email Address', self);
            })
        },
    }
  }
</script>

<style lang="css">

</style>
