<template>
    <transition  name="fade" appear>
      <div class="slider-featured-image-container slider-featured-child-container">

        <h6 class="slider-featured-image-head">Slider Featured Images <span class="image-x" v-on:click="closeComponent()">X</span></h6>
        <h6 class="slider-featured-image-none mt-5 mx-2 text-secondary" v-if="!features.length">There are no images on file</h6>
            <ul class="slider-featured-image">
              <li v-for="(image, index) in features" :key="index">
                  <div class="index-text index-text-images" v-if="indexDelete == index">{{ image }}</div>
                  <img v-if="indexDelete != index" :src="'../storage/images/' + image.image_path + '/' + image.image_name"></img>
               </li>
            </ul>
      </div>
   </transition>
</template>



<script>
import { errorHandle } from '../../helper/errors';
export default {

    props: {
      'features': Array,
    },
    data(){
      return{
        loading: false,
        loadingDots: true,
        indexDelete: null,
      }
  },
  methods: {
    closeComponent: function(){
      this.$root.$emit('closeImages', false);
    },

  }


}


</script>
<style scoped>
.slider-featured-image-container {
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  z-index: 100;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 20px 1px rgba(0,0,0, .4);
  transform: translate(-50%, -50%);
}
.slider-featured-image-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.image-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.slider-featured-image {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.slider-featured-image li {
  width: 300px;
  padding: 0;
  margin: 0 10px 10px 10px;
}
.slider-featured-image img {
  width: 100%;
  height: 175px;
  margin: 0;
  padding: 0;
  background-color: rgb(255, 255, 255);

}
.index-text-images {
  height: 200px;
  padding-top: 150px;

 }
.slider-featured-image-none {
  width: 280px;
}
/* TRANSITION INTO COMPONENT */
.fade-enter-active{
     transition: opacity .5s;
 }
 .fade-leave-active {
     opacity: 0;
 }
 .fade-enter, .fade-leave-to {
     opacity: 0;
 }
</style>
