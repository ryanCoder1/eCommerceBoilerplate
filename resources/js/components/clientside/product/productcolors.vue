<template>
      <div :class="templateName + '-info-colors'">
          <div
          :class="templateName + '-colors-bg-color-hex' "
          v-bind:style="[group.color != 'null' ? {backgroundColor: group.color} : {display: 'none'} ]"
          v-for="(group, index) in groupColor" :key="index"
          v-on:click="colorChoose(index, group.in_stock)">
          <span
          :class="templateName + '-colors-out-of-stock-stripe'"
          v-if="group.in_stock == 0"
          v-on:mouseover="showOutOfStock()">
          </span>
          </div>
          <span
          :class="templateName + '-colors-out-of-stock'"
          v-if="outOfStock">
          Out of stock
          </span>
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
    outOfStock: false,
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
computed: {
  templateName: function(){
    if(this.$store.state.templateView){
       return this.$store.state.templateView;
     }
  }
},
methods: {
  showOutOfStock: function(){
    this.outOfStock = true;
    setTimeout(() => {
      this.outOfStock = false;
    }, 1500);
  },
  colorChoose: function(index, in_stock){
    if(in_stock != 0){
      this.$root.$emit('colorClicked', this.groupColor[index]);
    }
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
