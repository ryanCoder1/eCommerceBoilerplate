<template>
  <div class="">
    <form class="form py-5 pr-3">
      <!-- <p>Listed Social Media</p>
      <p v-if="socialMedia.length">{{ socialMedia }}</p>
      <p v-else>One not available</p> -->
      <h4>To accomplish a link. Visit your social media page. Copy the browser Url and paste it to the update area. </h4>
      <!-- Array of social media website names -->
        <div class="inputContainer" v-for="(website, index) in sMObjs" :key="index">
          <p>
            <span class="social-media-title">{{ website.name }}</span>
            <span class="social-media-break-line"></span>
            <span class="social-media-add" v-on:click="updateWebsite(index)">Update</span>
            <span v-if="website.showRemove == index"> / </span>
            <span
              class="social-media-remove"
              v-if="website.showRemove == index"
              v-on:click="removeWebsite(index)">
              Remove
            </span>
            <p class="add-social-media" v-if="website.showAdd == index">
              <input class="form-control"
                type="text"
                v-bind:class="{formInput: isActive}"
                placeholder="Add Social Media"
                :ref="'link' + index"
                :value="website.link != 'null' ? website.link : ''"
                >
            </p>
            <!-- the social media link after added to links -->
            <p class="social-media-add-links" v-if="website.showRemove == index">{{ sMObjs[index].link }}</p>
            <p class="bg-danger text-light p-2 ml-4 d-inline" v-if="sMObjs[index].error != null">{{ sMObjs[index].error }}</p>
          </p>
          <div
            class="dashboard-social-media-btn mb-3"
            v-if="website.showAdd == index"
            v-on:click="addWebsiteLink(index)">
            <input type="button" value="Add to list for saving" v-if="!loading && !updatedVis"/>
            <load-dots
             v-if="loading"
             v-bind:loading-dots="loadingDots"
             >
            </load-dots>
             <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
         </div>
        </div>

        <!-- Error/Success messages from api -->
        <p class="error-msg" v-if="errors != null">{{ errors }}</p>
        <p class="success-msg" v-if="success != null">{{ success }}</p>




        <div
          class="dashboard-page-btn"
          v-if="saveVis"
          v-on:click="saveLinks()">
          <input type="button" value="Save all Social Media links" v-if="!loading && !updatedVis"/>
          <load-dots
           v-if="loading"
           v-bind:loading-dots="loadingDots"
           >
          </load-dots>
           <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
        </div>
      </form>

  </div>
</template>

<script>
import { errorHandle } from '../../helper/errors';
  export default {
    data(){
      return{
          socialMedia: [],
          link: '',
          sMObjs: [

              {
               name:'facebook',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name:'twitter',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'linkedin',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'youtube',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'pinterest',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'instagram',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'reddit',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'snapchat',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'tumblr',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'nextdoor',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'quora',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
              {
               name: 'flickr',
               link: '',
               showAdd: null,
               showRemove: null,
               error: null,
             },
          ],
          isActive: false,
          loading: false,
          loadingDots: true,
          updatedVis: false,
          showSave: false,
          errors: null,
          success: null,
          saveVis: false,

      }
    },
    mounted() {
      this.getSocialMedia();
    },
    methods: {
      addWebsiteLink: function(index){
        this.sMObjs[index].link = this.$refs['link' + index][0].value;
        this.sMObjs[index].showAdd = null;
        this.sMObjs[index].showRemove = index;
        this.saveVis = true;
      },
      updateWebsite: function(index){
        this.sMObjs[index].showAdd = index;
        this.adjustSideBarHeight(true);
        this.clearMsgs();
      },
      removeWebsite: function(index){
        this.sMObjs[index].link = null;
        this.sMObjs[index].showRemove = null;
        this.saveVis = true;
        this.clearMsgs();
      },
      clearMsgs: function(){
        this.errors = null;
        this.success = null;
      },
      adjustSideBarHeight: function(elem){
        this.$root.$emit('adjustSideBarHeight', elem);
      },
      fillsMObjs: function(){
        // update sMObjs array of objects from DB results
        for(let m = 0; m < this.sMObjs.length; m++){
          for(let i = 0; i < this.socialMedia.length; i++){

              if(this.socialMedia[i].name == this.sMObjs[m].name){
                this.sMObjs[m].link = this.socialMedia[i].link;
                if(this.sMObjs[m].link != 'null'){
                    this.sMObjs[m].showRemove = m;
              }
            }
          }
        }

      },
      getSocialMedia: function(){
          // for scope of this within axios
          let self = this;
          let count = 0;
          let fd = new FormData();

             for(let i = 0; i < self.sMObjs.length; i++){

                  fd.append('socialMediaName' + count, self.sMObjs[i].name);
                  fd.append('socialMediaLink' + count, self.sMObjs[i].link);
                  count++;
               }

              fd.append('socialMediaCount', self.sMObjs.length);
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/socialmediashow',fd,
              )
             .then((res) => {
               console.log(res.data.socialmedia);
               if(res.data.socialmedia != undefined && res.data.socialmedia.length ){
                   self.socialMedia = res.data.socialmedia;
                   self.fillsMObjs();
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Get Specials Use', self);
            })
        },
        saveLinks: function(){
            // for scope of this within axios
            let self = this;
            let count = 0;
            let fd = new FormData();

               for(let i = 0; i < self.sMObjs.length; i++){

                    fd.append('socialMediaName' + count, self.sMObjs[i].name);
                    fd.append('socialMediaLink' + count, self.sMObjs[i].link);
                    count++;
                 }

                fd.append('socialMediaCount', self.sMObjs.length);
                fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

              axios.post('/socialmediastore',fd,
                )
               .then((res) => {
                 if(res.data.socialmedia != undefined && res.data.socialmedia.length ){
                     self.socialMedia = res.data.socialmedia;
                     self.saveVis = false;
                     self.errors = null;
                     self.success = 'Social Media Links updated successfully!';
                     self.fillsMObjs();
                  }

              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Save Specials Use', self);
              })
          },

    }
  }
</script>

<style lang="css">
.social-media-title {
  color: rgb(126, 126, 126);
  font-size: 18px;
  text-transform: capitalize;
  font-weight: bold;
  min-width: 125px;
  display: inline-block;
}
.social-media-add, .social-media-remove {
  font-size: 16px;
  cursor: pointer;

}
.social-media-add {
  color: #213B50;
  margin-left: 25px;
}
.social-media-remove {
  color: rgb(208, 94, 95);
}
.social-media-add-links {
  font-size: 18px;
  color: rgb(68, 130, 203);
  font-weight: bold;
  word-wrap: break-word;
}
@media only screen and (max-width: 400px){
  .social-media-break-line {
    display: block;
  }
}

</style>
