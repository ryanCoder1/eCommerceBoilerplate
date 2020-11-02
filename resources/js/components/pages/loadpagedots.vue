

<template>
        <div class="page-loading-dots-container">
          <span class="text-center" v-if="loadingDots">
            <span class="page-circle-one" v-bind:class="[activeLoader ? pageLoaderMove : pageLoaderDots]"></span>
            <span class="page-circle-two" v-bind:class="[activeLoader ? pageLoaderMove : pageLoaderDots]"></span>
            <span class="page-circle-three" v-bind:class="[activeLoader ? pageLoaderMove : pageLoaderDots]"></span>
          </span>
        </div>
</template>



<script>

export default {

    props: {
      'loadingDots': Boolean,
      'activeLoader': Boolean,
    },
    data(){
      return{
        pageLoaderMove: 'page-loader-move',
        pageLoaderDots: 'page-loader-dots',
      }

  }


}


</script>
<style>

</style>
