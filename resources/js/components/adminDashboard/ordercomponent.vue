<template>
     <div>
          <h4 class="text-secondary" v-if="!noDimensions">There haves to be dimensions to create the order.</h4>
            <form id="dashboardOrder" class="form" v-if="noDimensions">
                  <table class="dashboard-order-table">
                    <tr>
                      <th>Select order</th>
                    </tr>
                    <tr class="order-table-row" v-for="(dimensionName, indexName) in dimensionsNum">
                      <td>
                        <p>
                           <select
                           v-on:change="addOrder($event, dimensionName.dimension, indexName)"
                           >
                           <!-- option is the order_number returned from DB table -->
                             <option value="">{{ dimensionName.order_number }}</option>
                           <!-- option value is the number of dimensions in DB as in the index + 1 -->
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

                     <div class="dashboard-page-btn" v-if="showSave" v-on:click="saveDimensions()">
                       <input type="button"   value="Save Order" v-if="!loading"/>
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
     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();
     setTimeout(() => {
       this.templateHeight();
     },500);
  },
  methods: {
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardOrderContainer').offsetHeight;
      this.sideBarHeight(elem);
      console.log(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    addOrder: function(event, name, index){
      this.errors = null;
      this.success = null;
        // target the selectedIndex(new option value chosen)
        let selIndex = event.target.options.selectedIndex;
        // replace dimensions order_number with selIndex value;
        this.dimensionsNum[index].order_number = selIndex;
        // show save btn since a change was made
        this.showSave = true;

    },
    createDimensionNumArray: function(){
      // push elements into array. The same number as this.dimensions
      for(let i = 0; i < this.dimensions.length; i++){
        this.dimensionsNum.push({
                  dimension: this.dimensions[i].dimension,
               order_number: this.dimensions[i].order_number,
            });
      }
    },
      saveDimensions: function(){
          // for scope of this within axios
          let self = this;
          this.loading = true;

          let fd = new FormData();
          for(let i = 0; i < this.dimensions.length; i++){
            fd.append('order_number' + i ,this.dimensionsNum[i].order_number)
            fd.append('dimension' + i ,this.dimensionsNum[i].dimension)
          }

              fd.append('count',this.dimensionsNum.length)
              fd.append('Authorization', this.$store.state.admin.currentAdmin.token)

              axios.post('/dimensionsorderupdate', fd,
            )
             .then((res) => {
               if(res.data.success){
                  self.dimensionsNum = [];
                  self.loading = false;
                  self.success = 'Dimensions order was saved successfully!';
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
  .dashboard-order-table th {
    padding: 15px 0 0 0;
    font-size: 23px;
    color: #213B50;
    text-transform: capitalize;
  }
  .order-table-row {
    border-bottom: solid thin #e3e1e1;
  }
  .dashboard-order-table td {
    /* border: solid thin #ccc; */
    padding: 0 20px;
    height: 50px;

  }
  .dashboard-order-table td:last-child {
    /* border: solid thin #ccc; */
    padding: 25px 0 0 0;
    height: 50px;

  }
  .dashboard-order-table td:first-child {
    /* border: solid thin #ccc; */
    height: 50px;

  }
</style>
