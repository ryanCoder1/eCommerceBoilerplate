<template>
  <div class="banner-client-container">

    <div class="grid-two" v-if="banNum == 1">
      <div
      v-if="banners.length"
      class="banner-client-info-two">
        <h3 class="banner-client-title-two" v-if="banners.length && banners[1].title != 'null'">{{ banners[1].title }}</h3>
        <p class="banner-client-caption-two" v-if="banners.length && banners[1].caption != 'null'">{{ banners[1].caption }}</p>
      </div>

      <ul class="banner-client-ul-two">
        <li v-if="banners.length">
          <div class="banenr-client-li-container">
            <img class="banner-client-image" :src="'../storage/images/' + banners[1].image_path + '/' + banners[1].image_name" alt="banner image">
          </div>
          <span
          class="banner-background-two" >
          </span>
        </li>
      </ul>
    </div>

    <!--

    BANNER NUMBER 1

  -->
    <div class="grid-one" v-if="banNum == 0">
      <ul class="banner-client-ul-one">
        <li v-if="banners.length">
          <div class="banenr-client-li-container">
            <img class="banner-client-image" :src="'../storage/images/' + banners[0].image_path + '/' + banners[0].image_name" alt="banner image">
          </div>
          <span
          class="banner-background-one" >
          </span>
        </li>
      </ul>
      <div
      v-if="banners.length"
      class="banner-client-info-one">
        <h3 class="banner-client-title-one" v-if="banners.length && banners[0].title != 'null'">{{ banners[0].title }}</h3>
        <p class="banner-client-caption-one" v-if="banners.length && banners[0].caption != 'null'">{{ banners[0].caption }}</p>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      'banNum': Number,
    },
    data(){
      return{
        banners: [],
      }
    },
  created () {

  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll);
  },
    mounted() {
      this.showBanner();

    },
    watch:{
      banners: function(newBan, oldBan){
        if(newBan){
            window.addEventListener('scroll', this.handleScroll);
        }
      }
    },
    methods: {
      handleScroll: function(){
        let self = this;
        if(this.banNum == 0){
          setTimeout(function(){
              self.bannerMoveOne();
          }, 150);

        }
        if(this.banNum == 1){
          setTimeout(function(){
              self.bannerMoveTwo();
          }, 150);
        }
      },
      bannerMoveOne: function(){

          let elemt = document.getElementsByClassName('banner-client-ul-one')[0];
          let elemtInfo = document.getElementsByClassName('banner-client-info-one')[0];
          let elemtTop = elemt.offsetHeight;

          if(window.pageYOffset > elemtTop){
              elemt.classList.add('banner-client-ul-one-move');
              elemtInfo.classList.add('banner-client-info-one-move');
          }

      },
      bannerMoveTwo: function(){

          let elemt = document.getElementsByClassName('banner-client-ul-two')[0];
          let elemtInfo = document.getElementsByClassName('banner-client-info-two')[0];
          let elemtTop = elemt.offsetHeight;

          if(window.pageYOffset > elemtTop){
              elemt.classList.add('banner-client-ul-two-move');
              elemtInfo.classList.add('banner-client-info-two-move');
          }

      },
      showBanner: function(){
          // for scope of this within axios
          let self = this;
          console.log('called');
            axios.post('/bannershowclient'
              )
             .then((res) => {
              if(res.data.banners.length && res.data.banners !== undefined){
                self.banners = res.data.banners
              }

            }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Product Info Get', self);

            })
        },
    }
  }
</script>

<style lang="css">
.banner-client-container {
  width: 100%;
  min-height: 700px;
  position: relative;
  padding: 150px 20px 0 20px;
  margin: 0;
}
.grid-one, .grid-two {

  display: grid;
  grid-template-columns: 1fr 1fr;
  justify-content: space-around;
  align-content: center;
  grid-column-gap: 40px;
}
.grid-one {
  grid-template-areas: "imageOne infoOne";
}
.grid-two {
  grid-template-areas: "infoTwo imageTwo"
}
.banner-client-ul-one {
  grid-area: imageOne;
  width: 100%;
  max-width: 500px;
  padding: 0;
  margin: 0;
  position: relative;
  z-index: 10;
  list-style: none;
  transform: translateX(-400px);
  opacity: 0;
}
.banner-client-ul-one-move {

  animation: moveOne 1s ease-in-out 1s forwards;
}
@keyframes moveOne {
  100% {
    transform: translateX(0px);
    opacity: 1;
  }
}
.banner-client-ul-two {
  grid-area: imageTwo;
  width: 100%;
  max-width: 500px;
  padding: 0;
  margin: 0;
  position: relative;
  z-index: 10;
  list-style: none;
  transform: translateX(400px);
  opacity: 0;
}
.banner-client-ul-two-move {

  animation: moveTwo 1s ease-in-out 1s forwards;
}
@keyframes moveTwo {
  100% {
    transform: translateX(0px);
    opacity: 1;
  }
}
.banner-client-ul-one li, .banner-client-ul-two li {
  position: relative;
  padding: 0;
  margin: 0;
}

.banner-client-li-container {
  position: relative;
  text-align: center;
}
.banner-client-image {
  width: 100%;
  height: 400px;
  margin: 0;
  padding: 0;
  object-fit: cover;
  object-position: top;
  border: solid 3px #FFFFFF;
  transition: .3s;
}
.banner-client-info-one {
  grid-area: infoOne;
  max-width: 500px;
  width: 100%;
  position: relative;
  top: 150px;
  white-space: normal;
  line-height: normal;
  text-align: center;
  padding: 10px;
  transform: translateY(-50px);
  opacity: 0;
  }
  .banner-client-info-one-move {

    animation: moveInfoOne 1.4s ease-in-out 1s forwards;
  }
  @keyframes moveInfoOne {
    100% {
      transform: translateY(0px);
      opacity: 1;
    }
  }
.banner-client-info-two {
  grid-area: infoTwo;
  max-width: 500px;
  width: 100%;
  position: relative;
  top: 150px;
  left: 50px;
  white-space: normal;
  line-height: normal;
  text-align: center;
  padding: 10px 20px;
  transform: translateY(-50px);
  opacity: 0;
  }
  .banner-client-info-two-move {

    animation: moveInfoTwo 1.4s ease-in-out 1s forwards;
  }
  @keyframes moveInfoTwo {
    100% {
      transform: translateY(0px);
      opacity: 1;
    }
  }
.banner-client-title-one, .banner-client-title-two {
    color: #213B50;
    font-size: 22px;
    padding: 0 0 10px 0;
    display: inline-block;
    border-bottom: solid thin rgb(228, 228, 228);
}
.banner-client-caption-one, .banner-client-caption-two {
    color: rgb(138, 138, 138);
    font-size: 18px;
    padding: 10px 0 0 0;
}
.banner-background-one {
    width: 500px;
    height: 400px;
    margin: 0;
    padding: 0;
    display: block;
    background-color: #213B50;
    position: relative;
    top: -375px;
    left: 50px;
    z-index: -1;
    border: solid 3px #FFFFFF;

}
.banner-background-two {
    width: 500px;
    height: 400px;
    margin: 0;
    padding: 0;
    display: block;
    background-color: #2ec153;
    position: relative;
    top: -375px;
    left: 50px;
    z-index: -1;
    border: solid 3px #FFFFFF;

}
@media only screen and (max-width: 1000px){


}
</style>
