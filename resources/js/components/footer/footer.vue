<template>
    <div class="footer-container" v-if="!isLoggedInDashboard">
      <ul class="footer-uls footer-logo-ul">
        <li>
          <div id="footerLogoogoWrapper" class="navbar-options">
            <span id="footerLogoSign">
              <span class="nav-field">Field</span>
              <span class="nav-sign-jobs font-weight-bold text-primary ">Jobs
                <i class="fas fa-long-arrow-alt-right nav-sign-arrow-1"></i>
                <i class="fas fa-long-arrow-alt-right nav-sign-arrow-2"></i>
              </span>
              <span class="nav-tracker">Tracker</span>
            </span>
            <span id="navSignUnder"></span>
          </div>
        </li>
        <li>
          <p class="text-light footer-saying">Where business takes place</p>
        </li>
      </ul>
        <!-- page links -->
        <ul class="footer-list-ul footer-uls" v-bind:class="{ footerUlsPerc: !isLoggedIn }">

          <li class="footer-list">
            <span class="footer-grey-text footer-list-header">Access</span><br/>

            <ul class="footer-user-list">
               <li class="footer-grey-text"><router-link :to="{ path: '/about' }"><a>About</a></router-link></li>
               <li class="footer-grey-text"><router-link :to="{ path: '/admin/login' }"><a>Admin</a></router-link></li>
               <li class="footer-grey-text" v-if="!isLoggedIn"><router-link :to="{ name: 'Login', path: '/login' }"><a>Log in</a></router-link>
               <li class="footer-grey-text" v-if="!isLoggedIn"><router-link :to="{ name: 'Register', path: '/register' }"><a>Register</a></router-link></li>
               <li class="footer-grey-text" v-if="isLoggedIn"><router-link :to="{ name: 'Dashboard', path: '/dashboard' }"><a>Dashboard</a></router-link></li>
               <li class="footer-grey-text" v-if="isLoggedIn"><router-link :to="{ name: 'Logout', path: '/logout' }"><a>Logout</a></router-link></li>
            </ul>
          </li>
            <li class="footer-grey-text footer-list" v-show="navHeaderViews">
              <span class="footer-list-header">Customize <span class="job-span">Jobs</span></span>
              <ul class="footer-nav-list footer-nav-list-2">


              </ul>
            </li>
            <li class="footer-grey-text footer-list" v-show="navHeaderViews">
            <span class="footer-list-header">Operate <span class="job-span">Jobs</span></span>
                <ul class="footer-nav-list">

                </ul>
            </li><!-- End of Large Nav functionality -->

        </ul>

      <div class="footer-copy">
        &#169; <span>{{ currentYear }}</span> Field Jobs Tracker. All Rights Reserved.
      </div>
    </div>
</template>



<script>


export default {

  data(){
    return{

    }
  },
  computed: {
    // show Ulists when inside the outfit pages or hide otherwise
    navHeaderViews: function(){
       return this.$store.state.navHeaderView;
     },
     // show register, log in links when false
     // show dashbaord, log out links when true
     isLoggedIn: function(){
       return this.$store.state.userInfo.isLoggedIn;
     },
     currentYear: function(){
       // get current year for footer
       var d = new Date();
       return d.getFullYear();
     },
     isLoggedInDashboard: function(){
       console.log(this.$store.state.admin.isLoggedInDashboard);
       return this.$store.state.admin.isLoggedInDashboard;
     }
  },
  methods: {

      },

}


</script>
<style>
.footer-container {
  min-height: 150px;
  width: 100%;
  margin-top: 350px;
  background-color: #213B50;
  padding: 30px 0;
  text-align: center;
}

.footer-uls {
  text-align: left;
  display: inline-block;
  list-style: none;
  vertical-align: top;
}

.footer-list {
  display: inline-block;
  list-style: none;
  vertical-align: top;
  margin: 0 0 0 160px;

}
.footer-list:nth-child(1) {
  margin: 0 0 0 0 !important;
}
.footer-nav-list {
  list-style: none;
  text-align: left !important;
  margin: 5px 0 0 0px;
  padding: 0;
}

.footer-nav-list-2 {
  margin: 5px 0 0 0;
}
.footer-list-header {
  background-color: #ffffff;
  color: #213B50;
  padding: 5px;
  margin-left: -5px;
  display: inline-block;
}
.footer-grey-text a, .footer-grey-text li a {
  color: #c8c8c8;
}
.footer-user-list li, .footer-nav-list li {
  margin: 10px 0 0 0;
}
.footer-user-list {
  margin: 5px 0 0 0;
  padding: 0;
}
.footer-user-list li {
  list-style: none;
}
.footer-logo-ul {
  float: left;
}
.footer-logo-ul li{
  margin-top: 60px;
}
.footer-logo-ul li:nth-child(1) {
  margin-top: 0;
}

#footerLogoSign {
     font-size: 17px;
     color: #213B50;
     background-color: #FFFFFF;
     padding: 10px 10px 6px 10px;

 }
.footer-saying {
  font-size: 18px;
}
.nav-sign-jobs {
  position: relative;
  display: inline-block;
  padding: 4px 4px 1px 4px;
}
.nav-sign-arrow-1 {
  position: absolute;
  top: -3px;
  left: 2%;
  color: #3490dc;
  font-size: 20px;
}
.nav-sign-arrow-2 {
  position: absolute;
  bottom: -6px;
  left: 66%;
  color: #3490dc;
  font-size: 20px;
}
.about-li {
  margin: 10px 0 0 0;
}
.footer-copy {
  margin: 100px 0 0 35px;
  color: #b5b4b4;
  text-align: left;
}

@media only screen and (max-width: 1065px){
  .footer-container {
    width: 100%;
    margin-top: 350px;
    text-align: center;

  }
  .footer-logo-ul {
    float: none;
    margin-left: 35px;
  }
  .footer-uls {
    text-align: center;
    margin-top: 50px;
    padding: 0;
    display: block;
    list-style: none;
    vertical-align: top;
  }
  .footer-uls:nth-child(1){
    text-align: left;
    margin-top: 0;
    margin-left: 50px;
  }
  .footer-user-list {
    text-align: left;
    margin-left: 10px;
  }
  .footer-nav-list {
    list-style: none;
    text-align: left !important;
    margin: 5px 0 0 25px;
    padding: 0;
  }
  .footer-nav-list-2 {
    margin: 5px 0 0 0 !important;

  }
  .footer-copy {
    margin: 100px 0 0 35px;
    text-align: left;
    color: #b5b4b4;
  }
}
@media only screen and (max-width: 785px){
  .footer-uls {
    text-align: center;
    margin-left: 0;
    display: block;
  }

  .footer-logo-ul {
    text-align: center;
    margin-left: 0 !important;
  }
  .footer-list {
    text-align: center !important;
  }
  .footer-list-header {
    background-color: #ffffff;
    color: #213B50;
    padding: 5px;
    margin-left: 0;
    display: inline-block;
  }
  .footer-nav-list {
    margin: 5px 0 0 0;
    padding: 0;
  }
  .footer-uls:nth-child(1){
    text-align: center;
    margin-top: 0;
  }
  .footer-nav-list {
    list-style: none;
    text-align: center !important;
    margin: 5px 0 0 0;
    padding: 0;
  }

  .footer-list {
    text-align: center;
    display: block;
    list-style: none;
    vertical-align: top;
    margin: 30px 0 0 0;
  }

  .footer-user-list {
    text-align: center;
    margin: 5px 0 0 0 !important;
    padding: 0;
  }
  .footer-copy {
    margin: 100px 0 0 0;
    text-align: center;
    color: #b5b4b4;
  }
}
</style>
