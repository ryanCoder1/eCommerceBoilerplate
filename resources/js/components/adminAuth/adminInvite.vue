<template>
  <div class="non-popup-form-wrapper">
          <h1 class="admin-invite-error mx-auto text-secondary" v-if="!currentAdmin">This page works when logged into Dashboard</h1>
          <h1 class="admin-invite-non-admin-error mx-auto text-secondary" v-if="currentAdmin && currentAdmin.privilege == 'subAdmin' ">
            User must be Admin in Dashboard to invite users.
            <p class="admin-invite-dashboard-non-admin">
              <router-link :to="{ path: '/dashboard' }"><a>Dashboard</a></router-link>
            </p>
          </h1>
           <!-- v-if="currentAdmin && currentAdmin.privilege == 'admin'" -->
           <form id="adminInviteForm" class="form">

              <h3>Dashboard Email Invite
                <span class="crossingLine"></span>
              </h3>

               <br/>
                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"  type="email"  v-model="form.email"/>
                          <span class="labelStyle">Email</span>
                        </p>
                      </div>
                      </label>


                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"  type="text"  v-model="form.firstName"/>
                          <span class="labelStyle" >First Name</span>
                        </p>
                      </div>
                      </label>

                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"   type="text"  v-model="form.lastName"/>
                          <span class="labelStyle" >Last Name</span>
                        </p>
                      </div>
                      </label>
                      <label class="col-12 mx-auto">
                        <div class="inputContainer">
                          <p>
                            <select class="form-control formInput" name="state" v-model="form.privilege">
                            <option value="" disabled>Choose privilege</option>
                            <option value="admin">Admin</option>
                            <option value="subAdmin">Sub Admin</option>
                         </select>
                        <span class="labelStyle">Privilege</span>
                       </p>
                       </div>
                     </label>
                       <small class="bg-danger text-light p-1" v-if="errors != ''">{{ errors }} </small>

                     <p class="admin-invite-btn" v-on:click="sendAdminInvite()">
                       <input type="button" value="Send Dashboard Invite" v-if="!loading"/>
                       <load-dots
                        v-if="loading"
                        v-bind:loading-dots="loadingDots"
                        >
                      </load-dots>
                     </p>
                     <p class="admin-invite-dashboard" v-if="currentAdmin">
                       <router-link :to="{ path: '/dashboard' }"><a>Dashboard</a></router-link>
                     </p>
              </form>


    </div>
</template>



<script>
import LoadDots from '../pages/loaddots.vue';

export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
          form:{
                email: '',
                firstName: '',
                lastName: '',
                privilege: '',
                invitedBy: 'Ryan Lackey',
              },
          isActive: true,
          errors: '',
          loading: false,
          loadingDots: true,
      }
    },
    methods: {
      sendAdminInvite: function(){
        // for scope of this within axios
        let self = this;
        this.loading = true;

          if(this.$data.form.email != '')
          {

            axios.post('/admininvite', this.$data.form)
             .then((res) => {
               if(res.data.mailAdminInvite){
                  self.$data.form.email = '';
                  self.$data.form.firstName = '';
                  self.$data.form.lastName = '';
                  self.$data.form.privilege = '';
                  self.$data.isActive = false;
                  self.loading = false;
                  alert('An email has been sent successfully!');

             }else{
                self.loading = false;
                self.errors = 'Could not be mailed';
             }
            }).catch((error) => {
              console.log(error.response.data);
              this.errors = error.response.data.errors;

            })
          }else{
          this.errors =  ["Email must be a valid format"];
          }
        }

      },
      computed: {
        currentAdmin: function(){
          // admin of personal account
           return this.$store.state.admin.currentAdmin;
        },
      }

    }




</script>
<style>
.admin-invite-error {
  margin-top: 200px;
}
.admin-invite-dashboard {
  margin: 15px 20px 0  0;
  float: right;
}
.admin-invite-dashboard a{
   font-weight: bold;
}

.admin-invite-non-admin-error {
  margin-top: 200px;
  text-align: center;
}
</style>
