var mixinsSession = {
  created: function () {
    this.sessionDatas();
  },
  methods: {
    sessiondatas: function(){
     console.log('hello world');

      $.get('mySession', function(response) {
        this.sessiondata = response;
          console.log(this.sessiondata);
           this.sessiondata;
      });
    }
  }
}
