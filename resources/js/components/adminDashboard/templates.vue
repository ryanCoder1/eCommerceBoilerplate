<template>
  <div id="dashboardTemplatesContainer" class="dashboard-page-containers">
     <h3>Templates</h3>
      <h4 class="text-secondary" >Template to choose for website</h4>

        <div class="templates-flex">
          <ul>
            <li v-for="(template, index) in templates">
              <div class="templates-li-container">
                  <img :src="'../storage/images/' + template.image_path + '/' + template.image_name" alt="template image">
                  <p>{{ template.name }}</p>
                  <div class="template-check" >
                    <input
                    class="template-checkbox"
                    type="checkbox"
                    :ref="'template' + index"
                    :checked="using.template_name == template.name && usingNow ? true : false"
                    v-on:change="saveTemplateChoice(index, template.name)"/>
                  </div>
              </div>
            </li>
          </ul>
        </div>
        <!-- Error/Success messages from api -->
        <p class="error-msg" v-if="errors != null">{{ errors }}</p>
        <p class="success-msg" v-if="success != null">{{ success }}</p>

        <div class="dashboard-page-btn" v-if="showSave" v-on:click="saveTemplateUse()">
          <input type="button"   value="Save Template" v-if="!loading"/>
          <load-dots
           v-if="loading"
           v-bind:loading-dots="loadingDots"
           >
         </load-dots>
       </div>
  </div>
</template>

<script>

import LoadDots from '../pages/loaddots.vue';
import { errorHandle } from '../../helper/errors';

  export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        templates: [],
        templatesChoice: [
          {
            templateName: '',
          }
        ],
        showSave: false,
        using: [],
        usingNow: true,
      }
    },
    mounted() {
      this.showTemplates();
      this.showTemplateUse();
    },
    methods: {
      saveTemplateChoice: function(index, name){

        if(this.templatesChoice.length){

            for(let i = 0; i < this.templates.length; i++){
              // remove all elements in array
                this.templatesChoice.splice(i, 1);
              // set checked = false to all checkboxes
                this.$refs['template' + i][0].checked = false;
            }
          }
          this.templatesChoice.push({
            templateName: name,
          });
          if(this.templatesChoice !== this.using.template_name){
            this.usingNow = false;
          }else{
            this.usingNow = true;
          }
          // add checked = true to checked checkbox by index
          this.$refs['template' + index][0].checked = true;
          // set showSave = true as there is a checked box
          this.showSave = true;
      },
      showTemplates: function(){
          // for scope of this within axios
          let self = this;

            axios.post('/templatesshow',
            {
              Authorization: self.$store.state.admin.currentAdmin.token,
             }
            )
             .then((res) => {
               if(res.data.templates.length && res.data.templates !== undefined){
                 self.templates = res.data.templates;
              }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Template Save', self);
            })

        },
        showTemplateUse: function(){
            // for scope of this within axios
            let self = this;

              axios.post('/templateuseshow',
              {
                Authorization: self.$store.state.admin.currentAdmin.token,
               }
              )
               .then((res) => {
                 console.log(res.data);
                 if(res.data.using.length && res.data.using !== undefined){
                   self.using = res.data.using[0];
                }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Template Save', self);
              })

          },
        saveTemplateUse: function(){
            // for scope of this within axios
            let self = this;
            console.log('here ' + this.templatesChoice[0].templateName);
              axios.post('/templateusestore',
              {
                template_name: self.templatesChoice[0].templateName,
                Authorization: self.$store.state.admin.currentAdmin.token,
               }
              )
               .then((res) => {
                 if(res.data.success){
                   self.showSave = false;
                   self.success = 'Update the template to use successfully.';
               }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Template Save', self);
              })

          },
    }
  }
</script>

<style scoped>
.templates-flex ul {
  display: flex;
  display: -webkit-flex;
  flex-wrap: wrap;
  justify-content: space-around;
  gap: 25px;
  width: 100%;
}
.templates-flex ul li {
  box-shadow: 0 0 20px 1px rgba(0,0,0, .2);
  border: solid 10px #FFFFFF;
  border-radius: 8px;
  padding: 0;
  list-style: none;
}
.templates-li-container img{
  width: 300px;
  height: 200px;
  border: solid thin #ededed;

}
.templates-li-container p {
  color: #213B50;
  text-align: center;
  padding: 10px 0;
  margin: 0;
  font-size: 18px;
  font-weight: bold;
  text-transform: uppercase;
  border-top: solid thin #ededed;
  background-color:  #ededed;
}
.template-check {
  text-align: center;
  padding: 5px 0;
  margin: 0;
  background-color:  #ededed;
}
.template-check > input[type=checkbox] {
  width: 20px;
  height: 20px;
}
</style>
