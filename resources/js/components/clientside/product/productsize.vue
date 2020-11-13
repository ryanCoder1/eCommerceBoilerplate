<template>
      <div class="info-size">
        <label>
          <div class="inputContainer">
            <p>
              <span class="labelStyle">Sizes</span>
              <select class="form-control formInput product-size-select" v-on:change="sizeShowColors()" v-model="size" v-if="sizes.length">
                <option value="" selected disabled>Choose size</option>
                <option :value="size.dimension" v-for="(size, index) in sizes" :class="[!sizeInStock(size.dimension,index) ? 'size-option-display-none' : '']">{{ size.dimension }}</option>
              </select>

            </p>
         </div>
       </label>
    </div>
</template>


<script>



export default {
  props: {
    'productGroupsSize': Array,
  },
  components: {

  },
data(){
  return{
    sizes: [],
    size: '',
  }
},
mounted(){
  this.getProductDimensions();

},
methods: {
  sizeInStock: function(size,index){

          let count = 0;
          let sizeLC = size.toLowerCase();
          let dimensionsLC = null;
          let numDimension = null;
         // loop thru productGroupsSize for available sizes and inStock
       for(let i = 0; i < this.productGroupsSize.length; i++){
          dimensionsLC = this.productGroupsSize[i].dimension.toLowerCase();
         // if size from option not equal to loop dimension add count
         if(!dimensionsLC.startsWith(sizeLC)){
           count++;
           // if count is equal list about
           if(count == this.productGroupsSize.length){
               return false;
           }
         }else {
           numDimension = i;
           break;
         }
       }
       if(count < this.productGroupsSize.length){
          if(this.productGroupsSize[numDimension].in_stock == 0){
           let option = document.getElementsByClassName('product-size-select')[0];
            option[index + 1].disabled = true;
            option[index + 1].innerText = size + ' / out of stock';
            option[index + 1].style.background = "#dbd9d9";
           console.log(document.getElementsByClassName('product-size-select'));

         }
         return true;

       }

  },
  sizeShowColors: function(){
    this.$root.$emit('size', this.size);
  },
  getProductDimensions: function(){
      // for scope of this within axios
      let self = this;
      console.log('called');
        axios.post('/showproductdimensions')
         .then((res) => {
           if(res.data.success){

              self.sizes = res.data.dimensions;

            }

        }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Info Get', self);

        })
    },
}


}


</script>
<style>

</style>
