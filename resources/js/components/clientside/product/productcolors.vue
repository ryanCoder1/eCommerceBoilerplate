<template>
      <div class="info-colors">
          <div
          class="slide-bg-color-hex "
          v-bind:style="[group.color != 'null' ? {backgroundColor: group.color} : {display: 'none'} ]"
          v-for="(group, index) in groupColor" :key="index"
          v-on:click="colorChoose(index)">
          </div>
        </div>
      </div>
</template>


<script>



export default {
  props: {
    'size': String,
    'productId': Number,
  },
  components: {

  },
data(){
  return{
    groupColor: [],
  }
},
watch: {
  // watch any change to size prop to call method
  size: function(newSize, oldSize){
    if(newSize){
      this.showGroupColor();
    }
  }
},
methods: {
  colorChoose: function(index){
      this.$root.$emit('colorClicked', this.groupColor[index]);
  },
  showGroupColor: function(){
      // for scope of this within axios
      let self = this;
        axios.post('/showgroupcolor',
        {
          size: self.size,
          product_id: self.productId,
        }
        )
         .then((res) => {
           if(res.data.success){
             console.log(res.data);
              self.groupColor = res.data.product_styles_color;

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
