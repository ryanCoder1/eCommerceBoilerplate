

<template>
        <div class="loading-dots-container">
          <span class="text-center" v-if="loadingDots">
            <span class="circle-one loader-dots loader-dots-grey"></span>
            <span class="circle-two loader-dots loader-dots-grey"></span>
            <span class="circle-three loader-dots loader-dots-grey"></span>
          </span>
        </div>
</template>



<script>

export default {

    props: [
      'loadingDots',
    ],
    data(){
      return{

      }

  }


}


</script>
<style>
.loading-dots-container {
  margin-top: 0px;
}

</style>
