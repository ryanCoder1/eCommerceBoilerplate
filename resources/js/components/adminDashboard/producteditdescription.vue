<template>
    <transition  name="fade" appear>
      <div class="product-edit-description-container">

        <h5 class="product-edit-description-head">Info <span class="description-x" v-on:click="closeComponent()">X</span></h5>
            <ul class="product-edit-description">
              <li>
                <div class="index-text index-text-description" v-if="editDescription" >Edited!</div>
                <textarea
                v-if="!editDescription"
                :value="product.description == 'null' ? '' : product.description"
                ref="textarea">
                </textarea>
                  <div class="dashboard-edit-btn my-2" v-on:click="productEditDescription(product.id)" v-if="!editDescription">
                    <input type="button"   value="Edit Description" v-if="!loading"/>
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
      'product': Object,
    },
    data(){
      return{
        description: null,
        editDescription: false,
        loading: false,
        loadingDots: true,
      }
  },
  methods: {
    editDescriptionHandle: function(){
        let self = this;
        setTimeout(function(){
          self.editDescription = false;
          self.closeComponent();
        },2000);
    },
    productEditDescription: function(id){
      this.loading = true;
      let description = this.$refs.textarea.value;
      this.product.description = description;
      // for scope of this within axios
      let self = this;

        axios.post('/producteditdescription',
        {
          Authorization:  this.$store.state.admin.currentAdmin.token,
          productId: id,
          description: description
        },
          )
         .then((res) => {
           if(res.data.success){
             self.editDescription = true;
             self.editDescriptionHandle();
             self.loading = false;
            }
        }).catch((error) => {
          // error handling function with error, name axios is used for, and this.
         errorHandle(error, 'Product edit description', self);
      })
    },
      closeComponent: function(){
        this.$root.$emit('closeDescription', false);
      }


  }


}


</script>
<style scoped>

.product-edit-description-container {
  text-align: center;
  position: absolute;
  top: 0px;
  left: 0;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
}
.product-edit-description-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.description-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.product-edit-description {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-description li {
  width: 300px;
  padding: 0;
  margin: 20px 10px;
}
.product-edit-description textarea {
  width: 100%;
  height: 200px;

}
.description-name {
  display: inline;
  background-color: #f2f2f2;
  padding: 5px 10px 5px 5px;

}
.description-output {
  padding: 10px 0;
  text-align: center;
  border-bottom: solid thin #f2f2f2;
  margin-bottom: 10px;
}
.index-text-description {
  height: 200px;
  padding-top: 75px;

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
