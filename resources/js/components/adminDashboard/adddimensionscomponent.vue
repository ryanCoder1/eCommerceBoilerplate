<template>
     <div>

            <form id="dashboardAdd" class="form" v-if="noDimensions">
              <table class="dashboard-dimension-add-table">
                <tr>
                  <th>Dimension to Add</th>
                </tr>
                <tr class="dimension-add-table-row" >
                  <td>
                    <!-- select field to show available dimensions -->
                    <label class="col-12 mx-auto" v-if="noDimensions">
                      <div class="inputContainer">
                        <p>
                          <select class="form-control formInput">
                          <option value="" selected disabled>Available dimensions</option>
                          <option v-for="(dimension, index) in dimensions">{{ dimension.dimension }}</option>
                           </select>
                          <span class="labelStyle">Dimensions</span>
                         </p>
                        </div>
                       </label>
                   </td>
                   </tr>
                    <tr>
                    <td>
                      <!-- input to add new dimension -->
                       <label class="col-xs-12 col-sm-12">
                         <div class="inputContainer">
                           <p>
                             <input class="form-control" type="text" v-on:keyup="showSaveHide()" v-model="dimension"/>
                             <span class="labelStyle" >Dimensions (This field will save for future dimension options) (if necessary)</span>
                           </p>
                         </div>
                       </label>
                      </td>
                    </tr>
                  </table>

                   <!-- Error/Success messages from api -->
                   <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                   <p class="success-msg" v-if="success != null">{{ success }}</p>

                   <div class="dashboard-page-btn" v-if="showSave" v-on:click="saveDimensions()">
                     <input type="button"   value="Save" v-if="!loading"/>
                     <load-dots
                      v-if="loading"
                      v-bind:loading-dots="loadingDots"
                      >
                    </load-dots>
                  </div>
             </form>
        </div>
</template>



<script>

import LoadDots from '../pages/loaddots.vue';
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
        noDimensions: false,
        dimensions: [],
        dimension: null,
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        showSave: false,
      }

  },
  mounted(){

    // retrieve dimensions
     this.getDimensions();

       setTimeout(() => {
       this.templateHeight();
     },500);
  },
  methods: {
    templateHeight: function(){
      var elem = document.getElementById('dashboardAddContainer').offsetHeight;
      this.sideBarHeight(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    showSaveHide: function(){
      this.success = null;
      this.errors = null;
      if(this.dimension.length > 0){
        this.showSave = true;
      }
      else{
        this.showSave = false;
      }
    },
      saveDimensions: function(){
          // for scope of this within axios
          let self = this;
          this.loading = true;

              axios.post('/dimensionstore',
              {
                Authorization: self.$store.state.admin.currentAdmin.token,
                dimension: self.dimension,
              }
            )
             .then((res) => {
               if(res.data.success){
                  self.dimension = null;
                  self.showSave = false;
                  self.loading = false;
                  self.success = 'Dimension was added successfully!';
                  self.getDimensions();
             }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Groups Save', self);
            })
        },
          getDimensions: function(){
              // for scope of this within axios
              let self = this;

                axios.post('/dimensionsretrieve',
                {
                  Authorization:  this.$store.state.admin.currentAdmin.token
                },
                  )
                 .then((res) => {
                   if(res.data.dimensions != undefined && res.data.dimensions.length ){
                      self.dimensions = res.data.dimensions;
                      self.noDimensions = true;
                      self.createDimensionDeleteArray();
                    }else{
                      self.noDimensions = false;
                    }

                }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Product Get Dimensions', self);
                })
            }
    }


}


</script>
<style>

.dashboard-dimension-add-table  {
    width: 100%;
    margin-bottom: 30px;
}
  .dashboard-dimension-add-table th {
    padding: 15px 0 0 0;
    font-size: 23px;
    color: #213B50;
    text-transform: capitalize;
  }



</style>
