<template>
  <div >
    <span
    :class="templateName + '-social-media-container'"
    v-for="(icon, index) in socialMedia"
    :key="index"
    v-if="icon.link != 'null'"

    v-on:mouseleave="fadeIconName($event)">
      <a
      :href="'//' + icon.link"
      v-on:click="externalLink(icon.link)"
      v-on:mouseover="showIconName($event)">
        <font-awesome-icon :icon="{ prefix: 'fab', iconName: icon.name }"  :class="templateName + '-social-media-icon'">
          {{ checkFonts(index)}}
        </font-awesome-icon>
      </a>
      <!-- show icon name on hover of icon for a few seconds -->
      <span
      :class="templateName + '-social-media-icon-name'"
      >{{ icon.name }}</span>
    </span>
  </div>
</template>

<script>
  export default {
    data(){
      return{
        socialMedia: [],
        pixel: 0,
        pixelPlus: 50,

      }
    },
    mounted() {
      this.getSocialMedia();
    },
    computed: {
      templateName: function(){
        if(this.$store.state.templateView){
           return this.$store.state.templateView;
         }
      }
    },
    methods: {
      fadeIconName: function(event){
        console.log('in fadeIconName');
        let elemt = event.path[0].children[1];
         elemt.classList.remove('social-media-icon-name-show');
         // elemt.style.opacity = 0;
      },
      showIconName: function(event){
        // show social media icon name on hover of icon by adding class
        let elemt = event.path[3].children[1];
          elemt.classList.add('social-media-icon-name-show');
      },
      externalLink: function(link){
        window.open(
            link,
            '_blank'
          );
      },
      checkFonts: function(index){
          // if element icon undefined set the marginLeft to 0px so there is no gap.
          setTimeout(function(){
              let elemtCont = document.getElementsByClassName('social-media-container')[index];
              if(elemtCont !== undefined){
                  if(elemtCont.children[0].children.length == 0){
                    elemtCont.style.marginLeft = '0px';
                  }
                }
          },100);
      },
      getSocialMedia: function(){
          // for scope of this within axios
          let self = this;
            axios.post('/socialmediashowclient'
              )
             .then((res) => {
               console.log(res.data.socialmedia);
               if(res.data.socialmedia != undefined && res.data.socialmedia.length ){
                   self.socialMedia = res.data.socialmedia;
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Get Specials Use', self);
            })
        },
    }
  }
</script>

<style lang="css">

</style>
