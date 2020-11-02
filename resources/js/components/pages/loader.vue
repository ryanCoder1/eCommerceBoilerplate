<template >
  <div class="load-container">
    <!-- loader for home page -->
    <div class="load">
      <!-- <h3>eCommerce Mock</h3> -->
      <load-dots
        v-bind:loading-dots="loadingDots"
        v-bind:active-loader="activeLoader">
      </load-dots>
    </div>
  </div>
</template>

<script>
import LoadDots from '../pages/loadpagedots.vue';

  export default {
    components: {
      'load-dots': LoadDots,
    },
    props: {
      'activeLoader': Boolean,
    },
    data(){
      return {
        loadingDots: true,
      }
    },
    mounted() {
      console.log('New component mounted.')
    }
  }
</script>

<style lang="css">
.load-container {
  width: 100%;
  min-height: 100vh;
  background-color: #f2f2f2;
  position: absolute;
  bottom: 0;
  left: 0;
  z-index: 500;
  overflow: hidden;
}

.load {
  margin: 20% auto 0 auto;
  text-align: center;

}
</style>
