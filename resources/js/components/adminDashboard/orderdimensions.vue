<template>
      <div id="dashboardOrderContainer" class="dashboard-page-containers">
         <h3>Order dimensions</h3>
          <h4 class="text-secondary" v-if="!noDimensions">There haves to be dimensions to create the order.</h4>
            <form id="dashboardOrder" class="form" v-if="noDimensions">
                  <table>
                    <tr>
                      <th>Select order</th>
                    </tr>
                    <tr v-for="(dimensionName, indexName) in dimensionsNum">
                      <td>
                        <p>
                           <select
                           v-on:change="addOrder($event, dimensionName.dimension, indexName)"
                           >
                             <option value="">{{ dimensionName.order_number }}</option>

                             <option :value="index + 1" v-for="(dimension, index) in dimensionsNum">{{ index + 1 }}</option>
                           </select>
                         </p>
                       </td>
                      <td>{{ dimensionName.dimension }}</td>
                    </tr>
                  </table>
                     <!-- Error/Success messages from api -->
                     <p class="bg-danger text-light p-2 ml-4 d-inline" v-if="errors != null">{{ errors }}</p>
                     <p class="bg-success text-light p-2 my-2 ml-4 d-inline" v-if="success != null">{{ success }}</p>

                    <p class="dashboard-page-btn" v-on:click="saveGroups()">
                      <input type="button" value="Save Product Info" v-if="!loading"/>
                      <load-dots
                       v-if="loading"
                       v-bind:loading-dots="loadingDots"
                       >
                      </load-dots>
                    </p>
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
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
      }

  },
  mounted(){

    // retrieve dimensions
     this.getDimensions();
     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();

  },
  watch: {
    noProduct: function(newNoProduct, oldNoProduct){
      // run function to get height of template to send to parent sidebarmenu
      if(newNoProduct){
        let self = this;
        setTimeout(function(){
           self.templateHeight();
        }, 500);

      }
    }
  },
  methods: {
    handleFile: function(){

    },
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    clearErrors: function(){
      this.errors = null;
      this.success = null;
    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardProductsGroupsContainer').offsetHeight;
      this.sideBarHeight(elem);
      console.log(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    addOrder: function(event, name, index){
      console.log(name + ' ' + this.dimension_name );
      let selIndex = event.target.options.selectedIndex;
      console.log(index);
        this.dimensions[index].order_number = selIndex;
        console.log(this.dimensions);
    },
    createDimensionNumArray: function(){
      // push elements into array. The same number as this.dimensions
      for(let i = 0; i < this.dimensions.length; i++){
        this.dimensionsNum.push({
                  dimension: this.dimensions[i].dimension,
          order_number: this.dimensions[i].order_number,
            });
      }
      console.log(this.dimensionsNum);
    },
      saveGroups: function(){

        if(!this.validation()){
            return false;
          }
          // for scope of this within axios
          let self = this;
          this.loading = true;

          let fd = new FormData();
              fd.append('file',this.image_file)

              fd.append('Authorization', this.$store.state.admin.currentAdmin.token)

              axios.post('/productgroups')
             .then((res) => {
                console.log(res);
               if(res.data.success){

                  this.refreshFields();
                  self.loading = false;
                  self.success = 'Product Group was saved successfully!';
                  self.getDimensions();
             }
            }).catch((error) => {
              console.log(error);
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
                      self.createDimensionNumArray();
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

</style>
