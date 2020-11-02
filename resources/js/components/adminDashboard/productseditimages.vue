<template>
    <transition  name="fade" appear>
      <div class="product-edit-image-container product-edit-child-container">

        <h5 class="product-edit-image-head">Images <span class="image-x" v-on:click="closeComponent()">X</span></h5>
        <h5 class="product-edit-image-none mt-5 mx-2 text-secondary" v-if="!productImages.length">There are no images on file</h5>
            <ul class="product-edit-image">
              <li v-for="(image, index) in productImages" :key="index">
                  <div class="index-text index-text-images" v-if="indexDelete == index">{{ image }}</div>
                  <img v-if="indexDelete != index" :src="'../storage/images/' + image.image_path + '/' + image.image_name" alt="product image">
                  <div  v-if="indexDelete != index" class="dashboard-edit-btn my-2" v-on:click="productDeleteImage(index)">
                    <input type="button"   value="Delete image" v-if="!loading"/>
                    <load-dots
                     v-if="loading"
                     v-bind:loading-dots="loadingDots"
                     >
                   </load-dots>
                 </div>
               </li>
            </ul>
      </div>
   </transition>
</template>



<script>

import LoadDots from '../pages/loaddots.vue';
import { errorHandle } from '../../helper/errors';
export default {
    components: {
      'load-dots': LoadDots,
    },
    props: {
      'productImages': Array,
    },
    data(){
      return{
        loading: false,
        loadingDots: true,
        indexDelete: null,
      }
  },
  methods: {
    deleteImageHandle: function(index){
        this.productImages.splice(index, 1, "Deleted!");
        this.indexDelete = index;
        let self = this;
        setTimeout(function(){
          self.productImages.splice(index, 1);
          self.indexDelete = null;
        },2000);
    },
    closeComponent: function(){
      this.$root.$emit('closeImages', false);
    },
    productDeleteImage: function(index){
        // for scope of this within axios
        let self = this;
          this.loading = true;
          let image = this.productImages[index];
          axios.post('/productdeleteimage', {Authorization:  this.$store.state.admin.currentAdmin.token, imageId: image},
            )
           .then((res) => {
             console.log(res);
             if(res.data.deleteimage){
                self.deleteImageHandle(index);
                self.loading = false;

              }

          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'Product Delete Image', self);
          })
      },
  }


}


</script>
<style>
.product-edit-image-container {
  text-align: center;
  position: absolute;
  top: -15px;
  left: 20%;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 20px 1px rgba(0,0,0, .4);
}
.product-edit-image-head {
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
.product-edit-image {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-image li {
  width: 300px;
  padding: 0;
  margin: 0 10px 10px 10px;
}
.product-edit-image img {
  width: 100%;
  height: 200px;
  margin: 0;
  padding: 0;
  background-color: rgb(255, 255, 255);

}
.index-text-images {
  height: 200px;
  padding-top: 150px;

 }
.product-edit-image-none {
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
