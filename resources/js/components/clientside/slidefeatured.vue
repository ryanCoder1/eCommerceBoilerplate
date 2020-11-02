<template>
    <div id="featuredSlideContainer" v-if="noFeatured">
        <div id="featuredSlideWrapper" v-visibility-change="visibilityChange">

            <ul id="featuredSlideUl" >
              <li class="featured-slide-li" v-for="(feature, index) in featured" :key="index">
                <images
                  v-bind:feature="feature"
                  v-bind:feature-index="index"
                  >
                </images>
                <ul class="featured-caption">
                    <li
                      class="caption captionActive"
                      v-if="numberOfImgs == index + 1">
                        <span class="featured-bg-title"></span>
                        <span class="featured-title">{{ feature.title }}</span>
                        <span class="featured-caption">{{ feature.caption }}</span>
                    </li>
                </ul>
              </li>
            </ul>

            <ul id="featuredBullets">
              <li class="featured-bullets featuredActive" v-for="(feature, index) in featured">{{ index }}</li>
            </ul>

        </div>
   </div>
</template>



<script>

import Images from './featuredimages.vue';


export default {
  components: {
    'images': Images,
  },
  data(){
    return{
      noFeatured: false,
      featured: [],
      groups: [],
      numberOfImgs: 1,
      numberOfBullets: 0,
      element: '',
      move: 0,
      outerTime: 0,
    }
  },
  mounted(){
    this.getFeatured();
  },
  created() {
  window.addEventListener("resize", this.myEventHandler);
},
destroyed() {
  window.removeEventListener("resize", this.myEventHandler);
  window.clearInterval(this.outerTime);
},
watch: {
  noFeatured: function(newFeat, oldFeat){
    // when noFeatured = true, start setting the variables and running slideshow.
    if(newFeat){
      console.log('in featured');
      // send to parent Home for loader
      this.$root.$emit('featuredLoad', true);
      let self = this;
      // get the image list and set width
      setTimeout(function(){
        self.element = document.getElementsByClassName("featured-slide-li")[0];
        self.move = self.element.offsetWidth;
      },100);
      setTimeout(function(){
        self.scrollInfoAuto();
        self.moveBullets();
      }, 120);
    }
  }
},
  methods: {
    myEventHandler: function(){
        this.move = this.element.offsetWidth;
    },
    visibilityChange(evt, hidden) {
        if(hidden === false){
        // when return to tab
        }else{
        // when leave tab
        }
      },
     moveBullets: function(){
      let featuredBullets =  document.getElementsByClassName('featured-bullets');
      let featuredBulletsAl = document.querySelectorAll('.featured-bullets.featuredActive');

      for (let i = 0; i < this.featured.length; i++) {
        if(featuredBullets[i].classList.remove('featuredActive') !== undefined){
           featuredBulletsAl[i].classList.remove('featuredActive')
         }
       }
       if(this.numberOfBullets > this.featured.length - 1){
        this.numberOfBullets = 0;
        }
       featuredBullets[this.numberOfBullets].classList.add('featuredActive');
       this.numberOfBullets++;
     },
    scrollInfoAuto: function(){

        let liCount =  document.getElementsByClassName("featured-slide-img").length;
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
             self.element.style.transitionDuration = '2.6s';
             self.numberOfImgs = 1;
         }
      }, 5000);
  },
    addRemoveClassActiveImg: function(numberOfImgs){
      var elems = document.querySelectorAll(".featured-slide-img");
          [].forEach.call(elems, function(el) {
              el.classList.remove("featuredActiveImg");
          });
        document.getElementsByClassName("featured-slide-img")[numberOfImgs].classList.add('featuredActiveImg');
    },
    getFeatured: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/featuredshowclient'
          )
           .then((res) => {

             if(res.data.featured.length && res.data.featured !== undefined){
                self.featured = res.data.featured;
                self.noFeatured = true;
              }else {
                self.noFeatured = false;
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
