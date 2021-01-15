<template>
    <div :class="templateName + '-featured-slide-container'" v-if="noFeatured">
        <div :class="templateName + '-featured-slide-wrapper'" v-visibility-change="visibilityChange">

            <ul :class="templateName + '-featured-slide-ul'" >
              <li :class="templateName + '-featured-slide-li'" v-for="(feature, index) in featured" :key="index">
                <images
                  v-bind:feature="feature"
                  v-bind:feature-index="index"
                  >
                </images>
                <ul :class="templateName + '-featured-caption'">
                    <li
                      :class="[templateName + '-caption', templateName + '-caption-active']"
                      v-if="numberOfImgs == index + 1">
                        <span :class="templateName + '-featured-bg-title'"></span>
                        <span :class="templateName + '-featured-title'">{{ feature.title }}</span>
                        <span :class="templateName + '-featured-caption'">{{ feature.caption }}</span>
                    </li>
                </ul>
              </li>
            </ul>

            <ul :class="templateName + '-featured-bullets-ul'">
              <li :class="[templateName + '-featured-bullets', templateName +  '-featured-active']" v-for="(feature, index) in featured">{{ index }}</li>
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
      tempName: null,
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
      // send to parent Home for loader
      this.$root.$emit('featuredLoad', true);
      let self = this;

            // get the image list and set width
            setTimeout(function(){
              if(self.tempName){
                self.element = document.getElementsByClassName(self.tempName + "-featured-slide-li")[0];
                self.move = self.element.offsetWidth;
              }
            },200);
            setTimeout(function(){
              self.scrollInfoAuto();
              self.moveBullets();
            }, 220);
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
      let featuredBullets =  document.getElementsByClassName(this.tempName + '-featured-bullets');
      let featuredBulletsAl = document.querySelectorAll('.'+ this.tempName + '-featured-bullets.' + this.tempName + '-featured-active');

      for (let i = 0; i < this.featured.length; i++) {
        if(featuredBullets[i].classList.remove(this.tempName + '-featured-active') !== undefined){
           featuredBulletsAl[i].classList.remove(this.tempName + '-featured-active')
         }
       }
       if(this.numberOfBullets > this.featured.length - 1){
        this.numberOfBullets = 0;
        }
       featuredBullets[this.numberOfBullets].classList.add(this.tempName + '-featured-active');
       this.numberOfBullets++;
     },
    scrollInfoAuto: function(){

        let liCount =  document.getElementsByClassName(this.tempName + '-featured-slide-img').length;
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
      var elems = document.querySelectorAll('.' + this.tempName + '-featured-slide-img');
          [].forEach.call(elems, function(el) {
              el.classList.remove(this.tempName + '-featured-active-img');
          });
        document.getElementsByClassName(this.tempName + '-featured-slide-img')[numberOfImgs].classList.add(this.tempName + '-featured-active-img');
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
