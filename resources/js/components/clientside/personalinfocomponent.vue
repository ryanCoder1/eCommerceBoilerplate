<template>
  <!-- personal info of nav -->
  <div id="navPersonalInfo" class="nav-personal-info">
    <div
      class="personal-info-left">
      <phone-number
       v-if="phoneNumberUse">
      </phone-number>

      <email-address
       v-if="emailAddressUse">
      </email-address>
    </div>
    <div
    class="social-media"
    v-if="socialMediaUse">
      <social-media-icons v-if="socialMediaUse"></social-media-icons>
    </div>
  </div>
</template>

<script>
import SocialMediaIcons from './socialmediaicons';
import PhoneNumber from './phonenumberclient';
import EmailAddress from './emailaddressclient';

  export default {
    components: {
      'social-media-icons': SocialMediaIcons,
      'phone-number': PhoneNumber,
      'email-address': EmailAddress,
    },
    data(){
      return{
        socialMediaUse: false,
        emailAddressUse: false,
        phoneNumberUse: false,
      }
    },
    mounted(){
      this.getPhoneNumberUse();
      this.getEmailAddressUse();
      this.getSocialMediaUse();
    },
    methods: {
    getPhoneNumberUse: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/phonenumberuseclient'
            )
           .then((res) => {
             if(res.data.using != undefined && res.data.using.length ){
                 if(res.data.using[0].using == 0){
                   self.phoneNumberUse = false;
                 }else{
                   self.phoneNumberUse = true;
                 }
              }
          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'Featured slide show load', self);
          })
      },
      getSocialMediaUse: function(){
          // for scope of this within axios
          let self = this;

            axios.post('/socialmediauseclient'
              )
             .then((res) => {
               if(res.data.using != undefined && res.data.using.length ){
                   if(res.data.using[0].using == 0){
                     self.socialMediaUse = false;
                   }else{
                     self.socialMediaUse = true;
                   }
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Featured slide show load', self);
            })
        },
        getEmailAddressUse: function(){
            // for scope of this within axios
            let self = this;

              axios.post('/emailaddressuseclient'
                )
               .then((res) => {
                 if(res.data.using != undefined && res.data.using.length ){
                     if(res.data.using[0].using == 0){
                       self.emailAddressUse = false;
                     }else{
                       self.emailAddressUse = true;
                     }
                  }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Featured slide show load', self);
              })
          }
        }
  }
</script>

<style lang="css">
</style>
