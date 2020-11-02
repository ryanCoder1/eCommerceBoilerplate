<template>
  <transition  name="fade" appear>
    <div class="product-edit-group-container product-edit-child-container">

      <h5 class="product-edit-group-head">Groups <span class="group-x" v-on:click="closeComponent()">X</span></h5>
        <h5 class="product-edit-group-none mt-5 mx-2 text-secondary" v-if="!productStyles.length">There are no groups on file</h5>
          <ul class="product-edit-group">
            <li v-for="(group, index) in productStyles">
                <div class="index-text index-text-groups" v-if="indexDelete == index">{{ group }}</div>
                <div v-if="indexDelete != index">
                  <div class="group-name">Item Color</div>
                  <div class="group-bg-color-hex group-output" v-bind:style="{backgroundColor: group.color }"></div>
                  <div class="group-name">Dimension</div>
                  <div class="group-output">{{ group.dimension }}</div>
                  <div class="group-name">Price</div>
                  <div class="group-output">{{ group.price }}</div>
                  <div class="group-name" v-if="group.sale_price != null">Sale Price</div>
                  <div class="group-output" v-if="group.sale_price != null">{{ group.sale_price }}</div>
                  <div class="group-name">In Stock</div>
                  <div class="group-output">
                    <span  v-if="group.in_stock != null">{{ group.in_stock }}</span>
                    <span v-else>N/A</span>
                  </div>
                  <div class="group-name">Image</div>
                  <div class="group-image" v-if="group.image_name != null">
                      <img :src="'../storage/images/' + group.image_path + '/' + group.image_name" alt="product image">
                  </div>
                  <div class="dashboard-edit-btn my-2" v-on:click="productDeleteGroup(index, group.id)">
                    <input type="button"   value="Delete group" v-if="!loading"/>
                    <load-dots
                     v-if="loading"
                     v-bind:loading-dots="loadingDots"
                     >
                   </load-dots>
                 </div>
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
      'productStyles': Array,
    },

    data(){
      return{
        loading: false,
        loadingDots: true,
        indexDelete: null,
      }
  },
  methods: {
    deleteGroupHandle: function(index){
        this.productStyles.splice(index, 1, "Deleted!");
        this.indexDelete = index;
        let self = this;
        setTimeout(function(){
          self.productStyles.splice(index, 1);
          self.indexDelete = null;
        },2000);
    },
    closeComponent: function(){
      this.$root.$emit('closeGroups', false);
    },
    productDeleteGroup: function(index){
        // for scope of this within axios
        let self = this;
          this.loading = true;
          let group = this.productStyles[index];
          axios.post('/productdeletegroup',
          {
            Authorization:  this.$store.state.admin.currentAdmin.token,
            groupId: group
          },
            )
           .then((res) => {
             console.log(res);
             if(res.data.deletegroup){
                self.deleteGroupHandle(index);
                self.loading = false;
              }
          }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Delete Group', self);

          })
      },
  }


}


</script>
<style>
.product-edit-group-container {
  text-align: center;
  position: absolute;
  top: -35px;
  left: 20%;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
}
.product-edit-group-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.group-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.product-edit-group {
  height: 425px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-group li {
  width: 300px;
  padding: 0;
  margin: 20px 10px;
  border-bottom: solid 10px #213B50;
}
.product-edit-group img {
  width: 100%;
  height: 200px;
  margin: 0;
  padding: 0;
  background-color: rgb(255, 255, 255);

}
.group-name {
  display: inline;
  background-color: #f2f2f2;
  padding: 5px 10px 5px 5px;

}
.group-output {
  padding: 10px 0;
  text-align: center;
  border-bottom: solid thin #f2f2f2;
  margin-bottom: 10px;
}
.index-text-groups {
  height: 375px;
  padding-top: 325px;

}
.product-edit-group-none {
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
.group-bg-color-hex {
  width: 100%;
  height: 30px;
  margin: 15px 0;
  box-shadow: 0 0 1px 2px rgba(0,0,0, .7);
  border-bottom: none !important;
}
.group-image img {
  width: 100%;
  height: 230px;
  margin: 10px;
}
</style>
