<template>
  <div class="banners-container banners-edit-child-container">
      <h4 class="banners-head">
        Check up to {{ numOfBanners }} banners to show on website
       <br/>
        <span :class="{chosenOver: isActive}">Chosen = {{ bannersChecked }}</span>
        <div class="dashboard-add-btn" v-if="saveBtnShow" v-on:click="saveBanners()">
          <input type="button" value="Save Banner list" v-if="!loading && !updatedVis"/>
          <load-dots
           v-if="loading"
           v-bind:loading-dots="loadingDots"
           >
          </load-dots>
           <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
       </div>
      </h4>
      <h5 class="banners-none mt-5 mx-2 text-secondary" v-if="!banners.length">There are no banners on file</h5>
      <ul class="banners-ul">

        <li v-for="(banner, index) in banners">
          <div class="banners-image" v-if="banner.image_name != null">
            <img :src="'../storage/images/' + banner.image_path + '/' + banner.image_name" alt="banner image">
          </div>
            <div class="banners-parent">

                <div class="banner-check" >
                  <input class="banner-checkbox" type="checkbox" :ref="'banner' + banner.id" v-on:change="saveBanner(index, banner.id)"/>
                </div>
                <div class="banners-image-td">

                    <div class="banners-title">{{ banner.title}}</div>
                    <div class="banners-caption">{{ banner.caption}}</div>
                 </div>
             </div>

           </li>
        </ul>
  </div>
</template>

<script>
import { errorHandle } from '../../helper/errors';
import LoadDots from '../pages/loaddots.vue';

  export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
        banners: [],
        bannerList: [],
        bannersIn: [],
        bannersChecked: 0,
        check: false,
        numOfBanners: 2,
        saveBtnShow: false,
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        updatedVis: false,
        updated: 'Updated!',
        isActive: false,
        bannerSaveLength: 2,
      }
    },
    mounted() {
      this.showBanners();
      let self = this;
      setTimeout(function(){
        self.adjustSideBarHeight();
      },400);

    },
    methods: {
      adjustSideBarHeight: function(elem){
        this.$root.$emit('adjustSideBarHeight', elem);
      },
      addBanner: function(index, id){

         this.bannerList.push({
           index: index,
           show_on_website: 1,
           bannerId: id,
         });
      },
      removeBanner: function(index, id){
        for(let i = 0; i < this.bannerList.length; i++){
          if(this.bannerList[i].bannerId == id){
            this.bannerList.splice(i, 1);
          }
        }
      },
      addBannerIn: function(){
        let count = 0;
        for(let i = 0; i < this.banners.length; i++){
          if(this.banners[i].show_on_website == 1){
            count++;
            this.bannerList.push({
              show_on_website: 1,
              bannerId: this.banners[i].id,
            });
            this.$refs['banner' + this.banners[i].id][0].checked = true;
            }
          }
          this.bannersChecked = count;
      },
      clearBanner: function(index, id){
        for(let i = 0; i < this.bannerList.length; i++){
            this.bannerList.splice(i, 1);
        }
      },
      saveBanner: function(index, id){
        console.log(this.$refs['banner' + id][0].checked)
        // the product is checked(index)
          if(this.$refs['banner' + id][0].checked){
            // add 1 to when checked is true
            this.bannersChecked++;
            // if between 1 and bannerSaveLength, run methods
            if(this.bannersChecked > 0 && this.bannersChecked <= this.bannerSaveLength){
              this.saveBtnShow = true;
              this.addBanner(index, id);
              this.adjustSideBarHeight();
            }else{
              if(this.bannersChecked > this.bannerSaveLength){
                // make isActive true and turn on style class of red font
                this.addBanner(index, id);
                this.isActive = true;
              }
              this.saveBtnShow = false;
            }

          }else{
            // subtract 1 on checked(index) == false
            this.bannersChecked--;
            if(this.bannersChecked > 0 && this.bannersChecked <= this.bannerSaveLength){
              this.saveBtnShow = true;
              this.removeBanner(index, id);
            }else{
              this.saveBtnShow = false;
            }
            if(this.bannersChecked == 0 ){
              this.removeBanner(index, id);
            }
            if(this.bannersChecked <= this.bannerSaveLength){
              this.isActive = false;
            }
          }

      },
      removeUpdated: function(){
            let self = this;
            setTimeout(function(){
               self.updatedVis = false;
               self.saveBtnShow = false;
            }, 2000);
      },
      showBanners: function(){

          // for scope of this within axios
          let self = this;

            axios.post('/bannershow',
            {
              Authorization: self.$store.state.admin.currentAdmin.token,
            }
             )
             .then((res) => {
                if(res.data.banners.length && res.data.banners !== undefined){
                    self.banners = res.data.banners;
                    self.clearBanner();
                    setTimeout(function(){
                      self.addBannerIn();
                    },200);
                 }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Banner Show', self);
            })

        },
      saveBanners: function(){
        console.log(this.bannerList);
          // for scope of this within axios
          let self = this;
          let count = 0;
          let fd = new FormData();

            for(let i = 0; i < self.bannerList.length; i++){
                  fd.append('bannerId' + count, self.bannerList[i].bannerId);
                  fd.append('show_on_website' + count, self.bannerList[i].show_on_website);
                  count++;
              }
              fd.append('count', count);
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/bannerchoose', fd,
             )
             .then((res) => {
                if(res.data.success){
                  self.loading = false;
                  self.updatedVis = true;
                  self.removeUpdated();
                  self.showBanners();
                 }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Banner Save', self);
            })

        },
        getBannersOnHome: function(){
            // for scope of this within axios
            let self = this;
              axios.post('/bannershomeshow',
              {
                Authorization: self.$store.state.admin.currentAdmin.token
              }
                )
               .then((res) => {
                 if(res.data.banners !== undefined && res.data.banners.length ){
                   console.log(res.data);
                   self.bannersIn = res.data.banners;
                   setTimeout(function(){
                        self.addBannersIn();
                   },200);

                   self.bannersChecked = res.data.banners.length;
                  }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Banner Get', self);
              })
        },
    }
  }
</script>

<style lang="css">
.banners-container {
  max-width: 400px;
  width: 100%;
  padding: 0;
  margin: 25px auto 0 auto;
  border: solid 10px #FFFFFF;
  box-shadow: 0 0 20px 1px rgba(0,0,0, .2);
}
.banners-head {
  font-size: 20px;
  text-align: center;
  overflow-x: hidden;
}
.banners-ul {
  height: 400px;
  width: 100%;
  list-style: none;
  padding: 0;
  text-align: center;
  overflow-y: scroll;
}
.banners-ul li {
  width: 100%;
  padding: 0;
  margin-top: 15px;
  border-bottom: solid 1px #dbdbdb;
}
.banners-parent {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
}

.banner-check {
  padding: 10px 25px !important;

}
input.banner-checkbox {
  border: solid 5px #6c6c6c;
  width: 20px;
  height: 20px;
}
.banners-image-td {
  max-width: 300px;
  width: 100%;
  margin-right: 20px;
  text-align: center;
}

.banners-image img {
  max-width: 300px;
  width: 100%;
  height: 75px;
}
.banners-title {
  font-size: 17px;
  color: #213B50;
  font-weight: bold;
  margin-top: 10px;
  word-wrap: break-word;
}
.banners-caption {
  width: 90%;
  font-size: 13px;
  color: #6c6c6c;
  font-weight: bold;
  margin-top: 10px;
  /* word-wrap: break-word; */
}
.chosenOver {
  color: rgb(195, 44, 62);
}
</style>
