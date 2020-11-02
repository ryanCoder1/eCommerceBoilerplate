<template>
  <div>
    <div class="modal-shade"><a id="anchor"></a>
      <div class="modal-container">
        <div  class="delete-modal delete-modal-relative col-xs-11 col-sm-10 col-md-7 col-lg-5 mx-1 text-right">
          <div class="py-4" v-if="verifyMsg != ''">
            <p class="delete-modal-text text-left text-secondary" v-if="!confirm">{{ verifyMsg }} </p>
            <div class="text-center mt-2" v-if="confirmDot">
              <span class="circle-one loader-dots"></span>
              <span class="circle-two loader-dots"></span>
              <span class="circle-three loader-dots"></span>
            </div>
            <button class="delete-resume-btn text-right mr-2" v-if="!confirm" v-on:click="handler1('closeMessage', 'resumeProcess')">Confirm</button>
            <button class="delete-cancel-btn text-right" v-if="!confirm" v-on:click="handler2('closeMessage', 'cancelMessage')">Cancel</button>
          </div>
          <div  class="py-4" v-if="verifyMsgFollower != ''">
            <p class="delete-modal-text text-left text-secondary">{{ verifyMsgFollower }} </p>
            <button class="delete-resume-btn text-right mr-2" v-on:click="handler2('closeMessage', 'cancelMessage')">Ok</button>
          </div>
          <div class="delete-modal-complete-container text-center py-4 px-3" v-if="verifyMsgComplete != ''">
            <span v-bind:class="{'delete-modal-check':(verifyMsgComplete != '')}">
              <i class="fa fa-check"></i>
            </span>
            <p  v-bind:class="{'delete-modal-complete-text':(verifyMsgComplete != '')}" class=" text-secondary py-2">{{ verifyMsgComplete }} </p>
            <button class="delete-resume-btn text-right mr-2" v-on:click="handler3('closeMessage')">Ok</button>
          </div>
          <div class="delete-modal-complete-container text-center py-4 px-3" v-if="verifyMsgError != ''">
            <p  v-bind:class="{'delete-modal-complete-text':(verifyMsgError != '')}" class=" text-secondary py-2">{{ verifyMsgError }} </p>
            <button class="delete-resume-btn text-right mr-2" v-on:click="handler3('closeMessage')">Ok</button>
          </div>
      </div>
    </div>
  </div>
</div>
</template>



<script>


export default {
    props: [
      'verifyMsg',
      'verifyMsgFollower',
      'verifyMsgComplete',
      'verifyMsgError',

    ],
    data(){
      return{
        confirmDot: false,
        confirm: false,
      }
    },
    watch: {
      verifyMsgComplete: function(){
        this.confirmDot = false;
      },
      verifyMsgError: function(){
        this.confirmDot = false;
      }

    },
    methods: {
        cancelMessage: function (value) {
         this.$root.$emit('clickCancelMessage', true);
       },
        resumeProcess: function (value) {

          this.$root.$emit('clickResumeMessage', true);
          this.confirmDot = true;
          this.confirm = true;
        },
        closeMessage: function (value) {
           this.$root.$emit('clickCloseMessage', false);
         },
         closeFinishedMessage: function (value) {

            this.$root.$emit('clickCloseFinishedMessage', false);
          },
         handler1:function(arg1,arg2){
            this.closeMessage(arg1);
            this.resumeProcess(arg2);
              },
        handler2:function(arg1,arg2){
            this.cancelMessage(arg1);
            this.closeMessage(arg2);
        },
        handler3:function(arg2){
            this.closeFinishedMessage(arg2);
        }
    }

}


</script>
<style>


</style>
