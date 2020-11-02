<template>
    <transition  name="fade" appear>
      <div class="product-edit-details-container">

        <h5 class="product-edit-details-head">Info <span class="details-x" v-on:click="closeComponent()">X</span></h5>
            <ul class="product-edit-details">
              <li>
                <div class="index-text index-text-details" v-if="editDetails" >Edited!</div>
                <textarea
                  v-if="!editDetails"
                  :value="product.details == 'null' ? '' : product.details"
                  ref="textarea">
                </textarea>
                  <div class="dashboard-edit-btn my-2" v-on:click="productEditDetails(product.id)" v-if="!editDetails">
                    <input type="button" value="Edit Details" v-if="!loading"/>
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
        details: null,
        editDetails: false,
        loading: false,
        loadingDots: true,
      }
  },
  methods: {
    editDetailsHandle: function(){
        let self = this;
        setTimeout(function(){
          self.editDetails = false;
          self.closeComponent();
        },2000);
    },
    productEditDetails: function(id){
      let details = this.$refs.textarea.value;
      this.product.details = details;
      // for scope of this within axios
      let self = this;
        this.loading = true;
        axios.post('/producteditdetails',
        {
          Authorization:  this.$store.state.admin.currentAdmin.token,
          productId: id,
          details: details },
          )
         .then((res) => {
           if(res.data.success){
             self.editDetails = true;
             self.editDetailsHandle();
             self.loading = false;
            }
        }).catch((error) => {
          // error handling function with error, name axios is used for, and this.
         errorHandle(error, 'Product edit details', self);
      })
    },
      closeComponent: function(){
        this.$root.$emit('closeDetail', false);
      },

  }


}


</script>
<style scoped>

.product-edit-details-container {
  text-align: center;
  position: absolute;
  top: 0px;
  left: 0;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
}
.product-edit-details-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.details-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.product-edit-details {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-details li {
  width: 300px;
  padding: 0;
  margin: 20px 10px;
}
.product-edit-details textarea {
  width: 100%;
  height: 200px;

}
.details-name {
  display: inline;
  background-color: #f2f2f2;
  padding: 5px 10px 5px 5px;

}
.details-output {
  padding: 10px 0;
  text-align: center;
  border-bottom: solid thin #f2f2f2;
  margin-bottom: 10px;
}
.index-text-details {
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
