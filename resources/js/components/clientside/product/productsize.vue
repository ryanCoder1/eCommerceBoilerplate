<template>
      <div class="info-size">
        <label>
          <div class="inputContainer">
            <p>
              <span class="labelStyle">Sizes</span>
              <select class="form-control formInput" v-on:change="sizeShowColors()" v-model="size">
                <option value="" selected disabled>Choose size</option>
                <option :value="size.dimension" v-for="(size, index) in productGroupsSize">{{ size.dimension }}</option>
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
    size: '',
  }
},
methods: {
  sizeShowColors: function(){
    this.$root.$emit('size', this.size);
  }
}


}


</script>
<style>

</style>
