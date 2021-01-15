<template>
  <div >
    <div :class="templateName + '-about-client-container'">
      <h3 :class="templateName + '-about-client-head'">About</h3>
      <p :class="templateName + '-about-client-body'" v-if="about.info != 'null'">{{ about.info }}</p>
    </div>
  </div>
</template>

<script>
  export default {
    data(){
      return{
        about: [],

      }
    },
  created () {

  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll);
  },
    mounted() {
      this.showAbout();
      console.log('in about');
    },
    watch:{
      about: function(newAbout, oldAbout){
        if(newAbout){
            window.addEventListener('scroll', this.handleScroll);
        }
      }
    },
    computed: {
      templateName: function(){
        if(this.$store.state.templateView){
            this.tempName = this.$store.state.templateView;
           return this.$store.state.templateView;
         }
      }
    },
    methods: {
      handleScroll: function(){
          this.aboutAnimate();

      },
      aboutAnimate: function(){

          let elemt = document.getElementsByClassName(this.tempName + '-about-client-body')[0];
          let elemtInfo = document.getElementsByClassName(this.tempName + '-about-client-head')[0];
          let elemtTop = elemt.offsetHeight + 150;

          if(window.pageYOffset > elemtTop){
              elemt.classList.add(this.tempName + '-about-client-body-move');
              elemtInfo.classList.add(this.tempName + '-about-client-head-move');
          }

      },
      showAbout: function(){
          // for scope of this within axios
          let self = this;
            axios.post('/aboutshowclient'
              )
             .then((res) => {
              if(res.data.about.length && res.data.about !== undefined){
                console.log(res.data.about);
                self.about = res.data.about[0];
              }

            }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'About Info Get', self);

            })
        },
    }
  }
</script>

<style lang="css">
.about-client-container {
  max-width: 600px;
  width: 100%;
  min-height: 500px;
  text-align: center;
  position: relative;
  padding: 200px 0 0 0;
  margin: 0 auto;
}
.about-client-head {
  color: #213B50;
  transform: translateY(50px);
  opacity: 0;
}
.about-client-head-move {
  animation: moveAboutHead 1s ease-in-out 0s forwards;
}
@keyframes moveAboutHead {
  100% {
    transform: translateY(0px);
    opacity: 1;
  }
}
.about-client-body {

  font-size: 18px;
  color: rgb(113, 113, 113);
  transform: translateY(75px);
  opacity: 0;
}
.about-client-body-move {
  animation: moveAboutBody 1.4s ease-in-out 0s forwards;
}
@keyframes moveAboutBody {
  100% {
    transform: translateY(0px);
    opacity: 1;
  }
}
</style>
