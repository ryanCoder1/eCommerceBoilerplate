<template>
      <div class="info-size">
        <label>
          <div class="inputContainer">
            <p>
              <span class="labelStyle">Sizes</span>
              <select class="form-control formInput" v-on:change="sizeShowColors()" v-model="size">
                <option value="" selected disabled>Choose size</option>
                <option :value="size.dimension" v-for="(size, index) in sizes">{{ size.dimension }}</option>
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
