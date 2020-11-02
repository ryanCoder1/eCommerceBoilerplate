<template>
    <div id="specialsSlideContainer" v-if="noSpecials">
        <div class="specials-client-head">Product Specials</div>
        <div id="specialsSlideWrapper" class="inline" v-visibility-change="visibilityChange">

            <ul class="specials-slide-ul" >
              <li class="specials-slide-li" v-for="(special, index) in specials" :key="index">
                <products
                  v-bind:special="special"
                  v-bind:groups="groups"
                  v-bind:special-index="index"
                  >
                </products>
              </li>
            </ul>

            <ul id="specialsBullets">
              <li class="specials-bullets special-active" v-for="(special, index) in specials">{{ index }}</li>
            </ul>

        </div>
   </div>
</template>



<script>

import Images from './specialsimages.vue';
import Products from './specialsproduct.vue';

export default {
  components: {
    'images': Images,
    'products': Products,
  },
  data(){
    return{
      noSpecials: false,
      specials: [],
      groups: [],
      numberOfImgs: 1,
      numberOfBullets: 0,
      element: '',
      move: 0,
      outerTime: 0,
    }
  },
  mounted(){
    this.getSpecials();

  },
  created() {
    window.addEventListener("resize", this.myEventHandler);
  },
  destroyed() {
    window.removeEventListener("resize", this.myEventHandler);
    window.clearInterval(this.outerTime);
  },
  watch: {
    noSpecials: function(newSpec, oldSpec){
        // when noSpecials = true, start setting the variables and running slideshow.
      if(newSpec){

        let self = this;
        setTimeout(function(){
          self.element = document.getElementsByClassName("specials-slide-li")[0];
          self.move = self.element.offsetWidth;
        },1200);
        setTimeout(function(){
          self.scrollInfoAuto();
          self.moveBullets();
        }, 1500);
      }
    }
  },
  methods: {
    visibilityChange(evt, hidden) {
        if(hidden === false){
        // when return to tab
        }else{
        // when leave tab
        }
      },

     moveBullets: function(){
      let bullets =  document.getElementsByClassName('specials-bullets');
      let bulletsAl = document.querySelectorAll('.specials-bullets.special-active');

      for (let i = 0; i < this.specials.length; i++) {
        if(bullets[i].classList.remove('special-active') !== undefined){
           bulletsAl[i].classList.remove('special-active')
         }
       }
       if(this.numberOfBullets > this.specials.length - 1){
        this.numberOfBullets = 0;
        }
       bullets[this.numberOfBullets].classList.add('special-active');
       this.numberOfBullets++;
     },
     scrollInfoAuto: function(){

         let liCount =  document.getElementsByClassName("info-img").length;
         let self = this;
       this.outerTime = setInterval(function(){

           self.moveBullets();
           // multiply the image number to calculated image width and marginLeft - moveTimes
           // else reset marginLeft = 0 and start over numberOfImgs count
           let moveTimes = self.move * self.numberOfImgs;

           if(self.numberOfImgs <= liCount - 1){
              self.element.style.marginLeft = "-" + moveTimes + "px";
              self.element.style.transitionDuration = '1.6s';
              self.numberOfImgs++;
          }else{
              self.element.style.marginLeft = "0px";
              self.element.style.transitionDuration = '0s';
              self.numberOfImgs = 1;
          }
       }, 5000);
   },
    addRemoveClassActiveImg: function(numberOfImgs){
      var elems = document.querySelectorAll(".info-img");
          [].forEach.call(elems, function(el) {
              el.classList.remove("activeImg");
          });
        document.getElementsByClassName("info-img")[numberOfImgs].classList.add('activeImg');
    },
    getSpecials: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/specialsshowclient'
          )
           .then((res) => {

             if(res.data.specials.length && res.data.specials !== undefined){
                self.specials = res.data.specials;
                self.groups = res.data.groups;
                self.noSpecials = true;
              }else {
                self.noSpecials = false;
              }
          }).catch((error) => {

            if(error.response.status === 401){
               self.$router.push('/admin/logout');
             }
             if(error.response.status == 500){
                self.$store.dispatch('userErrors/featureNeedsService', 'Slider Specials Saves');
                self.$router.push('/featureneedsservicedashboard');
              }
          })
      },
    }

}


</script>
<style>
/*
// Info slide slide show
*/


</style>
