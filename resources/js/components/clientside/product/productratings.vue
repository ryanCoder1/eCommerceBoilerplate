<template>
      <div :class="templateName + '-info-ratings'">
        <span>ratings</span>
      </div>
</template>


<script>



export default {
  components: {

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
