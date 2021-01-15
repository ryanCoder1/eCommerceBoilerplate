<template>
  <div :class="templateName + '-product-add-wrapper'">
    <!-- add to cart btn -->
    <div >
      <div :class="templateName + '-product-page-btn'">
        <input type="button"  value="Add to cart"/>
     </div>
    </div>
  </div>
</template>


<script>



export default {

  props: {

  },
data(){
  return{

  }
},
computed: {
  templateName: function(){
    if(this.$store.state.templateView){
       return this.$store.state.templateView;
     }
  }
},

}


</script>
<style>

</style>
