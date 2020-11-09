<template>
     <div>
          <h4 class="text-secondary" v-if="!noDimensions">There haves to be dimensions to create the dimension-delete.</h4>
            <form id="dashboardOrder" class="form" v-if="noDimensions">
                  <table class="dashboard-dimension-delete-table">
                    <tr>
                      <th>Dimension to Delete</th>
                    </tr>
                    <tr class="dimension-delete-table-row" v-for="(dimension, index) in dimensions">
                      <td>
                        <div>
                          <input class="dimension-checkbox" type="checkbox" :ref="'dimension' + index" v-on:change="addDimensionDelete(index, dimension.id)"/>
                        </div>
                      </td>
                      <td>{{ dimension.dimension }}</td>
                    </tr>
                  </table>
                  <!-- Error/Success messages from api -->
                  <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                  <p class="success-msg" v-if="success != null">{{ success }}</p>


                     <div class="dashboard-page-btn" v-if="showSave" v-on:click="saveDimensions()">
                       <input type="button"   value="Delete" v-if="!loading"/>
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
        dimension_name: null,
        dimensionsNum: [],
        dimensionsDelete: [],
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
      var elem = document.getElementById('dashboardDeleteContainer').offsetHeight;
      this.sideBarHeight(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    addDimensionDelete: function(index, id){
      // the product is checked(index)
         if(this.$refs['dimension' + index][0].checked){
              this.dimensionsDelete[index].delete = true;
              this.showSave = true;
          }
          else{
             this.dimensionsDelete[index].delete = false;
             this.removeShowSave();
          }

    },
    removeShowSave: function(){
      let count = 0;
      for(let i = 0; i < this.dimensionsDelete.length; i++){
          if(this.dimensionsDelete[i].delete == false){
              count++;
              if(count == this.dimensionsDelete.length){
                this.showSave = false;
              }
          }
        }
    },
    createDimensionDeleteArray: function(){
      // push elements into array. The same number as this.dimensions
      for(let i = 0; i < this.dimensions.length; i++){
        this.dimensionsDelete.push({
               dimension: this.dimensions[i].dimension,
                  delete: false,
            });
      }
    },
      saveDimensions: function(){
          // for scope of this within axios
          let self = this;
          this.loading = true;

          let fd = new FormData();
          for(let i = 0; i < this.dimensionsDelete.length; i++){
            fd.append('dimension_delete' + i ,this.dimensionsDelete[i].delete)
            fd.append('dimension' + i ,this.dimensionsDelete[i].dimension)
          }

              fd.append('count',this.dimensionsDelete.length)
              fd.append('Authorization', this.$store.state.admin.currentAdmin.token)

              axios.post('/dimensionsdelete', fd,
            )
             .then((res) => {
               if(res.data.success){
                  self.dimensionsDelete = [];
                  self.$refs['dimension' + 0][0].checked = false;
                  self.showSave = false;
                  self.loading = false;
                  self.success = 'Dimensions were deleted successfully!';
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
.dashboard-dimension-delete-table  {
    max-width: 300px;
    margin-bottom: 30px;
}
  .dashboard-dimension-delete-table th {
    padding: 15px 0 0 0;
    font-size: 23px;
    color: #213B50;
    text-transform: capitalize;
  }
  .dimension-delete-table-row {
    border-bottom: solid thin #e3e1e1;
  }
  .dashboard-dimension-delete-table td {
    /* bdimension-delete: solid thin #ccc; */
    padding: 0 20px;
    height: 50px;

  }
  .dashboard-dimension-delete-table td:last-child {
    /* bdimension-delete: solid thin #ccc; */
    padding: 0;
    height: 50px;

  }
  .dashboard-dimension-delete-table td:first-child {
    /* bdimension-delete: solid thin #ccc; */
    height: 50px;
    max-width: 50px;

  }
</style>
