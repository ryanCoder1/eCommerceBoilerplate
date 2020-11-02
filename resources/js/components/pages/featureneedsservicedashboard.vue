<template>
      <div>
        <h3 class="page-not-found-header text-center text-secondary mx-3" v-if="storeNotice">
           There is an error!<br/><br/>
           The feature<span v-if="featureNeedsService.length > 1">s</span>
           <span class="text-primary" v-for="(feature, index) in featureNeedsService">
             {{ feature }}<span v-if="featureNeedsService.length > 1 && index < featureNeedsService.length - 1">, </span></span>
               <span v-if="featureNeedsService.length > 1">
                 are</span>
                 <span v-else>is</span>
                  being reported to tech support for the error<span v-if="featureNeedsService.length > 1">s</span>.
                   <br/><br/>
           The response will be quick in fixing the problem.
         </h3>
         <h3 class="page-not-found-header text-center text-secondary mx-3"  v-if="!storeNotice">
            You have visited this page without the proper error notification alert.<br/><br/>
            Errors, if necessary, will show during working with features.
         </h3>
      </div>
</template>



<script>


export default {

  data(){
    return{

    }
  },
 computed: {
   storeNotice: function(){
     console.log(this.$store.state.userErrors.storeNotice);
     return this.$store.state.userErrors.storeNotice;
   },
   featureNeedsService: function(){
     return this.$store.state.userErrors.featureNeedsService;
   }
 }
}


</script>
<style>
.page-not-found-header {
  margin: 150px auto;
}
</style>
